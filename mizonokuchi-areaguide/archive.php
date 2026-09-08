<?php
/**
 * アーカイブ（カテゴリー / タグ / 日付など）
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

get_header();
?>

<div class="page-hero">
	<p class="eyebrow">
		<?php
		if (is_category()) {
			echo 'CATEGORY';
		} elseif (is_tag()) {
			echo 'TAG';
		} else {
			echo 'ARCHIVE';
		}
		?>
	</p>
	<h1><?php echo esc_html(wp_strip_all_tags(get_the_archive_title())); ?></h1>
	<?php
	$mzk_desc = get_the_archive_description();
	if ($mzk_desc) :
		?>
		<div class="meta"><?php echo esc_html(wp_strip_all_tags($mzk_desc)); ?></div>
	<?php endif; ?>
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
		<?php endif; ?>
	</div>
</div>

<?php
get_footer();
