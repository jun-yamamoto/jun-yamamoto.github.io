<?php
/**
 * フッター
 *
 * 参考: https://tokyo.itot.jp/kugayama/ の footer.php と同じ構成。
 *   #pr_space
 *   footer.footer-style  … PR 枠（.head-txt-foot / .pr-foot / .btn-pr）
 *   .wrap-copyright      … Copyright © COCOLOMACHI Inc. + お問い合わせ /
 *                          プライバシーポリシー / 運営会社 / powered by itot
 *   .pTop                … ページトップへ戻るボタン
 *
 * PR 枠の中身は inc/footer-pr.php を直接書き換えて設定する（件数は自由）。
 * コピーライト行と各リンクは「外観 > カスタマイズ > フッター」で編集できる。
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_pr       = mzk_footer_pr();
$mzk_pr_items = $mzk_pr['items'];
$mzk_pr_count = count($mzk_pr_items);
?>
</div><!-- /#contents -->

<div id="pr_space"></div>

<footer class="footer-style">

	<?php if ($mzk_pr_count) : ?>
		<?php if ($mzk_pr['heading']) : ?>
			<div class="head-txt-foot"><?php echo esc_html($mzk_pr['heading']); ?></div>
		<?php endif; ?>
		<div class="pr-foot pr-foot--<?php echo (1 === $mzk_pr_count) ? 'single' : 'multi'; ?>">
			<div class="inner-foot">
				<div class="base">
					<?php foreach ($mzk_pr_items as $mzk_item) : ?>
						<div class="inner">
							<?php if ($mzk_item['url']) : ?>
								<a href="<?php echo esc_url($mzk_item['url']); ?>"<?php echo $mzk_item['target'] ? ' target="_blank" rel="noopener"' : ''; ?> class="pr-img">
									<img src="<?php echo esc_url($mzk_item['image']); ?>" alt="<?php echo esc_attr($mzk_item['title']); ?>" class="primg" loading="lazy" decoding="async" />
								</a>
							<?php else : ?>
								<span class="pr-img">
									<img src="<?php echo esc_url($mzk_item['image']); ?>" alt="<?php echo esc_attr($mzk_item['title']); ?>" class="primg" loading="lazy" decoding="async" />
								</span>
							<?php endif; ?>

							<div class="wrap-txt-pr">
								<?php if ($mzk_item['sub_title']) : ?>
									<div class="sub-tit-pr"><?php echo esc_html($mzk_item['sub_title']); ?></div>
								<?php endif; ?>
								<div class="tit-pr"><?php echo esc_html($mzk_item['title']); ?></div>
								<?php if ($mzk_item['text']) : ?>
									<div class="txt-pr"><?php echo esc_html($mzk_item['text']); ?></div>
								<?php endif; ?>
								<?php if ($mzk_item['url'] && $mzk_item['btn']) : ?>
									<a href="<?php echo esc_url($mzk_item['url']); ?>"<?php echo $mzk_item['target'] ? ' target="_blank" rel="noopener"' : ''; ?> class="btn-pr"><span><?php echo esc_html($mzk_item['btn']); ?></span></a>
								<?php endif; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<ul class="foot-nav">
		<?php mzk_footer_nav(); ?>
	</ul>
</footer>

<div class="wrap-copyright">
	<div class="inner-copy base">
		<div class="copy-left"><?php echo wp_kses_post(mzk_footer_option('mzk_copyright')); ?></div>
		<ul class="copy-right">
			<li class="hover">
				<?php contact_link(); ?>
			</li>
			<li class="hover"> <a href="<?php echo esc_url(mzk_footer_option('mzk_privacy_url')); ?>" target="_blank" rel="noopener"><?php esc_html_e('プライバシーポリシー', 'mizonokuchi'); ?></a> </li>
			<li class="hover"> <a href="<?php echo esc_url(mzk_footer_option('mzk_company_url')); ?>" target="_blank" rel="noopener"><?php esc_html_e('運営会社', 'mizonokuchi'); ?></a> </li>
			<li class="hover"> <a href="<?php echo esc_url(mzk_footer_option('mzk_itot_url')); ?>" target="_blank" rel="noopener">
					<?php if (mzk_itot_logo_uri()) : ?>
						<img src="<?php echo esc_url(mzk_itot_logo_uri()); ?>" alt="powered by itot" loading="lazy" decoding="async" />
					<?php else : ?>
						powered by itot
					<?php endif; ?>
				</a> </li>
		</ul>
	</div>
</div>

<?php it_disphtml('google_analytics'); ?>
<?php wp_footer(); ?>

<div class="pTop"> <a href="#pTop" id="pTopBtn" aria-label="<?php esc_attr_e('ページ先頭へ戻る', 'mizonokuchi'); ?>"><?php mzk_the_icon('arrow-up'); ?></a> </div>

</body>

</html>
