# MIZONOKUCHI AREA GUIDE（WordPress テーマ）

AI が作成したモック `index.html` を WordPress テーマ化したものです。

- ヘッダーナビは**ページ内アンカーではなく、WordPress のメニュー機能**で各記事へリンクします
- ヘッダー／フッターは `https://tokyo.itot.jp/kugayama/`（提供いただいた `header.php` / `footer.php`）と同じ構成にしています
- ヘッダーは PC / SP でレスポンシブ（SP はドロワー）

---

## 1. インストール

`mizonokuchi-areaguide` フォルダをまるごと `wp-content/themes/` に置き、
**外観 > テーマ**で有効化してください。

```
wp-content/themes/mizonokuchi-areaguide/
```

## 2. 最初に設定すること

### 2-1. ヘッダーナビ（必須）

**外観 > メニュー** で新しいメニューを作成し、**メニューの位置「ヘッダーナビ」**にチェックを入れます。

メニュー項目には、投稿・固定ページ・カテゴリーのいずれでも登録できます。
表示は自動で次のようになります。

| 表示 | 元データ |
| --- | --- |
| `01` `02` … の連番 | メニューの並び順から自動採番 |
| `AREA` などの英字ラベル | メニュー項目の **ナビゲーションラベル** |
| `エリアコラム` などの和文（SP のみ表示） | メニュー項目の **説明** 欄 |

> 「説明」欄が出ていない場合は、メニュー編集画面の右上「表示オプション」で
> **説明** にチェックを入れてください。

メニュー未設定でも、下記のスラッグから自動でリンクを生成します（フォールバック）。

`column` / `promotion` / `mazaka` / `gourmet` / `convenience` / `education` / `life-info`

### 2-2. カテゴリー（推奨）

トップページの各セクションは、次のスラッグの **固定ページ → カテゴリー（最新記事）→ 投稿**
の順にリンク先を解決します。該当が無ければモックの文言・画像のまま表示されます。

| セクション | スラッグ |
| --- | --- |
| 01 AREA COLUMN | `column` |
| 02 PROMOTION | `promotion` |
| 03 MAZAKA | `mazaka` |
| 04 GOURMET（親） | `gourmet` |
| └ NEW OPEN | `gourmet-new-open` |
| └ TRADITIONAL | `gourmet-traditional` |
| └ STYLISH | `gourmet-stylish` |
| 05 CONVENIENCE（親） | `convenience` |
| └ カード 01 | `convenience-01` |
| └ カード 02 | `convenience-02` |
| └ マルイファミリー溝口 | `convenience-marui` |
| 06 EDUCATION（親） | `education` |
| └ 特別インタビュー | `education-interview` |
| 07 LIFE INFORMATION（親） | `life-info` |
| └ 公共・医療 | `life-public` |
| └ 子育て・教育 | `life-child` |
| └ ショッピング | `life-shopping` |
| └ 公園・スポーツ | `life-park` |
| └ グルメ | `life-gourmet` |
| 特選物件 | `property` |

子カテゴリーは親カテゴリーの下に作ると、親のアーカイブにも記事が出るのでおすすめです。

記事が見つかった場合は、そのセクションの**見出し・本文（抜粋）・アイキャッチ画像**が
自動で記事のものに差し替わります。

### 2-3. フッター（PR 枠・コピーライト）

**外観 > カスタマイズ > フッター（PR枠・コピーライト）** で設定します。

- PR 枠の見出し／キャッチコピー／物件名／説明文／画像／リンク先 URL／ボタン文言
- コピーライト表記
- お問い合わせ・プライバシーポリシー・運営会社・itot の各 URL
- itot ロゴ画像

物件名とリンク先 URL の**両方が空**のときは PR 枠ごと非表示になります。

#### itot ロゴの差し替え

コピーライトバーは黒背景なので、**背景が透明・白のロゴ**を使います。差し替え方法は 2 通りです。

1. `img/footlogo-white.png` としてテーマ内に置く（推奨）
2. 「外観 > カスタマイズ > フッター > itot ロゴ画像」でメディアライブラリから指定する

優先順は **カスタマイザー → `img/footlogo-white.png` → `img/footlogo-white.svg`（仮）** です。
表示高さは CSS で 16px 固定・幅は自動なので、縦横比は問いません。
高解像度ディスプレイでぼやけないよう、**高さ 32px 以上**の画像を用意してください。

### 2-4. トップページ

**外観 > カスタマイズ > トップページ**

- Movie Gallery の YouTube 動画 ID（2 本）
- エリアマップ画像（未設定ならダミーの SVG マップを表示）

### 2-5. ロゴ

**外観 > カスタマイズ > サイト基本情報 > ロゴ** で画像を設定できます。
未設定の場合はモックの SVG ロゴ＋サイト名／キャッチフレーズを表示します。

---

## 3. ファイル構成

```
mizonokuchi-areaguide/
├── style.css              テーマヘッダ（実スタイルは css/ 配下）
├── functions.php          テーマ設定・アセット読み込み・itot 関数の互換シム
├── header.php             ヘッダー（ドロワー + wp_nav_menu）
├── footer.php             フッター（itot 共通フッター構成）
├── front-page.php         トップページ
├── index.php / archive.php / search.php / single.php / page.php / 404.php
├── comments.php / searchform.php
├── inc/
│   ├── icons.php          インライン SVG アイコン（lucide 相当）
│   ├── template-tags.php  セクション定義・リンク解決・画像ヘルパー
│   ├── nav-walker.php     ヘッダーナビ用ウォーカー＋フォールバック
│   └── customizer.php     カスタマイザー設定
├── template-parts/
│   ├── content-card.php   一覧用カード
│   └── front/             トップページの各セクション
├── css/                   reset / cmn / header / footer / front / article
├── js/common.js           ヒーロー切替・スクロールバー・ドロワー・pTop
├── img/                   noimage.svg / footlogo-white.svg
└── image/                 セクション画像
```

---

## 4. モックからの変更点

| モック | テーマ |
| --- | --- |
| Tailwind CDN（`cdn.tailwindcss.com`） | 素の CSS に置き換え（本番非推奨の CDN を排除） |
| lucide CDN（アイコン JS） | インライン SVG（`inc/icons.php`） |
| `@import` での Google Fonts | `wp_enqueue_style` + `preconnect` |
| ヘッダーナビ＝ページ内アンカー | `wp_nav_menu`（メニュー位置「ヘッダーナビ」）で各記事へ |
| フッター＝独自のコピーライト表記 | itot 共通フッター（PR 枠＋コピーライトバー＋pTop） |
| SP でナビが横スクロール | 1279px 以下はドロワー（チェックボックス方式、JS なしでも開閉可）／1280px 以上は横並び |
| YouTube 埋め込み | `youtube-nocookie.com` + `loading="lazy"` |
| セクションの文言・画像が固定 | 記事があれば記事のタイトル／抜粋／アイキャッチに差し替え |

---

## 5. 差し替えが必要なアセット

| ファイル | 内容 |
| --- | --- |
| `img/footlogo-white.svg` | itot ロゴの仮版。`img/footlogo-white.png` を置くか、カスタマイザーで指定すると差し替わります |
| `image/GOURMET/**` | 添付 zip に写真が無かったため空です（`image/GOURMET/README.txt` 参照） |
| `image/pr/01_dummy.jpg`, `image/MAZAKA/01_dummy.jpg` | ダミー画像 |
| `image/map/area-map.jpg` | エリアマップ（未設置ならダミー SVG を表示） |

画像ファイルが無い場合は自動で `img/noimage.svg` に差し替わるため、
レイアウトが崩れたり画像リンク切れになったりはしません。

---

## 6. itot 親テーマ環境で使う場合

`it_disphtml()` / `contact_link()` / `navigation_title()` / `meta_title()` は
`function_exists()` で囲んだ互換シムとして `functions.php` に入れています。
親テーマやプラグイン側に同名関数があればそちらが優先されるため、
このテーマ単体でも、itot 環境に載せても動作します。

`header.php` 冒頭にあった `get_template_part('nav', 'location')` と
`css/kickstart.css` などの itot 固有の読み込みは、単体で動かすために外してあります。
必要であれば `header.php` / `functions.php` に戻してください。
