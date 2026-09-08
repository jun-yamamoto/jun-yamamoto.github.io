<?php
/**
 * 404
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

get_header();
?>

<div class="error-404 washi">
	<p class="code">404</p>
	<h1><?php esc_html_e('お探しのページは見つかりませんでした。', 'mizonokuchi'); ?></h1>
	<p><?php esc_html_e('URL が変更されたか、削除された可能性があります。', 'mizonokuchi'); ?></p>
	<?php get_search_form(); ?>
	<p style="margin-top:32px;">
		<a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-ink f-en-sans">BACK TO TOP <?php mzk_the_icon('arrow-right'); ?></a>
	</p>
</div>

<?php
get_footer();
