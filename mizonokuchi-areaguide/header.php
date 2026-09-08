<?php
/**
 * ヘッダー
 *
 * 参考: https://tokyo.itot.jp/kugayama/ の header.php
 *   - チェックボックス方式のドロワー（#menu-navibtn / #navibtn / #navi / #menu）
 *   - <ul id="menu"> の中身は wp_nav_menu( theme_location => 'headernav' ) で生成
 *   - モックのページ内アンカー（#area-column 等）は使わず、メニューに登録した
 *     記事・カテゴリーへのリンクになる
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<?php if (get_option('blogkeyword')) : ?>
		<meta name="keywords" content="<?php echo esc_attr(get_option('blogkeyword')); ?>">
	<?php endif; ?>
	<?php it_disphtml('google_webmaster'); ?>
	<link rel="pingback" href="<?php echo esc_url(get_bloginfo('pingback_url')); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#contents"><?php esc_html_e('本文へスキップ', 'mizonokuchi'); ?></a>
<a name="pTop" id="pTop"></a>
<div id="scrollbar" aria-hidden="true"></div>

<header class="site-header">
	<input type="checkbox" id="menu-navibtn" class="menu-navibtn" aria-hidden="true" tabindex="-1">
	<nav id="navi" class="navi" role="navigation" aria-label="<?php esc_attr_e('メインメニュー', 'mizonokuchi'); ?>">
		<label class="navi-overlay" for="menu-navibtn" aria-hidden="true"></label>

		<div class="navi-inner">
			<div class="menu-left">
				<a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home" class="site-logo hover">
					<?php mzk_the_logo_mark(34); ?>
					<?php if (!has_custom_logo()) : ?>
						<span class="site-logo-txt">
							<span class="site-logo-en"><?php echo esc_html(get_bloginfo('name', 'display')); ?></span>
							<?php if (get_bloginfo('description', 'display')) : ?>
								<span class="site-logo-jp"><?php echo esc_html(get_bloginfo('description', 'display')); ?></span>
							<?php endif; ?>
						</span>
					<?php endif; ?>
				</a>
			</div>

			<label id="navibtn" class="navibtn" for="menu-navibtn" aria-expanded="false" aria-controls="menu">
				<span><span><?php esc_html_e('メニューを開く', 'mizonokuchi'); ?></span></span>
			</label>

			<div class="menu-wrap">
				<ul id="menu" class="menu">
					<?php mzk_header_nav(); ?>
				</ul>
				<div class="navi-meta">
					<span><?php echo esc_html(apply_filters('mzk_edition_label', 'EDITION ' . wp_date('Y'))); ?></span>
					<span class="bar" aria-hidden="true"></span>
					<span><?php echo esc_html(apply_filters('mzk_volume_label', 'VOL.01')); ?></span>
				</div>
			</div>
		</div>
	</nav>
</header>

<div id="contents" class="site-contents<?php echo is_front_page() ? ' is-front' : ''; ?>">
