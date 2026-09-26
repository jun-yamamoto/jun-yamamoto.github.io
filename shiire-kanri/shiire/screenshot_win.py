"""かんたん決済 支払い明細のスクショ（Windows + Chrome のテンポラリウィンドウ方式）。

① chrome.exe --new-window で明細URLを新規ウィンドウに開く → ② 5秒待つ →
③ タイトルに「支払い明細」を含む Chrome ウィンドウを PrintWindow で撮影 → ④ ウィンドウを閉じる。
⑤ 保存PNGの目視検証（商品ID一致・支払金額合計・支払方法）は呼び出し側で必ず行う。

既知バグ対策:
- タブ切替直後は別商品が写る → 既存タブは使わず必ず新規ウィンドウ
- 小窓だと下部が切れる → 撮影前に画面の高さいっぱいまでウィンドウを広げる
- 最小化・別の仮想デスクトップだと真っ白 → 復元＋前面化し、PNG が白紙なら撮り直し
"""
from __future__ import annotations

import ctypes
import os
import subprocess
import time
from ctypes import wintypes
from datetime import date
from pathlib import Path

from .files import evidence_path

WINDOW_TITLE = "支払い明細"
WAIT_SEC = 5
WIDTH = 1280
PW_RENDERFULLCONTENT = 2
WM_CLOSE = 0x0010
SW_RESTORE = 9

CHROME_CANDIDATES = [
    Path(os.environ.get("ProgramFiles", r"C:\Program Files")) / r"Google\Chrome\Application\chrome.exe",
    Path(os.environ.get("ProgramFiles(x86)", r"C:\Program Files (x86)")) / r"Google\Chrome\Application\chrome.exe",
    Path(os.environ.get("LOCALAPPDATA", "")) / r"Google\Chrome\Application\chrome.exe",
]


def _chrome() -> str:
    env = os.environ.get("SHIIRE_CHROME")
    if env:
        return env
    for p in CHROME_CANDIDATES:
        if p.exists():
            return str(p)
    raise FileNotFoundError("chrome.exe が見つかりません（環境変数 SHIIRE_CHROME で指定可）")


def _user32():
    u = ctypes.windll.user32
    try:
        ctypes.windll.shcore.SetProcessDpiAwareness(2)  # 高DPIで切れないよう実ピクセルで扱う
    except Exception:  # noqa: BLE001
        u.SetProcessDPIAware()
    return u


def _find_window(user32, title: str) -> int | None:
    found: list[int] = []
    proto = ctypes.WINFUNCTYPE(wintypes.BOOL, wintypes.HWND, wintypes.LPARAM)

    def cb(hwnd, _):
        if not user32.IsWindowVisible(hwnd):
            return True
        cls = ctypes.create_unicode_buffer(256)
        user32.GetClassNameW(hwnd, cls, 256)
        if cls.value != "Chrome_WidgetWin_1":
            return True
        n = user32.GetWindowTextLengthW(hwnd)
        buf = ctypes.create_unicode_buffer(n + 1)
        user32.GetWindowTextW(hwnd, buf, n + 1)
        if title in buf.value:
            found.append(hwnd)
            return False
        return True

    user32.EnumWindows(proto(cb), 0)
    return found[0] if found else None


def _fit_window(user32, hwnd: int) -> None:
    user32.ShowWindow(hwnd, SW_RESTORE)
    user32.SetForegroundWindow(hwnd)
    work = wintypes.RECT()
    user32.SystemParametersInfoW(0x0030, 0, ctypes.byref(work), 0)  # SPI_GETWORKAREA
    user32.MoveWindow(hwnd, work.left, work.top, WIDTH, work.bottom - work.top, True)


def _capture(user32, hwnd: int, dest: Path) -> None:
    from PIL import Image

    gdi32 = ctypes.windll.gdi32
    rect = wintypes.RECT()
    user32.GetWindowRect(hwnd, ctypes.byref(rect))
    w, h = rect.right - rect.left, rect.bottom - rect.top
    hwnd_dc = user32.GetWindowDC(hwnd)
    mem_dc = gdi32.CreateCompatibleDC(hwnd_dc)
    bmp = gdi32.CreateCompatibleBitmap(hwnd_dc, w, h)
    gdi32.SelectObject(mem_dc, bmp)
    try:
        if not user32.PrintWindow(hwnd, mem_dc, PW_RENDERFULLCONTENT):
            raise RuntimeError("PrintWindow に失敗しました")

        class BITMAPINFOHEADER(ctypes.Structure):
            _fields_ = [("biSize", wintypes.DWORD), ("biWidth", wintypes.LONG), ("biHeight", wintypes.LONG),
                        ("biPlanes", wintypes.WORD), ("biBitCount", wintypes.WORD),
                        ("biCompression", wintypes.DWORD), ("biSizeImage", wintypes.DWORD),
                        ("biXPelsPerMeter", wintypes.LONG), ("biYPelsPerMeter", wintypes.LONG),
                        ("biClrUsed", wintypes.DWORD), ("biClrImportant", wintypes.DWORD)]

        bi = BITMAPINFOHEADER()
        bi.biSize = ctypes.sizeof(BITMAPINFOHEADER)
        bi.biWidth, bi.biHeight, bi.biPlanes, bi.biBitCount = w, -h, 1, 32  # 負の高さ=上から下
        buf = ctypes.create_string_buffer(w * h * 4)
        gdi32.GetDIBits(mem_dc, bmp, 0, h, buf, ctypes.byref(bi), 0)
        img = Image.frombuffer("RGB", (w, h), buf, "raw", "BGRX", 0, 1)
        dest.parent.mkdir(parents=True, exist_ok=True)
        img.save(dest)
    finally:
        gdi32.DeleteObject(bmp)
        gdi32.DeleteDC(mem_dc)
        user32.ReleaseDC(hwnd, hwnd_dc)


def is_blank(png: Path) -> bool:
    """ほぼ単色（真っ白/真っ黒）なら True。"""
    from PIL import Image

    lo, hi = Image.open(png).convert("L").getextrema()
    return hi - lo < 10


def capture_payment_detail(url: str, base: Path, item_id: str, won: date, retries: int = 2) -> Path:
    user32 = _user32()
    dest = evidence_path(base, item_id, won)
    last_err: Exception | None = None
    for _ in range(retries + 1):
        subprocess.Popen([_chrome(), "--new-window", url])
        hwnd = None
        try:
            time.sleep(WAIT_SEC)
            hwnd = _find_window(user32, WINDOW_TITLE)
            if hwnd is None:
                raise RuntimeError(f"「{WINDOW_TITLE}」ウィンドウが見つかりません（ログイン切れ？）")
            _fit_window(user32, hwnd)
            time.sleep(1.5)  # リサイズ後の再描画待ち
            _capture(user32, hwnd, dest)
            if is_blank(dest):
                raise RuntimeError("白紙のスクショ（最小化/別デスクトップ？）")
            return dest  # ⑤ 呼び出し側で PNG を開いて商品ID・金額・支払方法を目視確認すること
        except Exception as e:  # noqa: BLE001 - 撮り直す
            last_err = e
        finally:
            if hwnd:
                user32.PostMessageW(hwnd, WM_CLOSE, 0, 0)
                time.sleep(0.5)
    raise RuntimeError(f"スクショ失敗: {last_err}")
