<div class="home-step">
			<?php
			// 将来的な動的実装例：データ配列から生成
			$step_items = [
				[
					'title' => '新ソルフェージュ<br class="is-sp">指導法講座',
					'labels' => ['オンライン', '対話'],
					'text_lg' => '音感を磨く専門的な音楽トレーニング',
					'text_sm' => 'ソルフェージュを"教える力"が、あなたの未来を広げる',
					'link' => '/course/solfege/',
					'link_text' => '詳しくはこちら',
					'image' => 'https://placehold.jp/316x300.png'
				],
				[
					'title' => '新ソルフェージュ<br class="is-sp">指導法講座',
					'labels' => ['オンライン', '対話'],
					'text_lg' => '音感を磨く専門的な音楽トレーニング',
					'text_sm' => 'ソルフェージュを"教える力"が、あなたの未来を広げる',
					'link' => '/course/solfege/',
					'link_text' => '詳しくはこちら',
					'image' => 'https://placehold.jp/316x300.png'
				],
				[
					'title' => '新ソルフェージュ<br class="is-sp">指導法講座',
					'labels' => ['オンライン', '対話'],
					'text_lg' => '音感を磨く専門的な音楽トレーニング',
					'text_sm' => 'ソルフェージュを"教える力"が、あなたの未来を広げる',
					'link' => '/course/solfege/',
					'link_text' => '詳しくはこちら',
					'image' => 'https://placehold.jp/316x300.png'
				]
			];

			foreach ($step_items as $index => $item) :
				$step_number = $index + 1; // 1から始まる連番
				$is_odd = ($step_number % 2 === 1); // 奇数判定
				$bg_image_suffix = $is_odd ? 'odd' : 'even'; // 奇数:odd, 偶数:even
			?>
			<div class="home-step__fields animated fadeIn delay-100ms">
				<div class="home-step__col">
					<span class="home-step__item -num" data-bg="<?= ASSET_URI . "/img/icon_hana.png" ?>"></span>
					<div class="home-step__item -title">
						<h3><?= $item['title'] ?></h3>
					</div>
					<div class="home-step__item -label">
						<?php foreach ($item['labels'] as $label) : ?>
						<span><?= esc_html($label) ?></span>
						<?php endforeach; ?>
					</div>
					<div class="home-step__item -text-lg">
						<p><?= esc_html($item['text_lg']) ?></p>
					</div>
					<div class="home-step__item -text-sm">
						<p><?= esc_html($item['text_sm']) ?></p>
					</div>
					<div class="home-step__item -btn">
						<a href="<?= esc_url($item['link']) ?>" class="c-btn -arrowRight">
							<span>詳しくはこちら</span>
						</a>
					</div>
				</div>
				<div class="home-step__col">
					<!-- 動的に奇数・偶数判定して背景画像を設定 -->
					<div class="home-step__item -img" data-bg="<?= ASSET_URI . "/img/icon_hana_{$bg_image_suffix}.svg" ?>">
						<img src="<?= esc_url($item['image']) ?>" alt="講座画像<?= $step_number ?>">
					</div>
				</div>
			</div><!-- home-step__fields -->
			<?php endforeach; ?>

		</div>
