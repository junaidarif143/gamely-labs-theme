</div><!-- #content -->

<footer class="g-footer">
	<div class="g-wrap g-footer__grid">
		<div class="g-footer__about">
			<a class="g-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<span class="g-brand__mark"><?php echo gamely_icon( 'leaf', 24, '#F2C14E' ); // phpcs:ignore ?></span>
				<span class="g-brand__name"><?php bloginfo( 'name' ); ?></span>
			</a>
			<p><?php esc_html_e( 'Free lawn, garden and yard calculators for homeowners. Plan the project, skip the guesswork.', 'gamely' ); ?></p>
		</div>

		<?php foreach ( array_keys( gamely_categories() ) as $cat ) : $tools = gamely_tools_in( $cat ); if ( ! $tools ) { continue; } ?>
		<div class="g-footer__col">
			<h2><?php echo esc_html( gamely_cat_label( $cat, true ) ); ?></h2>
			<ul>
				<?php foreach ( $tools as $tool ) : ?>
					<li><a href="<?php echo esc_url( gamely_tool_url( $tool ) ); ?>"><?php echo esc_html( $tool['short'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endforeach; ?>

		<?php $links = gamely_site_links(); if ( $links ) : ?>
		<div class="g-footer__col">
			<h2><?php esc_html_e( 'Site', 'gamely' ); ?></h2>
			<ul>
				<?php foreach ( $links as $link ) : ?>
					<li><a href="<?php echo esc_url( $link['url'] ); ?>"><?php echo esc_html( $link['label'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
	<div class="g-wrap g-footer__legal">
		&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Results are estimates; always check product labels and local guidance.', 'gamely' ); ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
