<?php
/**
 * トップ：06 EDUCATION
 *
 * バナー画像 1 枚を記事へのリンクにするだけの構成。
 * 画像は「外観 > カスタマイズ > トップページ > EDUCATION バナー画像」か、
 * テーマ内の image/EDUCATION/banner.png（または .jpg / .webp）を置いて差し替える。
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_post  = mzk_section_post('education-interview');
$mzk_url   = mzk_link('education-interview', 'post', mzk_link('education', 'archive', home_url('/')));
$mzk_alt   = $mzk_post ? get_the_title($mzk_post) : '高津区で、子育てするということ。';
?>
<section id="education" class="section section--gold">
	<div class="bg-dot-white" aria-hidden="true"></div>

	<div class="inner inner--1200">

		<?php mzk_chapter_head('education', 'on-gold'); ?>

		<div class="section-lead section-lead--tight section-lead--white">
			<p class="en f-en-italic">&ldquo; A town that grows with families. &rdquo;</p>
			<p class="jp-sm">溝の口で、子を育てる。</p>
		</div>

		<div class="edu-banner">
			<a href="<?php echo esc_url($mzk_url); ?>">
				<?php
				mzk_the_banner_img(
					'mzk_education_banner',
					array(
						'image/EDUCATION/banner.png',
						'image/EDUCATION/banner.jpg',
						'image/EDUCATION/banner.webp',
					),
					$mzk_alt,
					1200
				);
				?>
			</a>
		</div>
	</div>
</section>
