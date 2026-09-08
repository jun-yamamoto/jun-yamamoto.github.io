<?php
/**
 * トップ：06 EDUCATION
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_post  = mzk_section_post('education-interview');
$mzk_url   = mzk_link('education-interview', 'post', mzk_link('education', 'archive', home_url('/')));
$mzk_thumb = mzk_post_thumb($mzk_post, 'image/EDUCATION/01.jpg');
$mzk_title = $mzk_post ? get_the_title($mzk_post) : '';
?>
<section id="education" class="section section--gold">
	<div class="bg-dot-white" aria-hidden="true"></div>

	<div class="inner inner--1000">

		<?php mzk_chapter_head('education', 'on-gold'); ?>

		<div class="section-lead section-lead--tight section-lead--white">
			<p class="en f-en-italic">&ldquo; A town that grows with families. &rdquo;</p>
			<p class="jp-sm">溝の口で、子を育てる。</p>
		</div>

		<div class="edu-stage">
			<img src="<?php echo esc_url($mzk_thumb); ?>" alt="" loading="lazy" decoding="async" />
			<div class="veil" aria-hidden="true"></div>

			<div class="edu-card-wrap">
				<div class="edu-card">
					<div class="badge" aria-hidden="true"><?php mzk_the_icon('mic', 'icon', 1.6); ?></div>
					<div class="inner-body">
						<p class="en f-en-italic">Special Interview</p>
						<span class="wave-mark" aria-hidden="true"></span>
						<h3>
							<?php if ($mzk_post) : ?>
								<a href="<?php echo esc_url($mzk_url); ?>"><?php echo esc_html($mzk_title); ?></a>
							<?php else : ?>
								高津区の子育て環境を<br />区役所へインタビュー
							<?php endif; ?>
						</h3>
						<a href="<?php echo esc_url($mzk_url); ?>" class="btn btn-ink f-en-sans">
							READ ARTICLE <?php mzk_the_icon('arrow-right'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
