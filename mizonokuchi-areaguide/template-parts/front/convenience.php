<?php
/**
 * トップ：05 CONVENIENCE
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_conv_fallback = mzk_link('convenience', 'archive', home_url('/'));

$mzk_cards = apply_filters(
	'mzk_convenience_cards',
	array(
		array(
			'slug'  => 'convenience-01',
			'no'    => '01',
			'image' => 'image/CONVENIENCE/01.jpg',
			'title' => '必要な施設がしっかりそろい、<br />安心できる住環境。',
		),
		array(
			'slug'  => 'convenience-02',
			'no'    => '02',
			'image' => 'image/CONVENIENCE/02.jpg',
			'title' => '「溝の口」駅と「武蔵溝ノ口」の<br />特徴とエリアにおける違いを紹介。',
		),
	)
);

$mzk_marui_post = mzk_section_post('convenience-marui');
$mzk_marui_url  = mzk_link('convenience-marui', 'post', $mzk_conv_fallback);
$mzk_marui_img  = mzk_post_thumb($mzk_marui_post, 'image/CONVENIENCE/03.jpg');
?>
<section id="convenience" class="section washi">
	<div class="inner inner--1000">

		<?php mzk_chapter_head('convenience'); ?>

		<div class="section-lead section-lead--tight section-lead--gold">
			<p class="en f-en-italic">Everyday, comfortably.</p>
			<p class="jp-sm">日々の暮らしを支える、確かな街。</p>
		</div>

		<div class="conv-cards">
			<?php foreach ($mzk_cards as $mzk_c) : ?>
				<?php
				$mzk_post = mzk_section_post($mzk_c['slug']);
				$mzk_url  = mzk_link($mzk_c['slug'], 'post', $mzk_conv_fallback);
				$mzk_img  = mzk_post_thumb($mzk_post, $mzk_c['image']);
				?>
				<a class="conv-card" href="<?php echo esc_url($mzk_url); ?>">
					<div class="thumb">
						<img src="<?php echo esc_url($mzk_img); ?>" alt="" loading="lazy" decoding="async" />
						<span class="num sec-mega" aria-hidden="true"><?php echo esc_html($mzk_c['no']); ?></span>
					</div>
					<h3>
						<?php if ($mzk_post) : ?>
							<?php echo esc_html(get_the_title($mzk_post)); ?>
						<?php else : ?>
							<?php echo wp_kses($mzk_c['title'], array('br' => array())); ?>
						<?php endif; ?>
					</h3>
					<span class="link-underline">READ <?php mzk_the_icon('arrow-right'); ?></span>
				</a>
			<?php endforeach; ?>
		</div>

		<a class="conv-wide" href="<?php echo esc_url($mzk_marui_url); ?>">
			<div class="num sec-mega" aria-hidden="true">03</div>
			<div class="thumb">
				<img src="<?php echo esc_url($mzk_marui_img); ?>" alt="" loading="lazy" decoding="async" />
			</div>
			<div class="body">
				<div class="eyebrow">
					<span class="rule" aria-hidden="true"></span>
					<span class="label">SHOPPING FACILITY</span>
				</div>
				<h3>Marui Family</h3>
				<div class="jp"><?php echo esc_html($mzk_marui_post ? get_the_title($mzk_marui_post) : 'マルイファミリー溝口'); ?></div>
				<p><?php echo esc_html(mzk_section_excerpt('convenience-marui', '駅前に直結し、ファッションから食品、雑貨まで多彩なショップが揃う大型商業施設。日常の買い物をより豊かに彩ります。', 110)); ?></p>
				<span class="link-gold">VIEW DETAILS <?php mzk_the_icon('arrow-right'); ?></span>
			</div>
		</a>
	</div>
</section>
