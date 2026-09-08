<?php
/**
 * 一覧用のカード
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_cats = get_the_category();
?>
<article <?php post_class('entry-card'); ?>>
	<a href="<?php the_permalink(); ?>">
		<div class="thumb">
			<img src="<?php echo esc_url(mzk_post_thumb(get_post(), 'image/column/01.jpg', 'mzk-card')); ?>" alt="" loading="lazy" decoding="async" />
		</div>
		<?php if ($mzk_cats) : ?>
			<span class="cat"><?php echo esc_html($mzk_cats[0]->name); ?></span>
		<?php endif; ?>
		<h2><?php the_title(); ?></h2>
		<p class="excerpt"><?php echo esc_html(get_the_excerpt()); ?></p>
		<span class="date"><?php echo esc_html(get_the_date('Y.m.d')); ?></span>
	</a>
</article>
