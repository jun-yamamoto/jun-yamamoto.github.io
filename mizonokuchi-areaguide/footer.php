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
 * 各項目は「外観 > カスタマイズ > フッター（PR枠・コピーライト）」で編集できる。
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_pr_title = mzk_footer_option('mzk_pr_title');
$mzk_pr_url   = mzk_footer_option('mzk_pr_url');
$mzk_pr_image = mzk_footer_option('mzk_pr_image');
$mzk_has_pr   = ('' !== trim($mzk_pr_title) || '' !== trim($mzk_pr_url));
?>
</div><!-- /#contents -->

<div id="pr_space"></div>

<footer class="footer-style">

	<?php if ($mzk_has_pr) : ?>
		<div class="head-txt-foot"><?php echo esc_html(mzk_footer_option('mzk_pr_heading')); ?></div>
		<div class="pr-foot">
			<div class="inner-foot">
				<div class="base">
					<div class="inner">
						<?php if ($mzk_pr_url) : ?>
							<a href="<?php echo esc_url($mzk_pr_url); ?>" target="_blank" rel="noopener" class="pr-img">
								<img src="<?php echo esc_url($mzk_pr_image ? $mzk_pr_image : mzk_img('image/pr/01_dummy.jpg')); ?>" alt="<?php echo esc_attr($mzk_pr_title); ?>" class="primg" loading="lazy" decoding="async" />
							</a>
						<?php else : ?>
							<span class="pr-img">
								<img src="<?php echo esc_url($mzk_pr_image ? $mzk_pr_image : mzk_img('image/pr/01_dummy.jpg')); ?>" alt="<?php echo esc_attr($mzk_pr_title); ?>" class="primg" loading="lazy" decoding="async" />
							</span>
						<?php endif; ?>

						<div class="wrap-txt-pr">
							<?php if (mzk_footer_option('mzk_pr_sub_title')) : ?>
								<div class="sub-tit-pr"><?php echo esc_html(mzk_footer_option('mzk_pr_sub_title')); ?></div>
							<?php endif; ?>
							<div class="tit-pr"><?php echo esc_html($mzk_pr_title); ?></div>
							<?php if (mzk_footer_option('mzk_pr_text')) : ?>
								<div class="txt-pr"><?php echo esc_html(mzk_footer_option('mzk_pr_text')); ?></div>
							<?php endif; ?>
							<?php if ($mzk_pr_url) : ?>
								<a href="<?php echo esc_url($mzk_pr_url); ?>" target="_blank" rel="noopener" class="btn-pr"><span><?php echo esc_html(mzk_footer_option('mzk_pr_btn_label')); ?></span></a>
							<?php endif; ?>
						</div>
					</div>
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
