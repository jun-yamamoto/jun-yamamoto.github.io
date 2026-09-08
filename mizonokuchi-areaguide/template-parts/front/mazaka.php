<?php
/**
 * トップ：03 MAZAKA
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_post  = mzk_section_post('mazaka');
$mzk_url   = mzk_link('mazaka', 'post', home_url('/'));
$mzk_thumb = mzk_post_thumb($mzk_post, 'image/MAZAKA/01_dummy.jpg');
$mzk_title = $mzk_post ? get_the_title($mzk_post) : '';
?>
<section id="mazaka" class="section cream">
	<div class="inner inner--1200">

		<?php mzk_chapter_head('mazaka'); ?>

		<div class="mazaka-stage">
			<a href="<?php echo esc_url($mzk_url); ?>">
				<img src="<?php echo esc_url($mzk_thumb); ?>" alt="<?php echo esc_attr($mzk_title ? $mzk_title : 'MAZAKA'); ?>" loading="lazy" decoding="async" />
			</a>

			<div class="mazaka-card">
				<span class="initial sec-mega" aria-hidden="true">M<span class="f-en-italic">.</span></span>

				<div class="head-block">
					<p class="eyebrow">FEATURE FACILITY · N° 03</p>
					<p class="en-title">Mazaka<span class="dot">.</span></p>
				</div>

				<div class="rule" aria-hidden="true"></div>

				<p class="lead">
					<?php if ($mzk_post) : ?>
						<a href="<?php echo esc_url($mzk_url); ?>"><?php echo esc_html($mzk_title); ?></a>
					<?php else : ?>
						歴史の流れに沿う、<br />新しい街の景色。
					<?php endif; ?>
				</p>

				<p class="body f-mincho-2">
					<?php echo esc_html(mzk_section_excerpt('mazaka', '街の歴史を継承しながら、新たな価値を創造する複合施設「MAZAKA」。その建築の思想と、地域と繋がるための設計をご紹介します。', 120)); ?>
				</p>

				<a href="<?php echo esc_url($mzk_url); ?>" class="btn btn-ink f-en-sans">
					READ ARTICLE <?php mzk_the_icon('arrow-right'); ?>
				</a>
			</div>
		</div>
	</div>
</section>
