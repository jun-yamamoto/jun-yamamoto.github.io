<?php
/**
 * トップページ
 *
 * モック（index.html）の構成を踏襲しつつ、各セクションの見出し・本文・
 * 画像・リンク先を WordPress の記事から取得できるようにしている。
 * リンク先は mzk_link() が「固定ページ → カテゴリー（最新記事）→ 投稿」の
 * 順に解決するため、ページ内アンカーではなく実際の記事へ飛ぶ。
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

get_header();

get_template_part('template-parts/front/hero');
get_template_part('template-parts/front/column');
get_template_part('template-parts/front/promotion');
get_template_part('template-parts/front/mazaka');
get_template_part('template-parts/front/gourmet');
get_template_part('template-parts/front/convenience');
get_template_part('template-parts/front/education');
get_template_part('template-parts/front/life-info');
get_template_part('template-parts/front/property');

get_footer();
