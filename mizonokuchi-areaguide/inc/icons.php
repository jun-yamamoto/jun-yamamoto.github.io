<?php
/**
 * インライン SVG アイコン
 *
 * モックでは lucide の CDN を読み込んでいたが、外部 JS 依存を無くすため
 * 使用しているアイコンのみをインライン SVG として持たせている。
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

/**
 * アイコンのパス定義を返す。
 *
 * @return array<string,string>
 */
function mzk_icon_paths() {
	return array(
		'arrow-right'     => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
		'arrow-up'        => '<path d="m5 12 7-7 7 7"/><path d="M12 19V5"/>',
		'mic'             => '<path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><path d="M12 19v3"/>',
		'map-pin'         => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'building-2'      => '<path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>',
		'graduation-cap'  => '<path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>',
		'shopping-bag'    => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/>',
		'trees'           => '<path d="M10 10v.2A3 3 0 0 1 8.9 16H5a3 3 0 0 1-1-5.8V10a3 3 0 0 1 6 0Z"/><path d="M7 16v6"/><path d="M13 19v3"/><path d="M12 19h8.3a1 1 0 0 0 .7-1.7L18 14h.3a1 1 0 0 0 .7-1.7L16 9h.2a1 1 0 0 0 .8-1.7L13 3l-1.4 1.5"/>',
		'utensils'        => '<path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/>',
		'search'          => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
	);
}

/**
 * インライン SVG アイコンを返す。
 *
 * @param string $name        アイコン名。
 * @param string $class       付与するクラス。
 * @param float  $stroke      線幅。
 * @return string エスケープ済み SVG マークアップ。
 */
function mzk_icon($name, $class = 'icon', $stroke = 1.8) {
	$paths = mzk_icon_paths();
	if (!isset($paths[$name])) {
		return '';
	}

	return sprintf(
		'<svg class="%1$s" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="%2$s" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">%3$s</svg>',
		esc_attr($class),
		esc_attr((string) $stroke),
		$paths[$name] // 内部定義の固定文字列。
	);
}

/**
 * mzk_icon() の echo 版。
 *
 * @param string $name   アイコン名。
 * @param string $class  クラス。
 * @param float  $stroke 線幅。
 * @return void
 */
function mzk_the_icon($name, $class = 'icon', $stroke = 1.8) {
	echo mzk_icon($name, $class, $stroke); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 上でエスケープ済み。
}
