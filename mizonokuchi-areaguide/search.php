<?php
/**
 * 検索結果
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

get_header();
?>

<div class="page-hero">
	<p class="eyebrow">SEARCH</p>
	<h1>
		<?php
		/* translators: %s: 検索キーワード */
		printf(esc_html__('「%s」の検索結果', 'mizonokuchi'), esc_html(get_search_query()));
		?>
	</h1>
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
			<p class="no-posts"><?php esc_html_e('該当する記事が見つかりませんでした。', 'mizonokuchi'); ?></p>
			<?php get_search_form(); ?>
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
