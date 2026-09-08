<?php
/**
 * トップ：02 PROMOTION（末長組）
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_post  = mzk_section_post('promotion');
$mzk_url   = mzk_link('promotion', 'post', home_url('/'));
$mzk_thumb = mzk_post_thumb($mzk_post, 'image/pr/01_dummy.jpg');
$mzk_title = $mzk_post ? get_the_title($mzk_post) : '';

$mzk_movies = array(
	array('id' => get_theme_mod('mzk_movie_1', '33GNNfem_Es'), 'no' => 'i.',  'label' => '日中編'),
	array('id' => get_theme_mod('mzk_movie_2', 'oczZEFMkOYw'), 'no' => 'ii.', 'label' => '夜の街編'),
);
?>
<section id="promotion" class="section section--navy">
	<div class="bg-dot-white" aria-hidden="true"></div>
	<div class="gold-hairline" aria-hidden="true"></div>

	<div class="inner inner--1200">

		<?php mzk_chapter_head('promotion', 'on-dark'); ?>

		<div class="grid grid--12 promo-grid">
			<div class="col-6 promo-figure">
				<div class="figure-tag on-navy">
					<span class="label f-en-sans">CHAPTER</span>
					<span class="num">Ⅱ.</span>
				</div>
				<a href="<?php echo esc_url($mzk_url); ?>">
					<img src="<?php echo esc_url($mzk_thumb); ?>" alt="<?php echo esc_attr($mzk_title ? $mzk_title : '末長組'); ?>" loading="lazy" decoding="async" />
				</a>
			</div>

			<div class="col-6 promo-text">
				<span class="tag-outline">PROMOTION · SUENAGA GUMI</span>

				<h2 class="promo-title">
					<?php if ($mzk_post) : ?>
						<a href="<?php echo esc_url($mzk_url); ?>"><?php echo esc_html($mzk_title); ?></a>
					<?php else : ?>
						溝の口の発展と、<br /><span class="accent">末長組</span>のものづくり。
					<?php endif; ?>
				</h2>

				<?php if ($mzk_post) : ?>
					<p class="f-mincho-2"><?php echo esc_html(mzk_section_excerpt('promotion', '', 140)); ?></p>
				<?php else : ?>
					<p class="f-mincho-2">高津区の地形と向き合い続け、末長組はこの街と共に歩んできました。宿場町から鉄道結節点、そして現代の広域生活拠点へ。</p>
					<p class="f-mincho-2">北口再開発から南口広場整備へと続く、街の新たなフェーズをご紹介します。</p>
				<?php endif; ?>

				<a href="<?php echo esc_url($mzk_url); ?>" class="btn btn-gold f-en-sans">
					VIEW MORE <?php mzk_the_icon('arrow-right'); ?>
				</a>
			</div>
		</div>

		<!-- MOVIE GALLERY -->
		<div class="movie-gallery">
			<div class="head">
				<p class="en f-en-italic">Movie Gallery</p>
				<p class="jp f-mincho-2">溝の口周辺ガイド</p>
			</div>
			<div class="movie-list">
				<?php foreach ($mzk_movies as $mzk_movie) : ?>
					<?php if (!$mzk_movie['id']) { continue; } ?>
					<div class="movie-item">
						<div class="movie-frame">
							<iframe
								src="<?php echo esc_url('https://www.youtube-nocookie.com/embed/' . rawurlencode($mzk_movie['id'])); ?>"
								title="<?php echo esc_attr('溝の口周辺ガイド ' . $mzk_movie['label']); ?>"
								loading="lazy"
								allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture;web-share"
								allowfullscreen></iframe>
						</div>
						<div class="movie-caption">
							<span class="no f-en-italic"><?php echo esc_html($mzk_movie['no']); ?></span>
							<span class="rule dot-rule" aria-hidden="true"></span>
							<span class="jp f-mincho-2"><?php echo esc_html($mzk_movie['label']); ?></span>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
