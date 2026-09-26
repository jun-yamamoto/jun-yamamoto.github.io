"""`仕入台帳ダッシュボード.html` の `const ENTRIES=[...]` を台帳全月から再生成する。"""
from __future__ import annotations

import json
import re
from pathlib import Path

from openpyxl import Workbook

from . import config
from .ledger import iter_rows, to_date, to_int

ENTRIES_RE = re.compile(r"const\s+ENTRIES\s*=\s*\[.*?\];", re.S)
TEMPLATE = Path(__file__).with_name("dashboard_template.html")


def build_entries(wb: Workbook) -> list[dict]:
    out = []
    for row in iter_rows(wb):
        d = to_date(row["日付"])
        price, ship = to_int(row["金額"]), to_int(row["送料"])
        out.append({
            "date": d.isoformat() if d else "",
            "month": int(row.sheet.rstrip("月")),
            "name": row["商品名"] or "",
            "price": price,
            "shipping": ship,
            "total": price + ship,
            "shop": row["店舗名"] or "",
            "type": row["店舗タイプ"] or "",
            "payment": row["支払方法"] or "",
            "invoice": row["インボイス番号"] or "",
            "evidence": row["証憑"] or "",
            "note": row["備考"] or "",
            "returned": row["返品"] == config.CHECK,
            "repair": row["修理"] == config.CHECK,
            "junk": row["ジャンク"] == config.CHECK,
        })
    return out


def regenerate(wb: Workbook, html_path: Path) -> int:
    entries = build_entries(wb)
    new_js = "const ENTRIES=" + json.dumps(entries, ensure_ascii=False) + ";"
    html = html_path.read_text(encoding="utf-8") if html_path.exists() else TEMPLATE.read_text(encoding="utf-8")
    if not ENTRIES_RE.search(html):
        raise ValueError(f"{html_path.name} に const ENTRIES=[...]; が見つかりません")
    # 置換文字列中の \ や \1 を解釈させないため lambda を使う
    html = ENTRIES_RE.sub(lambda m: new_js, html, count=1)
    html_path.write_text(html, encoding="utf-8")
    return len(entries)
