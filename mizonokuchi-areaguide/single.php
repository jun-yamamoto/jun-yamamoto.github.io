<?php
/**
 * 単一記事
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()) :
	the_post();
	$mzk_cats = get_the_category();
	?>

	<div class="page-hero">
		<p class="eyebrow"><?php echo esc_html($mzk_cats ? $mzk_cats[0]->name : 'ARTICLE'); ?></p>
		<h1><?php the_title(); ?></h1>
		<?php mzk_entry_meta(); ?>
	</div>

	<div class="content-area">
		<div class="inner inner--720">
			<article <?php post_class(); ?>>
				<?php if (has_post_thumbnail()) : ?>
					<figure class="entry-thumb"><?php the_post_thumbnail('mzk-wide'); ?></figure>
				<?php endif; ?>

				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages(
						array(
							'before' => '<div class="page-links">',
							'after'  => '</div>',
						)
					);
					?>
				</div>

				<div class="entry-foot">
					<?php foreach ($mzk_cats as $mzk_cat) : ?>
						<a class="cat-link" href="<?php echo esc_url(get_category_link($mzk_cat)); ?>"><?php echo esc_html($mzk_cat->name); ?></a>
					<?php endforeach; ?>
					<?php
					$mzk_tags = get_the_tags();
					if ($mzk_tags) :
						foreach ($mzk_tags as $mzk_tag) :
							?>
							<a class="cat-link" href="<?php echo esc_url(get_tag_link($mzk_tag)); ?>">#<?php echo esc_html($mzk_tag->name); ?></a>
							<?php
						endforeach;
					endif;
					?>
				</div>

				<nav class="post-nav" aria-label="<?php esc_attr_e('前後の記事', 'mizonokuchi'); ?>">
					<?php
					$mzk_prev = get_previous_post();
					$mzk_next = get_next_post();
					if ($mzk_prev) :
						?>
						<a class="prev" href="<?php echo esc_url(get_permalink($mzk_prev)); ?>">
							<span class="dir">PREV</span>
							<span class="ttl"><?php echo esc_html(get_the_title($mzk_prev)); ?></span>
						</a>
					<?php endif; ?>
					<?php if ($mzk_next) : ?>
						<a class="next" href="<?php echo esc_url(get_permalink($mzk_next)); ?>">
							<span class="dir">NEXT</span>
							<span class="ttl"><?php echo esc_html(get_the_title($mzk_next)); ?></span>
						</a>
					<?php endif; ?>
				</nav>

				<?php
				if (comments_open() || get_comments_number()) {
					comments_template();
				}
				?>
			</article>
		</div>
	</div>

	<?php
endwhile;

get_footer();
