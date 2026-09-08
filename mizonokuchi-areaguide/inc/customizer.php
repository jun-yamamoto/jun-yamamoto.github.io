<?php
/**
 * カスタマイザー設定
 *
 * フッターの PR 枠とコピーライトまわり（itot 共通フッター相当）を
 * 管理画面から編集できるようにする。
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

/**
 * フッター設定の初期値。
 *
 * @return array<string,string>
 */
function mzk_footer_defaults() {
	return array(
		'mzk_pr_heading'   => 'PR：新築分譲マンションをご紹介',
		'mzk_pr_image'     => '',
		'mzk_pr_sub_title' => '溝の口に、新しい暮らしの起点を',
		'mzk_pr_title'     => '（物件名を設定してください）',
		'mzk_pr_text'      => '東急田園都市線・大井町線「溝の口」駅／JR南武線「武蔵溝ノ口」駅',
		'mzk_pr_url'       => '',
		'mzk_pr_btn_label' => 'MORE',
		'mzk_copyright'    => 'Copyright &copy; COCOLOMACHI Inc. All rights reserved.',
		'mzk_contact_url'  => '',
		'mzk_privacy_url'  => 'http://itot.jp/pp.html',
		'mzk_company_url'  => 'http://cocolomachi.co.jp/',
		'mzk_itot_url'     => 'http://itot.jp/',
	);
}

/**
 * フッター設定値を取得する。
 *
 * @param string $key 設定キー。
 * @return string
 */
function mzk_footer_option($key) {
	$defaults = mzk_footer_defaults();
	$default  = isset($defaults[$key]) ? $defaults[$key] : '';
	return (string) get_theme_mod($key, $default);
}

/**
 * カスタマイザーに項目を登録する。
 *
 * @param WP_Customize_Manager $wp_customize カスタマイザー。
 * @return void
 */
function mzk_customize_register($wp_customize) {
	$defaults = mzk_footer_defaults();

	$wp_customize->add_section(
		'mzk_footer',
		array(
			'title'       => __('フッター（PR枠・コピーライト）', 'mizonokuchi'),
			'priority'    => 130,
			'description' => __('itot 共通フッターの PR 枠とコピーライト行を設定します。「物件名」と「リンク先 URL」の両方が空の場合、PR 枠は表示されません。', 'mizonokuchi'),
		)
	);

	$fields = array(
		'mzk_pr_heading'   => array(__('PR 枠の見出し', 'mizonokuchi'), 'text'),
		'mzk_pr_sub_title' => array(__('PR 枠のキャッチコピー', 'mizonokuchi'), 'text'),
		'mzk_pr_title'     => array(__('PR 枠の物件名', 'mizonokuchi'), 'text'),
		'mzk_pr_text'      => array(__('PR 枠の説明文', 'mizonokuchi'), 'textarea'),
		'mzk_pr_url'       => array(__('PR 枠のリンク先 URL', 'mizonokuchi'), 'url'),
		'mzk_pr_btn_label' => array(__('PR 枠のボタン文言', 'mizonokuchi'), 'text'),
		'mzk_copyright'    => array(__('コピーライト表記', 'mizonokuchi'), 'text'),
		'mzk_contact_url'  => array(__('お問い合わせ URL', 'mizonokuchi'), 'url'),
		'mzk_privacy_url'  => array(__('プライバシーポリシー URL', 'mizonokuchi'), 'url'),
		'mzk_company_url'  => array(__('運営会社 URL', 'mizonokuchi'), 'url'),
		'mzk_itot_url'     => array(__('itot サイト URL', 'mizonokuchi'), 'url'),
	);

	foreach ($fields as $key => $conf) {
		list($label, $type) = $conf;

		$wp_customize->add_setting(
			$key,
			array(
				'default'           => isset($defaults[$key]) ? $defaults[$key] : '',
				'sanitize_callback' => ('url' === $type) ? 'esc_url_raw' : (('textarea' === $type) ? 'sanitize_textarea_field' : 'wp_kses_post'),
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $label,
				'section' => 'mzk_footer',
				'type'    => ('textarea' === $type) ? 'textarea' : (('url' === $type) ? 'url' : 'text'),
			)
		);
	}

	// PR 画像。
	$wp_customize->add_setting(
		'mzk_pr_image',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'mzk_pr_image',
			array(
				'label'   => __('PR 枠の画像', 'mizonokuchi'),
				'section' => 'mzk_footer',
			)
		)
	);

	// フッターの itot ロゴ。
	$wp_customize->add_setting(
		'mzk_itot_logo',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'mzk_itot_logo',
			array(
				'label'       => __('itot ロゴ画像（フッター）', 'mizonokuchi'),
				'description' => __('黒背景に載るため、背景が透明で白のロゴを指定してください。未設定の場合はテーマ内の img/footlogo-white.png → img/footlogo-white.svg の順に使われます。', 'mizonokuchi'),
				'section'     => 'mzk_footer',
			)
		)
	);

	// トップページ：動画。
	$wp_customize->add_section(
		'mzk_front',
		array(
			'title'       => __('トップページ', 'mizonokuchi'),
			'priority'    => 131,
			'description' => __('トップページの Movie Gallery で使う YouTube 動画 ID を設定します。', 'mizonokuchi'),
		)
	);
	// エリアマップ画像（未設定ならテーマ内のダミー SVG を表示）。
	$wp_customize->add_setting(
		'mzk_area_map',
		array(
			'default'           => '',
			'sanitize_callback' => 'esc_url_raw',
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'mzk_area_map',
			array(
				'label'       => __('エリアマップ画像', 'mizonokuchi'),
				'description' => __('未設定の場合はダミーのマップが表示されます。', 'mizonokuchi'),
				'section'     => 'mzk_front',
			)
		)
	);

	$movies = array(
		'mzk_movie_1' => array(__('動画1の YouTube ID', 'mizonokuchi'), '33GNNfem_Es'),
		'mzk_movie_2' => array(__('動画2の YouTube ID', 'mizonokuchi'), 'oczZEFMkOYw'),
	);
	foreach ($movies as $key => $conf) {
		$wp_customize->add_setting(
			$key,
			array(
				'default'           => $conf[1],
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		$wp_customize->add_control(
			$key,
			array(
				'label'   => $conf[0],
				'section' => 'mzk_front',
				'type'    => 'text',
			)
		);
	}
}
add_action('customize_register', 'mzk_customize_register');
