"""ロック確認・バックアップ・証憑パスなどのファイル操作。"""
from __future__ import annotations

import shutil
from datetime import date, datetime
from pathlib import Path

from . import config


class LedgerLockedError(RuntimeError):
    """Excel / LibreOffice で台帳が開かれている。"""


def check_locks(path: Path) -> None:
    """`~$*.xlsx` / `.~lock.*#` があれば編集を中止する（破損防止）。"""
    candidates = [
        path.with_name("~$" + path.name),
        path.with_name("~$" + path.name[2:]),  # Excel は先頭2文字を置き換えることがある
        path.with_name(f".~lock.{path.name}#"),
    ]
    found = [p for p in candidates if p.exists()]
    if found:
        raise LedgerLockedError(
            f"台帳が開かれています。閉じてから再実行してください: {', '.join(p.name for p in found)}"
        )


def backup(path: Path, now: datetime | None = None) -> Path | None:
    """`バックアップ/{名}.backup_{日時}.xlsx` へコピーし、古いものを削除する。"""
    if not path.exists():
        return None
    now = now or datetime.now()
    backup_dir = path.parent / config.BACKUP_DIR
    backup_dir.mkdir(parents=True, exist_ok=True)
    dest = backup_dir / f"{path.stem}.backup_{now:%Y%m%d_%H%M%S}{path.suffix}"
    shutil.copy2(path, dest)
    keep = config.BACKUP_KEEP_JUNK if "ジャンク" in path.stem else config.BACKUP_KEEP_LEDGER
    prune_backups(backup_dir, path, keep)
    return dest


def prune_backups(backup_dir: Path, path: Path, keep: int) -> list[Path]:
    olds = sorted(backup_dir.glob(f"{path.stem}.backup_*{path.suffix}"), reverse=True)[keep:]
    for p in olds:
        p.unlink()
    return olds


def evidence_path(base: Path, item_id: str, won: date) -> Path:
    """`仕入証憑　画像あり/{YYYY}年/{N}月/{商品ID}_{YYYYMMDD}.png`（月はゼロ埋めなし）。"""
    folder = base / config.EVIDENCE_DIR / f"{won.year}年" / f"{won.month}月"
    return folder / f"{item_id}_{won:%Y%m%d}.png"


def move_to_evidence(base: Path, src: Path, won: date) -> Path:
    """領収書受信箱のファイルを証憑フォルダ（年/月）へ移動する。"""
    folder = base / config.EVIDENCE_DIR / f"{won.year}年" / f"{won.month}月"
    folder.mkdir(parents=True, exist_ok=True)
    dest = folder / src.name
    n = 1
    while dest.exists():
        dest = folder / f"{src.stem}_{n}{src.suffix}"
        n += 1
    shutil.move(str(src), dest)
    return dest
