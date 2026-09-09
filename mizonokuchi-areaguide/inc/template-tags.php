<?php
/**
 * テンプレートから使うヘルパー関数
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

/* ==================================================================
   セクション定義
================================================================== */

/**
 * トップページの章立て定義。
 *
 * key は「カテゴリー / 固定ページのスラッグ」として解決に使われる。
 *
 * @return array<string,array<string,string>>
 */
function mzk_sections() {
	$sections = array(
		'column' => array(
			'no'    => '01',
			'ghost' => 'Column',
			'en'    => 'AREA COLUMN',
			'jp'    => 'エリアコラム',
			'nav'   => 'AREA',
		),
		'promotion' => array(
			'no'    => '02',
			'ghost' => 'Promotion',
			'en'    => 'PROMOTION',
			'jp'    => '末長組',
			'nav'   => 'PROMOTION',
		),
		'mazaka' => array(
			'no'    => '03',
			'ghost' => 'Mazaka',
			'en'    => 'NEW FACILITY',
			'jp'    => '新設施設',
			'nav'   => 'MAZAKA',
		),
		'gourmet' => array(
			'no'    => '04',
			'ghost' => 'Gourmet',
			'en'    => 'GOURMET',
			'jp'    => 'グルメ',
			'nav'   => 'GOURMET',
		),
		'convenience' => array(
			'no'    => '05',
			'ghost' => 'Convenience',
			'en'    => 'CONVENIENCE',
			'jp'    => '住環境',
			'nav'   => 'CONVENIENCE',
		),
		'education' => array(
			'no'    => '06',
			'ghost' => 'Education',
			'en'    => 'EDUCATION',
			'jp'    => '子育て・教育',
			'nav'   => 'EDUCATION',
		),
		'life-info' => array(
			'no'    => '07',
			'ghost' => 'Information',
			'en'    => 'LIFE INFORMATION',
			'jp'    => '生活情報',
			'nav'   => 'LIFE',
		),
	);

	/**
	 * 章立てを変更するためのフィルター。
	 *
	 * @param array $sections
	 */
	return apply_filters('mzk_sections', $sections);
}

/**
 * 章ヘッダー（大英字＋メタ情報）を出力する。
 *
 * @param string $slug     mzk_sections() のキー。
 * @param string $modifier '' | 'on-dark' | 'on-gold'。
 * @return void
 */
function mzk_chapter_head($slug, $modifier = '') {
	$sections = mzk_sections();
	if (!isset($sections[$slug])) {
		return;
	}
	$s   = $sections[$slug];
	$mod = $modifier ? ' ' . $modifier : '';
	?>
	<div class="chapter-head<?php echo esc_attr($mod); ?>">
		<h2 class="chapter-title<?php echo esc_attr($mod); ?>"><?php echo esc_html($s['ghost']); ?></h2>
		<div class="chapter-meta">
			<span class="num">N°<?php echo esc_html($s['no']); ?></span>
			<span class="sep">/</span>
			<span><?php echo esc_html($s['en']); ?></span>
			<span class="sep">/</span>
			<span class="jp"><?php echo esc_html($s['jp']); ?></span>
		</div>
	</div>
	<?php
}

/* ==================================================================
   リンク解決
   ─ セクションのボタンやヘッダーナビのリンク先を「実際の記事」に向ける
================================================================== */

/**
 * スラッグから記事 / ページ / カテゴリーを解決する。
 *
 * 優先順:
 *   1. 同名スラッグの固定ページ
 *   2. 同名スラッグのカテゴリー（$prefer='post' なら最新記事、'archive' ならアーカイブ）
 *   3. 同名スラッグの投稿
 *   4. フォールバック URL
 *
 * @param string $slug     スラッグ。
 * @param string $prefer   'post' | 'archive'。
 * @param string $fallback 見つからない場合の URL。
 * @return string URL。
 */
function mzk_link($slug, $prefer = 'post', $fallback = '') {
	static $cache = array();

	$key = $slug . '|' . $prefer;
	if (isset($cache[$key])) {
		return $cache[$key] ? $cache[$key] : $fallback;
	}

	$url = '';

	// 1. 固定ページ
	$page = get_page_by_path($slug);
	if ($page instanceof WP_Post && 'publish' === $page->post_status) {
		$url = get_permalink($page);
	}

	// 2. カテゴリー
	if (!$url) {
		$term = get_term_by('slug', $slug, 'category');
		if ($term && !is_wp_error($term)) {
			if ('archive' === $prefer) {
				$url = get_category_link($term);
			} else {
				$post = mzk_section_post($slug);
				$url  = $post ? get_permalink($post) : get_category_link($term);
			}
		}
	}

	// 3. 投稿
	if (!$url) {
		$post = get_page_by_path($slug, OBJECT, 'post');
		if ($post instanceof WP_Post && 'publish' === $post->post_status) {
			$url = get_permalink($post);
		}
	}

	$cache[$key] = $url;

	/**
	 * 解決結果を上書きするためのフィルター。
	 *
	 * @param string $url
	 * @param string $slug
	 * @param string $prefer
	 */
	$url = apply_filters('mzk_link', $url, $slug, $prefer);

	return $url ? $url : $fallback;
}

/**
 * mzk_link() の echo 版（エスケープ込み）。
 *
 * @param string $slug
 * @param string $prefer
 * @param string $fallback
 * @return void
 */
function mzk_the_link($slug, $prefer = 'post', $fallback = '') {
	echo esc_url(mzk_link($slug, $prefer, $fallback ? $fallback : home_url('/')));
}

/**
 * カテゴリースラッグの最新記事を 1 件返す。
 *
 * @param string $slug カテゴリースラッグ。
 * @return WP_Post|null
 */
function mzk_section_post($slug) {
	static $cache = array();
	if (array_key_exists($slug, $cache)) {
		return $cache[$slug];
	}

	$term = get_term_by('slug', $slug, 'category');
	if (!$term || is_wp_error($term)) {
		return $cache[$slug] = null;
	}

	$posts = get_posts(
		array(
			'category'            => $term->term_id,
			'numberposts'         => 1,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		)
	);

	return $cache[$slug] = $posts ? $posts[0] : null;
}

/**
 * セクションの見出しを返す。記事があればそのタイトル、無ければモックの文言。
 *
 * @param string $slug     カテゴリースラッグ。
 * @param string $fallback 記事が無い場合の文言。
 * @return string
 */
function mzk_section_title($slug, $fallback) {
	$post = mzk_section_post($slug);
	return $post ? get_the_title($post) : $fallback;
}

/**
 * セクションの本文を返す。記事があれば抜粋、無ければモックの文言。
 *
 * @param string $slug     カテゴリースラッグ。
 * @param string $fallback 記事が無い場合の文言。
 * @param int    $length   抜粋の文字数。
 * @return string
 */
function mzk_section_excerpt($slug, $fallback, $length = 100) {
	$post = mzk_section_post($slug);
	if (!$post) {
		return $fallback;
	}
	$text = $post->post_excerpt ? $post->post_excerpt : wp_strip_all_tags($post->post_content);
	$text = trim(preg_replace('/\s+/u', ' ', $text));
	if ('' === $text) {
		return $fallback;
	}
	return mb_strimwidth($text, 0, $length * 2, '…', 'UTF-8');
}

/* ==================================================================
   画像
================================================================== */

/**
 * テーマ内画像の URL を返す。ファイルが無い場合はプレースホルダーを返す。
 *
 * @param string $relative テーマルートからの相対パス（例: image/top/higashiguchi3.JPG）。
 * @return string URL。
 */
function mzk_img($relative) {
	$relative = ltrim($relative, '/');
	$path     = trailingslashit(get_theme_file_path()) . $relative;

	if (file_exists($path)) {
		return get_theme_file_uri($relative);
	}

	return mzk_placeholder_uri();
}

/**
 * 画像が無いときに使うプレースホルダー画像の URL。
 *
 * データ URI は esc_url() で落とされるため、テーマ同梱の SVG ファイルを返す。
 *
 * @return string
 */
function mzk_placeholder_uri() {
	return get_theme_file_uri('img/noimage.svg');
}

/**
 * 投稿のアイキャッチ URL。無ければテーマ内画像 → プレースホルダー。
 *
 * @param WP_Post|null $post     投稿。
 * @param string       $fallback テーマ内画像の相対パス。
 * @param string       $size     画像サイズ。
 * @return string
 */
function mzk_post_thumb($post, $fallback, $size = 'large') {
	if ($post instanceof WP_Post && has_post_thumbnail($post)) {
		$url = get_the_post_thumbnail_url($post, $size);
		if ($url) {
			return $url;
		}
	}
	return mzk_img($fallback);
}

/**
 * フッターの itot ロゴ URL を返す。
 *
 * 優先順:
 *   1. カスタマイザー「itot ロゴ画像」
 *   2. テーマ内の img/footlogo-white.png（正式なロゴを置く場所）
 *   3. テーマ内の img/footlogo-white.svg（仮のロゴ）
 *
 * @return string
 */
function mzk_itot_logo_uri() {
	$custom = (string) get_theme_mod('mzk_itot_logo', '');
	if ($custom) {
		return $custom;
	}

	foreach (array('img/footlogo-white.png', 'img/footlogo-white.svg') as $rel) {
		if (file_exists(get_theme_file_path($rel))) {
			return get_theme_file_uri($rel);
		}
	}

	return '';
}

/**
 * バナー画像の <img> を出力する。
 *
 * 優先順:
 *   1. カスタマイザーで指定したメディア（添付ファイル ID。srcset 付きで出力）
 *   2. テーマ内の画像ファイル（$files の順に探す）
 *   3. プレースホルダー（img/noimage.svg）
 *
 * @param string   $mod_key カスタマイザーの設定キー。
 * @param string[] $files   テーマルートからの相対パス（探す順）。
 * @param string   $alt     代替テキスト。
 * @param int      $width   表示幅の上限（px）。sizes 属性に使う。
 * @return void
 */
function mzk_the_banner_img($mod_key, $files, $alt, $width = 1200) {
	$attachment_id = (int) get_theme_mod($mod_key, 0);
	if ($attachment_id && wp_attachment_is_image($attachment_id)) {
		echo wp_get_attachment_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WP がエスケープ済み。
			$attachment_id,
			'full',
			false,
			array(
				'alt'      => $alt,
				'sizes'    => sprintf('(max-width: %1$dpx) 100vw, %1$dpx', $width),
				'loading'  => 'lazy',
				'decoding' => 'async',
			)
		);
		return;
	}

	foreach ($files as $rel) {
		$path = get_theme_file_path($rel);
		if (!file_exists($path)) {
			continue;
		}
		$size = @getimagesize($path); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- 破損ファイルでも落とさない。
		printf(
			'<img src="%1$s" alt="%2$s"%3$s loading="lazy" decoding="async" />',
			esc_url(get_theme_file_uri($rel)),
			esc_attr($alt),
			($size && $size[0] && $size[1]) ? sprintf(' width="%d" height="%d"', $size[0], $size[1]) : ''
		);
		return;
	}

	printf(
		'<img src="%1$s" alt="%2$s" width="800" height="560" loading="lazy" decoding="async" />',
		esc_url(mzk_placeholder_uri()),
		esc_attr($alt)
	);
}

/* ==================================================================
   フッターの PR 枠
================================================================== */

/**
 * inc/footer-pr.php を読み込み、正規化して返す。
 *
 * @return array{heading:string, items:array<int,array<string,string>>}
 */
function mzk_footer_pr() {
	static $cache = null;
	if (null !== $cache) {
		return $cache;
	}

	$data = array(
		'heading' => '',
		'items'   => array(),
	);

	$file = get_theme_file_path('inc/footer-pr.php');
	if (file_exists($file)) {
		$loaded = require $file;
		if (is_array($loaded)) {
			$data['heading'] = isset($loaded['heading']) ? (string) $loaded['heading'] : '';
			$data['items']   = (isset($loaded['items']) && is_array($loaded['items'])) ? $loaded['items'] : array();
		}
	}

	$items = array();
	foreach ($data['items'] as $item) {
		if (!is_array($item)) {
			continue;
		}
		$item = wp_parse_args(
			$item,
			array(
				'sub_title' => '',
				'title'     => '',
				'text'      => '',
				'url'       => '',
				'image'     => '',
				'btn'       => 'MORE',
				'target'    => '_blank',
			)
		);

		// 物件名が空の項目は書きかけとみなして出さない。
		if ('' === trim((string) $item['title'])) {
			continue;
		}

		$item['image']  = mzk_resolve_image_src((string) $item['image']);
		$item['target'] = ('_blank' === $item['target']) ? '_blank' : '';
		$items[]        = $item;
	}

	$data['items'] = $items;

	/**
	 * PR 枠の内容を差し替えるためのフィルター。
	 *
	 * @param array $data
	 */
	$cache = apply_filters('mzk_footer_pr', $data);

	return $cache;
}

/**
 * 画像指定を URL に解決する。
 *
 * 絶対 URL はそのまま、それ以外はテーマ内の相対パスとして扱う。
 * 空・ファイルなしの場合はプレースホルダーを返す。
 *
 * @param string $src 画像の指定。
 * @return string URL。
 */
function mzk_resolve_image_src($src) {
	$src = trim((string) $src);
	if ('' === $src) {
		return mzk_placeholder_uri();
	}
	if (preg_match('#^(https?:)?//#i', $src)) {
		return $src;
	}
	return mzk_img($src);
}

/* ==================================================================
   ロゴ
================================================================== */

/**
 * サイトロゴ（カスタムロゴ画像があればそれ、無ければモックの SVG）。
 *
 * @param int $size SVG のサイズ。
 * @return void
 */
function mzk_the_logo_mark($size = 34) {
	if (has_custom_logo()) {
		$id  = get_theme_mod('custom_logo');
		$img = wp_get_attachment_image(
			$id,
			'full',
			false,
			array(
				'alt'   => get_bloginfo('name', 'display'),
				'id'    => 'top_logo',
				'class' => 'custom-logo',
			)
		);
		if ($img) {
			echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- WP がエスケープ済み。
			return;
		}
	}
	?>
	<svg width="<?php echo esc_attr($size); ?>" height="<?php echo esc_attr($size); ?>" viewBox="0 0 40 40" aria-hidden="true" focusable="false">
		<rect x="1" y="1" width="38" height="38" fill="none" stroke="#c9a24c" stroke-width="1"/>
		<line x1="6" y1="14" x2="34" y2="14" stroke="#c9a24c" stroke-width="1"/>
		<line x1="6" y1="20" x2="34" y2="20" stroke="#f4efe4" stroke-width="1.6"/>
		<line x1="6" y1="26" x2="34" y2="26" stroke="#c9a24c" stroke-width="1"/>
		<path d="M11 30 L11 10 L20 22 L29 10 L29 30" fill="none" stroke="#f4efe4" stroke-width="1.8" stroke-linejoin="round"/>
	</svg>
	<?php
}

/* ==================================================================
   ページャ / 投稿メタ
================================================================== */

/**
 * ページネーションを出力する。
 *
 * @return void
 */
function mzk_pagination() {
	$links = paginate_links(
		array(
			'mid_size'  => 1,
			'prev_text' => 'PREV',
			'next_text' => 'NEXT',
			'type'      => 'list',
		)
	);
	if (!$links) {
		return;
	}
	echo '<nav class="pagination" aria-label="' . esc_attr__('ページ送り', 'mizonokuchi') . '"><div class="nav-links">';
	echo wp_kses_post(str_replace(array('<ul class=\'page-numbers\'>', '</ul>', '<li>', '</li>'), '', $links));
	echo '</div></nav>';
}

/**
 * 投稿の日付とカテゴリーを出力する。
 *
 * @return void
 */
function mzk_entry_meta() {
	?>
	<div class="meta">
		<time datetime="<?php echo esc_attr(get_the_date('c')); ?>"><?php echo esc_html(get_the_date('Y.m.d')); ?></time>
		<?php
		$cats = get_the_category();
		if ($cats) :
			?>
			<span>/</span>
			<span><?php echo esc_html($cats[0]->name); ?></span>
		<?php endif; ?>
	</div>
	<?php
}
