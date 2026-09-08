<?php
/**
 * トップ：07 LIFE INFORMATION
 *
 * @package mizonokuchi
 */

defined('ABSPATH') || exit;

$mzk_map = get_theme_mod('mzk_area_map', '');
if (!$mzk_map && file_exists(get_theme_file_path('image/map/area-map.jpg'))) {
	$mzk_map = get_theme_file_uri('image/map/area-map.jpg');
}

$mzk_tiles = apply_filters(
	'mzk_life_tiles',
	array(
		array('slug' => 'life-public',   'no' => 'i.',   'icon' => 'building-2',     'name' => '公共・医療'),
		array('slug' => 'life-child',    'no' => 'ii.',  'icon' => 'graduation-cap', 'name' => '子育て・教育'),
		array('slug' => 'life-shopping', 'no' => 'iii.', 'icon' => 'shopping-bag',   'name' => 'ショッピング'),
		array('slug' => 'life-park',     'no' => 'iv.',  'icon' => 'trees',          'name' => '公園・スポーツ'),
		array('slug' => 'life-gourmet',  'no' => 'v.',   'icon' => 'utensils',       'name' => 'グルメ'),
	)
);

$mzk_life_fallback = mzk_link('life-info', 'archive', home_url('/'));
?>
<section id="life-info" class="section cream">
	<div class="inner inner--1200">

		<?php mzk_chapter_head('life-info'); ?>

		<div class="section-lead section-lead--tight section-lead--seal">
			<p class="en f-en-italic">Around the neighborhood.</p>
			<p class="jp-sm">カテゴリ別・エリア案内。</p>
		</div>

		<div class="area-map">
			<?php if ($mzk_map) : ?>
				<img src="<?php echo esc_url($mzk_map); ?>" alt="<?php esc_attr_e('溝の口エリアマップ', 'mizonokuchi'); ?>" loading="lazy" decoding="async" />
			<?php else : ?>
				<?php /* 差し替え用のダミーマップ。「外観 > カスタマイズ > トップページ」で画像を設定すると置き換わる。 */ ?>
				<svg viewBox="0 0 1200 480" role="img" aria-label="<?php esc_attr_e('溝の口エリアマップ（ダミー）', 'mizonokuchi'); ?>">
					<defs>
						<pattern id="gridB" width="40" height="40" patternUnits="userSpaceOnUse">
							<path d="M 40 0 L 0 0 0 40" fill="none" stroke="#c8bfab" stroke-width="0.5" />
						</pattern>
						<pattern id="gridB-l" width="200" height="200" patternUnits="userSpaceOnUse">
							<path d="M 200 0 L 0 0 0 200" fill="none" stroke="#a8997c" stroke-width="1" />
						</pattern>
					</defs>
					<rect width="1200" height="480" fill="#efe6d1" />
					<rect width="1200" height="480" fill="url(#gridB)" />
					<rect width="1200" height="480" fill="url(#gridB-l)" />
					<path d="M0 300 Q 200 260 400 290 T 800 280 T 1200 260" fill="none" stroke="#8fb0c4" stroke-width="14" stroke-linecap="round" opacity="0.7" />
					<path d="M0 300 Q 200 260 400 290 T 800 280 T 1200 260" fill="none" stroke="#5a7d95" stroke-width="1" />
					<path d="M100 60 L 900 420" stroke="#a8997c" stroke-width="2" />
					<path d="M300 40 L 700 460" stroke="#a8997c" stroke-width="2" />
					<g transform="translate(600 220)">
						<circle r="28" fill="#b8402f" />
						<circle r="40" fill="none" stroke="#b8402f" stroke-width="1" opacity="0.5" />
						<circle r="54" fill="none" stroke="#b8402f" stroke-width="1" opacity="0.3" />
						<text y="6" text-anchor="middle" fill="#fff" font-family="Zen Old Mincho, serif" font-size="14" font-weight="700">駅</text>
					</g>
					<text x="600" y="275" text-anchor="middle" fill="#0e0d10" font-family="Zen Old Mincho, serif" font-size="15" font-weight="700">溝の口</text>
					<g transform="translate(360 150)"><circle r="7" fill="#1c2942" /><text x="13" y="4" fill="#1c2942" font-family="Inter, sans-serif" font-size="11" font-weight="600">Park</text></g>
					<g transform="translate(840 130)"><circle r="7" fill="#b8402f" /><text x="13" y="4" fill="#b8402f" font-family="Inter, sans-serif" font-size="11" font-weight="600">School</text></g>
					<g transform="translate(420 380)"><circle r="7" fill="#a68a56" /><text x="13" y="4" fill="#a68a56" font-family="Inter, sans-serif" font-size="11" font-weight="600">Shop</text></g>
					<g transform="translate(880 360)"><circle r="7" fill="#1c2942" /><text x="13" y="4" fill="#1c2942" font-family="Inter, sans-serif" font-size="11" font-weight="600">Hospital</text></g>
					<g transform="translate(200 260)"><circle r="7" fill="#a68a56" /><text x="13" y="4" fill="#a68a56" font-family="Inter, sans-serif" font-size="11" font-weight="600">Cafe</text></g>
					<g transform="translate(1120 60)">
						<circle r="28" fill="none" stroke="#a68a56" stroke-width="1" />
						<text y="-34" text-anchor="middle" fill="#a68a56" font-family="Cormorant Garamond, serif" font-size="15" font-style="italic">N</text>
						<polygon points="0,-18 5,4 0,0 -5,4" fill="#b8402f" />
						<polygon points="0,18 5,-4 0,0 -5,-4" fill="#0e0d10" />
					</g>
					<text x="30" y="42" fill="#7a5f36" font-family="Cormorant Garamond, serif" font-style="italic" font-size="22">Mizonokuchi Area Map</text>
					<text x="30" y="60" fill="#7a5f36" font-family="Inter, sans-serif" font-size="9" letter-spacing="2">DUMMY · PLACEHOLDER</text>
				</svg>
			<?php endif; ?>

			<div class="pin f-en-sans">
				<?php mzk_the_icon('map-pin'); ?> AREA MAP
			</div>
		</div>

		<div class="cat-tiles">
			<?php foreach ($mzk_tiles as $mzk_t) : ?>
				<a class="cat-tile" href="<?php echo esc_url(mzk_link($mzk_t['slug'], 'archive', $mzk_life_fallback)); ?>">
					<span class="no sec-mega" aria-hidden="true"><?php echo esc_html($mzk_t['no']); ?></span>
					<span class="ico" aria-hidden="true"><?php mzk_the_icon($mzk_t['icon'], 'icon', 1.5); ?></span>
					<span class="name"><?php echo esc_html($mzk_t['name']); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
