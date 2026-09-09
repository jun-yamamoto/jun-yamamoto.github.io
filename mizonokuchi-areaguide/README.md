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

### 2-3. フッターの PR 枠

PR 枠の中身は **`inc/footer-pr.php` を直接書き換え**て設定します。
件数の増減に対応していて、書いた数だけ自動で並びます。

```php
return array(
	'heading' => 'PR：新築分譲マンションをご紹介',   // 枠全体の見出し

	'items' => array(
		array(
			'sub_title' => '久我山に、森の低層邸宅を',      // 小さなキャッチコピー
			'title'     => 'バウス久我山',                  // 物件名（必須）
			'text'      => '京王井の頭線「久我山」駅徒歩8分', // 説明文
			'url'       => 'https://example.com/',          // リンク先
			'image'     => 'image/pr/01.jpg',               // 画像
			'btn'       => 'MORE',                          // ボタン文言
			'target'    => '_blank',                        // 別タブで開く
		),
		// ↓ 増やすときは array( ... ) をコピーして貼り付け
	),
);
```

| キー | 内容 |
| --- | --- |
| `title` | 物件名。**空の項目は表示されません**（書きかけ扱い） |
| `sub_title` / `text` / `btn` | 省略可。空にするとその行ごと出ません |
| `url` | 省略するとリンクなしで画像とテキストだけ表示します |
| `image` | `https://` で始まる URL でも、テーマ内の相対パス（`image/pr/01.jpg`）でも可。省略・ファイルなしなら NO IMAGE |
| `target` | `_blank` で別タブ。それ以外は同じタブ |

`items` を空の `array()` にすると、見出しごと PR 枠が非表示になります。

**レイアウトは画像とテキストが左右交互に並びます。**

- PC（768px 以上）… 1 件目は画像左・テキスト右、2 件目は画像右・テキスト左、
  3 件目はまた画像左…と交互（参考サイトと同じ）
- スマホ（768px 未満）… 画像が上、テキストが下の縦 1 列

交互の並びは CSS の `:nth-child(even)` で自動判定するので、件数を増減しても
書き換えは不要です。

HTML は書けません。`<` `>` `&` などはそのまま文字として表示されるので、
タグの閉じ忘れでレイアウトが崩れる心配はありません。

### 2-3-2. コピーライト行

**外観 > カスタマイズ > フッター** で設定します。

- コピーライト表記
- お問い合わせ・プライバシーポリシー・運営会社・itot の各 URL
- itot ロゴ画像

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
- EDUCATION バナー画像
- エリアマップ画像（未設定ならダミーの SVG マップを表示）

#### EDUCATION バナー

06 EDUCATION は、**バナー画像 1 枚を記事へのリンクにするだけ**の構成です
（ホバーでの拡大などの演出は付けていません）。表示幅は 1200px で、
それ以下の画面では画面幅に合わせて縮みます。

画像の指定方法は 2 通りで、優先順は次のとおりです。

1. 「外観 > カスタマイズ > トップページ > EDUCATION バナー画像」
   （メディアライブラリから指定。`srcset` が付くので Retina 表示に有利）
2. テーマ内の `image/EDUCATION/banner.png`（`.jpg` / `.webp` も可）

どちらも未設定の場合は `img/noimage.svg` が表示されます。
元画像は表示幅の 2 倍（幅 2400px）を推奨します。

リンク先は他のセクションと同じく `education-interview` → `education` の
順に解決されます。

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
│   ├── footer-pr.php      ★フッター PR 枠の内容（ここを手で書き換える）
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
| 06 EDUCATION は写真＋カード重ねの構成 | バナー画像 1 枚のリンク（表示幅 1200px、演出なし） |
| 04 GOURMET は 3 ブロックそれぞれに VIEW MORE | ブロックの個別ボタンは廃止し、末尾にコーナー全体への VIEW MORE を 1 つ |
| フッター PR は 1 件固定（カスタマイザー） | `inc/footer-pr.php` に手書き。件数自由、画像とテキストが左右交互 |

---

## 5. 差し替えが必要なアセット

| ファイル | 内容 |
| --- | --- |
| `img/footlogo-white.svg` | itot ロゴの仮版。`img/footlogo-white.png` を置くか、カスタマイザーで指定すると差し替わります |
| `image/GOURMET/**` | 添付 zip に写真が無かったため空です（`image/GOURMET/README.txt` 参照） |
| `image/pr/01_dummy.jpg`, `image/MAZAKA/01_dummy.jpg` | ダミー画像 |
| `image/map/area-map.jpg` | エリアマップ（未設置ならダミー SVG を表示） |
| `image/EDUCATION/banner.png` | EDUCATION のバナー（未設置なら NO IMAGE を表示） |

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
