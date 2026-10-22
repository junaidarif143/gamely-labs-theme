<?php
/**
 * Not found.
 */
get_header();
?>
<main class="g-wrap">
	<section class="g-404">
		<p class="g-kicker"><?php esc_html_e( 'Page not found', 'gamely' ); ?></p>
		<h1><?php esc_html_e( 'This patch is bare.', 'gamely' ); ?></h1>
		<p><?php esc_html_e( 'The page you were looking for has moved or never sprouted. Try one of our calculators instead.', 'gamely' ); ?></p>
	</section>
	<div class="g-cards">
		<?php foreach ( gamely_tools() as $tool ) : ?>
			<a class="g-card" href="<?php echo esc_url( gamely_tool_url( $tool ) ); ?>">
				<?php echo gamely_tool_icon( $tool ); // phpcs:ignore ?>
				<span class="g-card__title"><?php echo esc_html( $tool['short'] ); ?></span>
				<span class="g-card__text"><?php echo esc_html( $tool['blurb'] ); ?></span>
			</a>
		<?php endforeach; ?>
	</div>
</main>
<?php
get_footer();
