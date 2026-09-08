<?php
/**
 * トップ：01 AREA COLUMN
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_post  = mzk_section_post('column');
$mzk_url   = mzk_link('column', 'post', home_url('/'));
$mzk_title = $mzk_post ? get_the_title($mzk_post) : '多摩の川辺に灯る、古き宿場のいま。';
$mzk_thumb = mzk_post_thumb($mzk_post, 'image/column/01.jpg');
?>
<section id="area-column" class="section washi">
	<div class="inner inner--1200">

		<?php mzk_chapter_head('column'); ?>

		<div class="grid grid--12">
			<div class="col-6 column-text">
				<span class="big-quote quote" aria-hidden="true">&ldquo;</span>

				<h2 class="column-title">
					<?php if ($mzk_post) : ?>
						<a href="<?php echo esc_url($mzk_url); ?>"><?php echo esc_html($mzk_title); ?></a>
					<?php else : ?>
						多摩の川辺に灯る、<br /><span class="brush">古き宿場のいま。</span>
					<?php endif; ?>
				</h2>

				<?php if ($mzk_post) : ?>
					<p class="f-mincho-2"><?php echo esc_html(mzk_section_excerpt('column', '', 140)); ?></p>
				<?php else : ?>
					<p class="f-mincho-2">かつて大山街道の宿場町として賑わい、いまは都心と自然を繋ぐ結節点となった街。溝の口。</p>
					<p class="f-mincho-2">再開発の風景の隙間に、昔ながらの商店の暖簾がひらり。──ここには、時代を重ねてきた街の呼吸がある。</p>
				<?php endif; ?>

				<div class="column-actions">
					<a href="<?php echo esc_url($mzk_url); ?>" class="btn btn-ink f-en-sans">
						READ THE STORY <?php mzk_the_icon('arrow-right'); ?>
					</a>
					<span class="wave-mark" aria-hidden="true"></span>
				</div>
			</div>

			<figure class="col-6 column-figure">
				<div class="figure-tag">
					<span class="label f-en-sans">CHAPTER</span>
					<span class="num">Ⅰ.</span>
				</div>
				<a href="<?php echo esc_url($mzk_url); ?>">
					<img src="<?php echo esc_url($mzk_thumb); ?>" alt="<?php echo esc_attr($mzk_title); ?>" loading="lazy" decoding="async" />
				</a>
				<figcaption>
					<span aria-hidden="true">—</span>
					<span>Mizonokuchi Station East Entrance / Photograph taken in the clear autumn light.</span>
				</figcaption>
			</figure>
		</div>
	</div>
</section>
