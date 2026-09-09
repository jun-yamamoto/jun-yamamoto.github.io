<?php
/**
 * トップ：04 GOURMET
 *
 * ３ブロック（NEW OPEN / TRADITIONAL / STYLISH）を配列でまわして出力し、
 * 最後にグルメコーナー全体へ誘導する VIEW MORE ボタンを 1 つ置く。
 * 各ブロックのリンク先は、同名スラッグの固定ページ／カテゴリー／記事を
 * mzk_link() が解決する。見つからない場合は gourmet カテゴリーへ。
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_gourmet_fallback = mzk_link('gourmet', 'archive', home_url('/'));

$mzk_blocks = apply_filters(
	'mzk_gourmet_blocks',
	array(
		array(
			'slug'    => 'gourmet-new-open',
			'no'      => '01',
			'tone'    => 'gold',
			'label'   => 'NEW OPEN',
			'title'   => '現代の感性が光る、<br />新しい美食の体験。',
			'text'    => 'こだわりの素材と革新的なアプローチで、溝の口のグルメシーンに新たな風を吹き込む店舗。洗練された空間と共に特別な時間を提供します。',
			'badge'   => '未知を拓く一皿',
			'flip'    => false,
			'images'  => array(
				'image/GOURMET/NEW OPEN/345418_13_03mizonokuchi.jpg',
				'image/GOURMET/NEW OPEN/futatsubo_03.jpg',
				'image/GOURMET/NEW OPEN/futatsubo_04.jpg',
			),
		),
		array(
			'slug'    => 'gourmet-traditional',
			'no'      => '02',
			'tone'    => 'seal',
			'label'   => 'TRADITIONAL',
			'title'   => '代々受け継がれる、<br />変わらぬ街の味。',
			'text'    => '長年にわたり地元で愛され続ける老舗。時代が変わっても色褪せない、職人の技と温かなおもてなしが、ここにはあります。',
			'badge'   => '時代が愛した名店',
			'flip'    => true,
			'images'  => array(
				'image/GOURMET/TRADITIONAL/243337_bubu_005.jpg',
				'image/GOURMET/TRADITIONAL/277353_mizo_04003.jpg',
				'image/GOURMET/TRADITIONAL/277354_mizo_04004.jpg',
			),
		),
		array(
			'slug'    => 'gourmet-stylish',
			'no'      => '03',
			'tone'    => 'white',
			'label'   => 'STYLISH',
			'title'   => '空間も味も妥協しない、<br />実力派ダイニング。',
			'text'    => '写真映えするだけでなく、確かな味で人々を魅了する店舗。休日のブランチや友人とのディナーに最適な空間をご紹介します。',
			'badge'   => '洗練を纏う実力派',
			'flip'    => false,
			'images'  => array(
				'image/GOURMET/STYLISH/todai_02.jpg',
				'image/GOURMET/STYLISH/todai_03.jpg',
				'image/GOURMET/STYLISH/Patisserie i （パティスリー アイ）.jpg',
			),
		),
	)
);
?>
<section id="gourmet" class="section section--dark">
	<div class="inner inner--1100">

		<?php mzk_chapter_head('gourmet', 'on-dark'); ?>

		<div class="section-lead">
			<p class="en f-en-italic">&ldquo; A taste that stays with you. &rdquo;</p>
			<p class="jp">新旧が集うグルメの街。</p>
		</div>

		<?php foreach ($mzk_blocks as $mzk_b) : ?>
			<?php
			$mzk_post = mzk_section_post($mzk_b['slug']);
			$mzk_url  = mzk_link($mzk_b['slug'], 'archive', $mzk_gourmet_fallback);
			$mzk_txt  = mzk_section_excerpt($mzk_b['slug'], $mzk_b['text'], 110);
			$mzk_dir  = $mzk_b['flip'] ? 'l' : 'r';
			?>
			<div class="grid grid--12 gourmet-block">
				<div class="col-5 gourmet-body <?php echo $mzk_b['flip'] ? 'order-2' : ''; ?>">
					<div class="gourmet-no gourmet-no--<?php echo esc_attr($mzk_b['tone']); ?>">
						<span class="num sec-mega"><?php echo esc_html($mzk_b['no']); ?></span>
						<span class="rule" aria-hidden="true"></span>
						<span class="label"><?php echo esc_html($mzk_b['label']); ?></span>
					</div>

					<h3 class="gourmet-title">
						<?php if ($mzk_post) : ?>
							<a href="<?php echo esc_url($mzk_url); ?>"><?php echo esc_html(get_the_title($mzk_post)); ?></a>
						<?php else : ?>
							<?php echo wp_kses($mzk_b['title'], array('br' => array())); ?>
						<?php endif; ?>
					</h3>

					<p class="f-mincho-2"><?php echo esc_html($mzk_txt); ?></p>
				</div>

				<div class="col-7 gourmet-collage gourmet-collage--<?php echo esc_attr($mzk_dir); ?> <?php echo $mzk_b['flip'] ? 'order-1' : ''; ?>">
					<div class="vt-badge vt-badge--<?php echo esc_attr($mzk_b['tone']); ?>">
						<span class="f-mincho"><?php echo esc_html($mzk_b['badge']); ?></span>
					</div>
					<div class="stage">
						<img class="im-a" src="<?php echo esc_url(mzk_img($mzk_b['images'][0])); ?>" alt="" loading="lazy" decoding="async" />
						<img class="im-b" src="<?php echo esc_url(mzk_img($mzk_b['images'][1])); ?>" alt="" loading="lazy" decoding="async" />
						<img class="im-c" src="<?php echo esc_url(mzk_img($mzk_b['images'][2])); ?>" alt="" loading="lazy" decoding="async" />
					</div>
				</div>
			</div>
		<?php endforeach; ?>

		<!-- グルメコーナー全体へ -->
		<div class="gourmet-more">
			<a href="<?php echo esc_url($mzk_gourmet_fallback); ?>" class="btn btn-gold btn-lg f-en-sans">
				VIEW MORE <?php mzk_the_icon('arrow-right'); ?>
			</a>
		</div>
	</div>
</section>
