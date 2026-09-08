<?php
/**
 * ヘッダーナビ用のウォーカーとフォールバック
 *
 * モックの「01 · AREA」という見え方を、WordPress のメニュー
 * （外観 > メニュー > 「ヘッダーナビ」）から生成する。
 *
 *   .nav-num … 連番（メニューの並び順から自動採番）
 *   .nav-en  … メニュー項目のラベル
 *   .nav-jp  … メニュー項目の「説明」欄（SP のドロワーで表示）
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

/**
 * ヘッダーナビ用ウォーカー。
 */
class Mizonokuchi_Nav_Walker extends Walker_Nav_Menu {

	/**
	 * 第 1 階層の連番カウンタ。
	 *
	 * @var int
	 */
	protected $top_index = 0;

	/**
	 * サブメニュー開始タグ。
	 *
	 * @param string   $output 出力バッファ。
	 * @param int      $depth  階層。
	 * @param stdClass $args   引数。
	 * @return void
	 */
	public function start_lvl(&$output, $depth = 0, $args = null) {
		$output .= "\n" . str_repeat("\t", $depth) . '<ul class="sub-menu">' . "\n";
	}

	/**
	 * サブメニュー終了タグ。
	 *
	 * @param string   $output 出力バッファ。
	 * @param int      $depth  階層。
	 * @param stdClass $args   引数。
	 * @return void
	 */
	public function end_lvl(&$output, $depth = 0, $args = null) {
		$output .= str_repeat("\t", $depth) . "</ul>\n";
	}

	/**
	 * メニュー項目。
	 *
	 * @param string  $output            出力バッファ。
	 * @param WP_Post $data_object       メニュー項目。
	 * @param int     $depth             階層。
	 * @param stdClass $args             引数。
	 * @param int     $current_object_id 現在の ID。
	 * @return void
	 */
	public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
		$item    = $data_object;
		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = implode(' ', array_filter(array_map('sanitize_html_class', $classes)));
		$output     .= '<li class="' . esc_attr($class_names) . '">';

		$atts = array(
			'href'   => !empty($item->url) ? $item->url : '',
			'target' => !empty($item->target) ? $item->target : '',
			'rel'    => !empty($item->xfn) ? $item->xfn : '',
			'title'  => !empty($item->attr_title) ? $item->attr_title : '',
		);
		if ('_blank' === $atts['target'] && empty($atts['rel'])) {
			$atts['rel'] = 'noopener';
		}

		$attributes = '';
		foreach ($atts as $key => $value) {
			if ('' === $value || false === $value) {
				continue;
			}
			$value       = ('href' === $key) ? esc_url($value) : esc_attr($value);
			$attributes .= ' ' . $key . '="' . $value . '"';
		}

		$title = apply_filters('the_title', $item->title, $item->ID);
		$desc  = trim((string) $item->description);

		$inner = '';
		if (0 === $depth) {
			$this->top_index++;
			$inner .= '<span class="nav-num">' . esc_html(sprintf('%02d', $this->top_index)) . '</span>';
			$inner .= '<span class="nav-sep" aria-hidden="true">·</span>';
		}
		$inner .= '<span class="nav-en">' . esc_html($title) . '</span>';
		if ($desc) {
			$inner .= '<span class="nav-jp">' . esc_html($desc) . '</span>';
		}

		$output .= '<a' . $attributes . '>' . $inner . '</a>';
	}

	/**
	 * メニュー項目の終了タグ。
	 *
	 * @param string  $output      出力バッファ。
	 * @param WP_Post $data_object メニュー項目。
	 * @param int     $depth       階層。
	 * @param stdClass $args       引数。
	 * @return void
	 */
	public function end_el(&$output, $data_object, $depth = 0, $args = null) {
		$output .= "</li>\n";
	}
}

/**
 * メニューが未設定のときのフォールバック。
 *
 * mzk_sections() の定義から「実際の記事（またはカテゴリー）」へのリンクを生成する。
 * ページ内アンカー（#area-column など）は使わない。
 *
 * @param array $args wp_nav_menu() の引数。
 * @return void
 */
function mzk_nav_fallback($args = array()) {
	$i = 0;
	foreach (mzk_sections() as $slug => $section) {
		$url = mzk_link($slug, 'archive');
		if (!$url) {
			continue;
		}
		$i++;
		printf(
			'<li class="menu-item menu-item-%1$s"><a href="%2$s"><span class="nav-num">%3$s</span><span class="nav-sep" aria-hidden="true">·</span><span class="nav-en">%4$s</span><span class="nav-jp">%5$s</span></a></li>',
			esc_attr($slug),
			esc_url($url),
			esc_html(sprintf('%02d', $i)),
			esc_html($section['nav']),
			esc_html($section['jp'])
		);
	}

	if (0 === $i) {
		printf(
			'<li class="menu-item"><a href="%1$s"><span class="nav-en">%2$s</span></a></li>',
			esc_url(admin_url('nav-menus.php')),
			esc_html__('メニューを設定してください', 'mizonokuchi')
		);
	}
}

/**
 * ヘッダーナビを出力する。
 *
 * @return void
 */
function mzk_header_nav() {
	wp_nav_menu(
		array(
			'theme_location' => 'headernav',
			'container'      => false,
			'items_wrap'     => '%3$s',
			'depth'          => 2,
			'walker'         => new Mizonokuchi_Nav_Walker(),
			'fallback_cb'    => 'mzk_nav_fallback',
		)
	);
}

/**
 * フッターナビを出力する。ヘッダーと同じメニューを流用する。
 *
 * @return void
 */
function mzk_footer_nav() {
	wp_nav_menu(
		array(
			'theme_location' => 'headernav',
			'container'      => false,
			'items_wrap'     => '%3$s',
			'depth'          => 1,
			'walker'         => new Mizonokuchi_Nav_Walker(),
			'fallback_cb'    => 'mzk_nav_fallback',
		)
	);
}
