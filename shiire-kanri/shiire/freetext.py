"""「キタムラ Nikon F100 仕入38500 送料1000 T… 7/11」のような1行入力の解釈。"""
from __future__ import annotations

import re
import unicodedata
from datetime import date

from . import config
from .ledger import Entry

DATE_RE = re.compile(r"^(?:(\d{4})[/\-.年])?(\d{1,2})[/\-.月](\d{1,2})日?$")
PRICE_RE = re.compile(r"^(?:仕入|仕入れ|金額|価格|購入)[:：]?(\d[\d,]*)円?$")
SHIP_RE = re.compile(r"^送料[:：]?(\d[\d,]*)円?$")
COUPON_RE = re.compile(r"^(?:クーポン|coupon)[:：]?-?(\d[\d,]*)円?$", re.I)
INVOICE_RE = re.compile(r"^T\d{13}$")
BARE_PRICE_RE = re.compile(r"^[¥￥]?(\d[\d,]*)円?$")
FLAGS = {"返品": "returned", "修理": "repair", "ジャンク": "junk"}
PAYMENTS = {"amex": "AMEX", "paypay": "PAYPAY", "paypayカード": "PAYPAY", "現金": "現金"}


def _num(s: str) -> int:
    return int(s.replace(",", ""))


def parse_line(text: str, year: int = config.LEDGER_YEAR, base=None) -> Entry:
    """先頭トークン=店舗名、残りのうち金額・送料・T番号・日付・支払以外=商品名。"""
    tokens = unicodedata.normalize("NFKC", text).split()
    if not tokens:
        raise ValueError("空の入力です")
    shop, rest = tokens[0], tokens[1:]
    name_parts: list[str] = []
    fields: dict = {}
    bare_prices: list[int] = []
    for tok in rest:
        if m := DATE_RE.match(tok):
            fields["date"] = date(int(m[1]) if m[1] else year, int(m[2]), int(m[3]))
        elif m := PRICE_RE.match(tok):
            fields["price"] = _num(m[1])
        elif m := SHIP_RE.match(tok):
            fields["shipping"] = _num(m[1])
        elif m := COUPON_RE.match(tok):
            fields["coupon"] = _num(m[1])
        elif INVOICE_RE.match(tok.upper()):
            fields["invoice"] = tok.upper()
        elif tok.lower() in PAYMENTS:
            fields["payment"] = config.resolve_payment(PAYMENTS[tok.lower()], base)
        elif tok in FLAGS:
            fields[FLAGS[tok]] = True
        elif (m := BARE_PRICE_RE.match(tok)) and (tok.endswith("円") or tok[0] in "¥￥"):
            bare_prices.append(_num(m[1]))
        else:
            name_parts.append(tok)
    if "price" not in fields and bare_prices:
        fields["price"] = bare_prices.pop(0)
    if "price" not in fields:
        raise ValueError(f"金額が読み取れません: {text}")
    if "date" not in fields:
        raise ValueError(f"日付が読み取れません: {text}")
    return Entry(
        name=" ".join(name_parts),
        shop=shop,
        shop_type="実店舗",
        evidence=config.MANUAL_EVIDENCE,
        **fields,
    )
