"""仕入管理システムの設定値（パス・カード表示・固定インボイス等）。"""
from __future__ import annotations

import json
import os
from pathlib import Path

# 作業フォルダ（環境変数 SHIIRE_BASE_DIR で上書き可）
BASE_DIR = Path(os.environ.get("SHIIRE_BASE_DIR", r"D:\desktop\オークション\仕入管理システム"))
LEDGER_YEAR = 2026
LEDGER_NAME = f"仕入台帳{LEDGER_YEAR}.xlsx"
DASHBOARD_NAME = "仕入台帳ダッシュボード.html"

BACKUP_DIR = "バックアップ"
EXPORT_DIR = "書き出し"
EVIDENCE_DIR = "仕入証憑　画像あり"
INBOX_DIR = "領収書受信箱"

# バックアップ保持数（台帳は最新2・ジャンク台帳は最新1）
BACKUP_KEEP_LEDGER = 2
BACKUP_KEEP_JUNK = 1

# 支払カード表示は作業フォルダの cards.json に置く（リポジトリには入れない）
# 例: {"AMEX": "クレジットカード AMEX 下4けた XXXX", "PAYPAY": "クレジットカード PAYPAY 下4けた XXXX"}
CARDS_FILE_NAME = "cards.json"


def cards_path(base: Path | None = None) -> Path:
    env = os.environ.get("SHIIRE_CARDS_FILE")
    return Path(env) if env else (base or BASE_DIR) / CARDS_FILE_NAME


def load_cards(base: Path | None = None) -> dict[str, str]:
    """cards.json を読む。キーは大文字（AMEX / PAYPAY 等）。無ければ空。"""
    p = cards_path(base)
    if not p.exists():
        return {}
    data = json.loads(p.read_text(encoding="utf-8-sig"))  # メモ帳のBOM付きUTF-8も可
    return {str(k).upper(): str(v) for k, v in data.items()}


def resolve_payment(value: str, base: Path | None = None) -> str:
    """"AMEX" などの略称を cards.json の表示に展開。未登録の略称は例外、それ以外はそのまま。"""
    if not value:
        return value
    cards = load_cards(base)
    key = value.strip().upper()
    if key in cards:
        return cards[key]
    if key in ("AMEX", "PAYPAY"):
        raise KeyError(f"{cards_path(base)} に {key} のカード表示がありません（cards.example.json を参照）")
    return value


# LINEヤフーのT番号は絶対に書かない
FORBIDDEN_INVOICES = {"T4010401039979"}

# 固定マッピング（過去登録より優先）。キーワードは正規化後の店舗名に部分一致で判定
FIXED_INVOICES = [
    (("バイセル",), "T4010001074187"),
    (("キタムラ", "北村写真機"), "T3490001010673"),
]

SHOP_TYPES = ("ヤフオク", "ヤフオク個人", "実店舗")
MANUAL_EVIDENCE = "手動入力（実店舗）"

# 台帳レイアウト
ANNUAL_SHEET = "年間合計"
MONTH_SHEETS = [f"{m}月" for m in range(1, 13)]
TOTAL_LABEL = "合　計"
RETURN_LABEL = "（うち返品）"
HEADER_ROW = 2
FIRST_DATA_ROW = 3
RULE_LAST_ROW = 200  # ✓ドロップダウン・条件付き書式の最終行（データが増えたら自動拡張）
CHECK = "✓"

HEADERS = [
    "日付", "商品名", "金額", "送料", "合計金額", "店舗名", "住所", "支払方法",
    "店舗タイプ", "インボイス番号", "証憑", "備考", "返品", "修理", "ジャンク",
]
COL = {name: i + 1 for i, name in enumerate(HEADERS)}
COL_WIDTHS = [12, 40, 11, 9, 11, 24, 30, 30, 12, 17, 45, 28, 6, 6, 7]

# 書式
FONT_NAME = "Meiryo"
FONT_SIZE = 9
STRIPE_ODD = "FFF3E5F5"
STRIPE_EVEN = "FFE1BEE7"
HEADER_FILL = "FF7B1FA2"
TOTAL_FILL = "FF4A148C"
TAB_MONTH = "FF7B1FA2"
TAB_ANNUAL = "FF4A148C"
RETURN_BG, RETURN_FG = "FFCDD2", "C62828"
REPAIR_BG, REPAIR_FG = "FFF3E0", "E65100"


def ledger_path(base: Path | None = None) -> Path:
    return (base or BASE_DIR) / LEDGER_NAME
