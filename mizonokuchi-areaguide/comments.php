<?php
/**
 * コメント
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

if (post_password_required()) {
	return;
}
?>
<section id="comments" class="comments-area">

	<?php if (have_comments()) : ?>
		<h2 class="comments-title">
			<?php
			$mzk_count = get_comments_number();
			/* translators: %s: コメント数 */
			printf(esc_html(_n('コメント（%s件）', 'コメント（%s件）', $mzk_count, 'mizonokuchi')), esc_html(number_format_i18n($mzk_count)));
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 44,
				)
			);
			?>
		</ol>

		<?php
		the_comments_pagination(
			array(
				'prev_text' => 'PREV',
				'next_text' => 'NEXT',
			)
		);
		?>
	<?php endif; ?>

	<?php if (!comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments')) : ?>
		<p class="no-comments"><?php esc_html_e('コメントは受け付けていません。', 'mizonokuchi'); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		array(
			'title_reply'         => __('コメントを送る', 'mizonokuchi'),
			'title_reply_to'      => __('%s さんへ返信', 'mizonokuchi'),
			'cancel_reply_link'   => __('返信をキャンセル', 'mizonokuchi'),
			'label_submit'        => __('送信する', 'mizonokuchi'),
			'class_submit'        => 'btn btn-ink f-en-sans comment-submit',
			'comment_notes_before'=> '<p class="comment-notes">' . esc_html__('メールアドレスが公開されることはありません。', 'mizonokuchi') . '</p>',
			'comment_field'       => sprintf(
				'<p class="comment-form-comment"><label for="comment">%1$s</label><textarea id="comment" name="comment" cols="45" rows="6" required></textarea></p>',
				esc_html__('コメント', 'mizonokuchi')
			),
		)
	);
	?>
</section>
