<?php
/**
 * 検索フォーム
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
	<label class="screen-reader-text" for="mzk-s"><?php esc_html_e('検索', 'mizonokuchi'); ?></label>
	<input type="search" id="mzk-s" name="s" value="<?php echo esc_attr(get_search_query()); ?>" placeholder="<?php esc_attr_e('キーワードで検索', 'mizonokuchi'); ?>" />
	<button type="submit">SEARCH</button>
</form>
