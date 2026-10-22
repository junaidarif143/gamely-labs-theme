<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#F6F1E7">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="g-sr g-skip" href="#content"><?php esc_html_e( 'Skip to content', 'gamely' ); ?></a>

<header class="g-header" id="g-header">
	<div class="g-wrap g-header__bar">
		<a class="g-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) . ' home' ); ?>">
			<span class="g-brand__mark"><?php echo gamely_icon( 'leaf', 24, '#F2C14E' ); // phpcs:ignore ?></span>
			<span class="g-brand__name"><?php bloginfo( 'name' ); ?></span>
		</a>

		<nav class="g-nav" id="g-nav" aria-label="<?php esc_attr_e( 'Main', 'gamely' ); ?>">
			<?php gamely_nav(); ?>
		</nav>

		<a class="g-btn g-btn--forest g-btn--pill g-header__cta" href="<?php echo esc_url( home_url( '/#tools' ) ); ?>"><?php esc_html_e( 'Browse all tools', 'gamely' ); ?></a>

		<button type="button" class="g-burger" id="g-burger" aria-expanded="false" aria-controls="g-nav" aria-label="<?php esc_attr_e( 'Open menu', 'gamely' ); ?>">
			<span class="g-burger__open"><?php echo gamely_icon( 'menu', 22 ); // phpcs:ignore ?></span>
			<span class="g-burger__close" hidden><?php echo gamely_icon( 'close', 22 ); // phpcs:ignore ?></span>
		</button>
	</div>
</header>

<div id="content">
