"""MF仕訳帳（取引No/月日/借方金額）と台帳の照合 ―「インボイス調べて」。

【A】T番号 or 住所あり ／【B】両方空 ／【C】台帳に一致なし
"""
from __future__ import annotations

import csv
from dataclasses import dataclass, field
from datetime import date
from itertools import combinations
from pathlib import Path

from openpyxl import Workbook

from .ledger import Row, iter_rows, sanitize_invoice, to_date, to_int


@dataclass
class Journal:
    no: str
    date: date
    amount: int


@dataclass
class Match:
    journal: Journal
    rank: str  # "A" | "B" | "C"
    rows: list[Row] = field(default_factory=list)


def _row_total(r: Row) -> int:
    return to_int(r["金額"]) + to_int(r["送料"])


def _rank(rows: list[Row]) -> str:
    if not rows:
        return "C"
    ok = all(sanitize_invoice(r["インボイス番号"]) or r["住所"] for r in rows)
    return "A" if ok else "B"


def match(wb: Workbook, journals: list[Journal], day_tolerance: int = 7, max_group: int = 3) -> list[Match]:
    rows = [(r, to_date(r["日付"]), _row_total(r)) for r in iter_rows(wb)]
    rows = [x for x in rows if x[1]]
    used: set[tuple[str, int]] = set()
    out: list[Match] = []
    for j in journals:
        near = sorted(
            (x for x in rows if abs((x[1] - j.date).days) <= day_tolerance and (x[0].sheet, x[0].row) not in used),
            key=lambda x: abs((x[1] - j.date).days),
        )
        found: list[Row] = []
        for x in near:  # 単独一致（日付の近い順）
            if x[2] == j.amount:
                found = [x[0]]
                break
        if not found:  # まとめ取引は合算で一致を探す
            for k in range(2, max_group + 1):
                hit = next((c for c in combinations(near[:20], k) if sum(x[2] for x in c) == j.amount), None)
                if hit:
                    found = [x[0] for x in hit]
                    break
        used.update((r.sheet, r.row) for r in found)
        out.append(Match(j, _rank(found), found))
    return out


def load_journals_csv(path: Path, year: int) -> list[Journal]:
    """列: 取引No, 月日(M/D or YYYY/M/D), 金額。ヘッダー行は自動スキップ。"""
    out = []
    with path.open(encoding="utf-8-sig", newline="") as f:
        for rec in csv.reader(f):
            if len(rec) < 3 or not any(ch.isdigit() for ch in rec[0]):
                continue
            no, d, amt = (s.strip() for s in rec[:3])
            parts = [int(p) for p in d.replace("-", "/").split("/")]
            dt = date(*parts) if len(parts) == 3 else date(year, parts[0], parts[1])
            out.append(Journal(no, dt, to_int(amt)))
    return out


def report(matches: list[Match]) -> str:
    lines = ["| 取引No | 月日 | 金額 | 判定 | 台帳 | 店舗名 | インボイス/住所 |", "|---|---|---:|:-:|---|---|---|"]
    for m in matches:
        where = "・".join(f"{r.sheet}{r.row}行" for r in m.rows) or "—"
        shops = "・".join(str(r["店舗名"] or "") for r in m.rows) or "—"
        inv = "・".join(sanitize_invoice(r["インボイス番号"]) or str(r["住所"] or "") or "空" for r in m.rows) or "—"
        lines.append(f"| {m.journal.no} | {m.journal.date:%m/%d} | {m.journal.amount:,} | {m.rank} | {where} | {shops} | {inv} |")
    cnt = {k: sum(m.rank == k for m in matches) for k in "ABC"}
    lines.append("")
    lines.append(f"合計{len(matches)}件=A{cnt['A']}+B{cnt['B']}+C{cnt['C']}")
    return "\n".join(lines)


def numbers_only(matches: list[Match], ranks: str = "BC") -> str:
    """「取引番号だけ」→ ドット区切り横並び。"""
    return ".".join(m.journal.no for m in matches if m.rank in ranks)
