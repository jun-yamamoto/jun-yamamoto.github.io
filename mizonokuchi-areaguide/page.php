<?php
/**
 * 固定ページ
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

get_header();

while (have_posts()) :
	the_post();
	?>

	<div class="page-hero">
		<p class="eyebrow">MIZONOKUCHI AREA GUIDE</p>
		<h1><?php the_title(); ?></h1>
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
			</article>
		</div>
	</div>

	<?php
endwhile;

get_footer();
