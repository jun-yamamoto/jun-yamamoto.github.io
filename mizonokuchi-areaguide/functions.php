<?php
/**
 * MIZONOKUCHI AREA GUIDE
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

define('MZK_VERSION', '1.0.0');

require_once get_theme_file_path('inc/icons.php');
require_once get_theme_file_path('inc/template-tags.php');
require_once get_theme_file_path('inc/nav-walker.php');
require_once get_theme_file_path('inc/customizer.php');

/* ==================================================================
   セットアップ
================================================================== */

/**
 * テーマサポートとメニューの登録。
 *
 * @return void
 */
function mzk_setup() {
	load_theme_textdomain('mizonokuchi', get_theme_file_path('languages'));

	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_theme_support('automatic-feed-links');
	add_theme_support('responsive-embeds');
	add_theme_support('align-wide');
	add_theme_support(
		'html5',
		array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets')
	);
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 40,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		)
	);

	register_nav_menus(
		array(
			// header.php / footer.php から利用する。モックのページ内アンカーの代わりに
			// このメニューに登録した「記事」へリンクする。
			'headernav' => __('ヘッダーナビ', 'mizonokuchi'),
		)
	);

	add_image_size('mzk-card', 800, 560, true);
	add_image_size('mzk-wide', 1600, 900, true);
}
add_action('after_setup_theme', 'mzk_setup');

/**
 * コンテンツ幅。
 *
 * @return void
 */
function mzk_content_width() {
	$GLOBALS['content_width'] = 1100;
}
add_action('after_setup_theme', 'mzk_content_width', 0);

/* ==================================================================
   アセット
================================================================== */

/**
 * CSS / JS の読み込み。
 *
 * @return void
 */
function mzk_enqueue_assets() {
	// Google Fonts（モックの @import を head での読み込みに置き換え）。
	wp_enqueue_style(
		'mzk-gfonts',
		'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700&family=Shippori+Mincho:wght@400;500;600;700;800&family=Zen+Old+Mincho:wght@400;500;700;900&display=swap',
		array(),
		null
	);

	$css = array(
		'mzk-reset'   => 'css/reset.css',
		'mzk-cmn'     => 'css/cmn.css',
		'mzk-header'  => 'css/header.css',
		'mzk-footer'  => 'css/footer.css',
		'mzk-article' => 'css/article.css',
	);
	$prev = array();
	foreach ($css as $handle => $rel) {
		wp_enqueue_style($handle, get_theme_file_uri($rel), $prev, mzk_asset_ver($rel));
		$prev = array($handle);
	}

	if (is_front_page()) {
		wp_enqueue_style('mzk-front', get_theme_file_uri('css/front.css'), array('mzk-article'), mzk_asset_ver('css/front.css'));
	}

	// 親テーマ/子テーマ構成でも style.css を最後に読み込めるようにしておく。
	wp_enqueue_style('mzk-style', get_stylesheet_uri(), array('mzk-cmn'), mzk_asset_ver('style.css'));

	wp_enqueue_script('mzk-common', get_theme_file_uri('js/common.js'), array(), mzk_asset_ver('js/common.js'), true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'mzk_enqueue_assets');

/**
 * ファイル更新時刻をバージョンに使い、キャッシュ切れを確実にする。
 *
 * @param string $rel テーマルートからの相対パス。
 * @return string
 */
function mzk_asset_ver($rel) {
	$path = get_theme_file_path($rel);
	return file_exists($path) ? (string) filemtime($path) : MZK_VERSION;
}

/**
 * Google Fonts への preconnect を追加する。
 *
 * @param array  $hints    ヒント。
 * @param string $relation 種別。
 * @return array
 */
function mzk_resource_hints($hints, $relation) {
	if ('preconnect' === $relation) {
		$hints[] = array('href' => 'https://fonts.googleapis.com');
		$hints[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $hints;
}
add_filter('wp_resource_hints', 'mzk_resource_hints', 10, 2);

/* ==================================================================
   body クラス（参考 header.php の分岐を踏襲）
================================================================== */

/**
 * body クラスを追加する。
 *
 * @param array $classes クラス配列。
 * @return array
 */
function mzk_body_class($classes) {
	$classes[] = 'itot';

	if (is_home() || is_front_page()) {
		$classes[] = 'home';
		$classes[] = 'index';
	}
	if (is_single()) {
		$classes[] = 'single';
		$cats      = get_the_category();
		if ($cats) {
			$classes[] = 'cat-' . $cats[0]->slug;
		}
	}
	if (is_category()) {
		$classes[] = 'category';
		$term      = get_queried_object();
		if ($term instanceof WP_Term) {
			$classes[] = 'cat-' . $term->slug;
		}
	}
	if (is_page()) {
		$classes[] = 'page';
		$post      = get_queried_object();
		if ($post instanceof WP_Post) {
			$classes[] = 'page-' . $post->post_name;
		}
	}
	if (is_404()) {
		$classes[] = 'missing';
	}
	if (is_archive()) {
		$classes[] = 'archives';
	}
	if (is_search()) {
		$classes[] = 'search';
	}
	if (is_tag()) {
		$classes[] = 'tag';
	}

	return array_values(array_unique($classes));
}
add_filter('body_class', 'mzk_body_class');

/* ==================================================================
   抜粋
================================================================== */

/**
 * 抜粋の文字数。
 *
 * @return int
 */
function mzk_excerpt_length() {
	return 70;
}
add_filter('excerpt_length', 'mzk_excerpt_length');

/**
 * 抜粋の末尾。
 *
 * @return string
 */
function mzk_excerpt_more() {
	return '…';
}
add_filter('excerpt_more', 'mzk_excerpt_more');

/* ==================================================================
   itot 系テーマ関数の互換シム
   （親テーマ / 独自プラグイン側に同名関数がある場合はそちらが優先される）
================================================================== */

if (!function_exists('it_disphtml')) {
	/**
	 * 管理画面で登録した任意 HTML（GA タグ等）を出力する。
	 * 本テーマ単体では、同名のオプションが登録されていれば出力する。
	 *
	 * @param string $key オプションキー。
	 * @return void
	 */
	function it_disphtml($key) {
		$html = get_option($key);
		if ($html) {
			echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 管理者が登録したタグをそのまま出力する。
		}
	}
}

if (!function_exists('contact_link')) {
	/**
	 * お問い合わせリンクを出力する。
	 *
	 * @return void
	 */
	function contact_link() {
		$url = mzk_footer_option('mzk_contact_url');
		if (!$url) {
			$page = get_page_by_path('contact');
			$url  = ($page instanceof WP_Post) ? get_permalink($page) : home_url('/contact/');
		}
		printf('<a href="%1$s">%2$s</a>', esc_url($url), esc_html__('お問い合わせ', 'mizonokuchi'));
	}
}

if (!function_exists('navigation_title')) {
	/**
	 * アーカイブ等のページタイトルを出力する。
	 *
	 * @return void
	 */
	function navigation_title() {
		echo esc_html(wp_strip_all_tags(get_the_archive_title()));
	}
}

if (!function_exists('meta_title')) {
	/**
	 * 記事タイトルを出力する（meta タグ用）。
	 *
	 * @return void
	 */
	function meta_title() {
		echo esc_attr(wp_strip_all_tags(get_the_title()));
	}
}

/* ==================================================================
   その他
================================================================== */

/**
 * 検索結果から固定ページを除外しない等のカスタマイズが不要なら何もしない。
 * ここでは アーカイブタイトルの「カテゴリー:」等の接頭辞だけ落とす。
 *
 * @param string $title タイトル。
 * @return string
 */
function mzk_archive_title($title) {
	if (is_category() || is_tag() || is_tax()) {
		$title = single_term_title('', false);
	} elseif (is_post_type_archive()) {
		$title = post_type_archive_title('', false);
	} elseif (is_author()) {
		$title = get_the_author();
	}
	return $title;
}
add_filter('get_the_archive_title', 'mzk_archive_title');

/**
 * 添付ファイルの alt が空のときに、キャプション/タイトルで補う。
 *
 * @param array $attr 属性。
 * @param WP_Post $attachment 添付。
 * @return array
 */
function mzk_image_alt_fallback($attr, $attachment) {
	if (empty($attr['alt'])) {
		$attr['alt'] = $attachment ? wp_strip_all_tags($attachment->post_title) : '';
	}
	return $attr;
}
add_filter('wp_get_attachment_image_attributes', 'mzk_image_alt_fallback', 10, 2);
