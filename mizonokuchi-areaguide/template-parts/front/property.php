<?php
/**
 * トップ：PROPERTY（特選物件）
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

// property スラッグが無い場合は、フッター PR の 1 件目のリンク先にフォールバックする。
$mzk_pr_first = mzk_footer_pr();
$mzk_pr_first = isset($mzk_pr_first['items'][0]['url']) ? $mzk_pr_first['items'][0]['url'] : '';

$mzk_url = mzk_link('property', 'archive', $mzk_pr_first);
if (!$mzk_url) {
	$mzk_url = home_url('/');
}
?>
<section id="property" class="section section--navy property">
	<div class="gold-hairline" aria-hidden="true"></div>
	<div class="property-ghost sec-mega" aria-hidden="true">Property</div>

	<div class="property-inner">
		<div class="property-eyebrow">
			<span class="rule" aria-hidden="true"></span>
			<span class="label">FEATURED PROPERTY</span>
			<span class="rule" aria-hidden="true"></span>
		</div>

		<p class="property-en f-en-italic">Where refined days begin.</p>
		<h2 class="property-jp">洗練された日常と、<br />上質な住空間がここに。</h2>
		<p class="property-text f-mincho-2">
			溝の口エリアの特選物件情報をご紹介。<br />
			利便性と自然環境を兼ね備えた、理想の暮らしをご提案します。
		</p>

		<a href="<?php echo esc_url($mzk_url); ?>" class="btn btn-gold btn-lg f-en-sans">
			VIEW PROPERTY <?php mzk_the_icon('arrow-right'); ?>
		</a>
	</div>
</section>
