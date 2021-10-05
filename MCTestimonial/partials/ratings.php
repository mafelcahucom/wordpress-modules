<?php
/**
 * Testimonial Ratings.
 *
 * @package mc-testimonial
 * @since   1.0.0
 * @author  Mafel John Cahucom
 */

?>
<style type="text/css">
	.mc-tst__rating__average h5 {
		margin: 0;
		font-size: 50px;
		line-height: 50px;
		margin-bottom: 15px;
	}
	.mc-tst__rating__average p {
		margin: 0;
	}
	.mc-tst__rating-list-star {
		display: flex;
		align-items: center;
		padding: 10px 0;
		margin: 0;
		list-style: none;
	}
	.mc-tst__rating-list-star li {
		margin-right: 5px;
		fill: gray;
	}
	.mc-tst__rating-list-star li[data-state="active"] {
		fill: #ffc313;
	}
	.mc-tst__rating-list-star svg {
		display: block;
		width: 16px;
	}
	.mc-tst__rating__list {
		padding: 0;
		margin: 0;
		list-style: none;
	}
	.mc-tst__rating__list li {
		display: flex;
		align-items: center;
		margin-bottom: 5px;
	}
	.mc-tst__rating__list a {
		color: #212121;
		text-decoration: none;
	}
	.mc-tst__rating__title {
		min-width: 50px;
	}
	.mc-tst__rating__count {
		min-width: 30px;
		text-align: center;
	}
	.mc-tst__rating__bar {
		width: 100%;
	}
	.mc-tst__rating__meter {
		height: 15px;
	    background: #f5f5f5;
	    border-radius: 3px;
	}
	.mc-tst__rating__progress {
		height: 15px;
	    background: #ffc313;
	    border-radius: 3px;
	}
</style>

<div class="mc-tst__rating">
	<div class="mc-tst__rating__average">
		<h5><?php echo esc_html( $average ); ?></h5>
		<p>base on <?php echo esc_html( $total_count ); ?> ratings</p>
		<?php
			$mc_tst_star = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M394 480a16 16 0 01-9.39-3L256 383.76 127.39 477a16 16 0 01-24.55-18.08L153 310.35 23 221.2a16 16 0 019-29.2h160.38l48.4-148.95a16 16 0 0130.44 0l48.4 149H480a16 16 0 019.05 29.2L359 310.35l50.13 148.53A16 16 0 01394 480z"/></svg>';
		?>
		<ul class="mc-tst__rating-list-star">
			<?php for( $i = 1; $i <= 5; $i++ ): ?>
				<li data-state="<?php echo ( $i <= $average ? 'active' : 'unactive' );  ?>">
					<?php echo $mc_tst_star; ?>
				</li>
			<?php endfor; ?>
		</ul>
	</div>
	<ul class="mc-tst__rating__list">
		<?php foreach( $rating_details as $rating ): ?>
			<li>
				<div class="mc-tst__rating__title">
					<a href="<?php echo esc_url( $rating['link'] ); ?>">
						<span><?php echo esc_html( $rating['rating'] .' '. ( $rating['rating'] == 1 ? 'Star' : 'Stars' ) ); ?></span>
					</a>
				</div>
				<div class="mc-tst__rating__bar" title="<?php echo esc_attr( $rating['percent'] ); ?>">
					<a href="<?php echo esc_url( $rating['link'] ); ?>">
						<div class="mc-tst__rating__meter">
							<div class="mc-tst__rating__progress" style="width: <?php echo $rating['percent']; ?>"></div>
						</div>
					</a>
				</div>
				<div class="mc-tst__rating__count">
					<a href="<?php echo esc_url( $rating['link'] ); ?>">
						<span>(<?php echo esc_html( $rating['count'] ); ?>)</span>
					</a>
				</div>
			</li>
		<?php endforeach; ?>
	</ul>
</div>