# 仕入管理システム（shiire）

中古カメラ等の仕入を月別 Excel 台帳に記録するツールです（Windows 用）。Claude Code が従うルールは [CLAUDE.md](CLAUDE.md) にあります。

## セットアップ（初回だけ）

1. **Python 3.11 以上**を https://www.python.org/ からインストール（インストーラで「Add python.exe to PATH」にチェック）。
2. このフォルダの中身（`CLAUDE.md`・`shiire\`・`requirements.txt`・`cards.example.json`）を
   `D:\desktop\オークション\仕入管理システム\` にコピー。
3. そのフォルダで PowerShell を開き、ライブラリを入れる:
   ```powershell
   cd "D:\desktop\オークション\仕入管理システム"
   py -m pip install -r requirements.txt
   ```
4. `cards.example.json` をコピーして `cards.json` にし、`XXXX` をカード下4けたに書き換える（このファイルは GitHub に上げない）。
5. 台帳を作成（既にあれば何もしない）:
   ```powershell
   py -m shiire init
   ```

## 使い方

作業フォルダで PowerShell を開いて実行します（Claude Code もこのフォルダで起動すれば `CLAUDE.md` を読んで同じコマンドを使います）。

```powershell
# ヤフオク（クーポン1000円使用の例。金額は自動で差し引き、備考に記入）
py -m shiire add --date 2026/7/10 --name "Nikon F3" --price 32000 --coupon 1000 --shipping 1200 `
  --shop "カメラ屋ストア" --payment AMEX --url https://auctions.yahoo.co.jp/jp/auction/x1234567890

# 実店舗（1行入力）
py -m shiire text "キタムラ Nikon F100 仕入38500 送料1000 7/11 PAYPAY"

# 支払い明細のスクショ → 仕入証憑　画像あり\2026年\7月\x1234567890_20260710.png
py -m shiire shot "明細のURL" --id x1234567890 --date 2026/7/10

py -m shiire dashboard          # ダッシュボード再生成
py -m shiire summary --month 7  # 月の仕入合計
py -m shiire mf 仕訳.csv        # MF仕訳帳照合（--ids で取引番号だけ）
```

- 台帳を Excel で開いたままだと編集は中止されます（破損防止）。閉じてから実行してください。
- 編集のたびに `バックアップ\` へ自動バックアップされます。
- スクショは Chrome でヤフオクにログインした状態で実行してください。

## テスト（開発者向け）

```powershell
py -m pip install pytest
py -m pytest
```
