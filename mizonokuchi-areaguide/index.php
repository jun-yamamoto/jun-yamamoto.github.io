<?php
/**
 * 汎用一覧（ブログトップ / フォールバック）
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

get_header();
?>

<div class="page-hero">
	<p class="eyebrow">MIZONOKUCHI AREA GUIDE</p>
	<h1><?php echo esc_html(is_home() && get_option('page_for_posts') ? get_the_title(get_option('page_for_posts')) : __('記事一覧', 'mizonokuchi')); ?></h1>
</div>

<div class="content-area">
	<div class="inner inner--1200">
		<?php if (have_posts()) : ?>
			<div class="entry-list">
				<?php
				while (have_posts()) :
					the_post();
					get_template_part('template-parts/content', 'card');
				endwhile;
				?>
			</div>
			<?php mzk_pagination(); ?>
		<?php else : ?>
			<p class="no-posts"><?php esc_html_e('記事がまだありません。', 'mizonokuchi'); ?></p>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
