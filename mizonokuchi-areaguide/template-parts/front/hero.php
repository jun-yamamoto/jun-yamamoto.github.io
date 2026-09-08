<?php
/**
 * トップ：ヒーロー
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_hero_images = apply_filters(
	'mzk_hero_images',
	array(
		'image/top/higashiguchi3.JPG',
		'image/top/kitaguchi3.JPG',
		'image/top/minamiguchi2.JPG',
	)
);
?>
<div class="hero" id="top">
	<?php foreach (array_values($mzk_hero_images) as $i => $rel) : ?>
		<img
			src="<?php echo esc_url(mzk_img($rel)); ?>"
			alt=""
			class="hero-image kb<?php echo 0 === $i ? ' is-active' : ''; ?>"
			<?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?>
			decoding="async" />
	<?php endforeach; ?>

	<div class="hero-overlay" aria-hidden="true"></div>

	<div class="hero-side hero-side--l" aria-hidden="true">
		<div class="row">
			<span class="rule"></span>
			<span class="meta-side vt">MIZONOKUCHI · AREA GUIDE</span>
		</div>
		<div class="meta-side">&copy; <?php echo esc_html(wp_date('Y')); ?><br />VOL.01</div>
	</div>
	<div class="hero-side hero-side--r" aria-hidden="true">
		<div class="row">
			<span class="meta-side vt">KAWASAKI · TAKATSU</span>
			<span class="rule"></span>
		</div>
		<div class="meta-side">AUTUMN<br /><?php echo esc_html(wp_date('Y')); ?></div>
	</div>

	<div class="hero-body">
		<p class="hero-eyebrow f-en-italic fade-up" style="animation-delay:.3s">an urban journal of</p>

		<h1 class="hero-title fade-up" style="animation-delay:.5s">Mizonokuchi</h1>

		<div class="hero-sub fade-up" style="animation-delay:.7s">
			<span class="rule" aria-hidden="true"></span>
			<p class="f-mincho">溝 の 口 案 内</p>
			<span class="rule" aria-hidden="true"></span>
		</div>

		<div class="hero-lead fade-up" style="animation-delay:.95s">
			<p class="en f-en-italic">&ldquo; Between the shadow and the shine,<br />a town finds its rhythm. &rdquo;</p>
			<p class="jp f-mincho-2">陰翳と艶のあいだで、街は息づく。</p>
		</div>
	</div>

	<div class="hero-scroll" aria-hidden="true">
		<span class="label">SCROLL DOWN</span>
		<span class="track"><i class="scroll-line"></i></span>
	</div>
</div>
