<?php
/**
 * Gamely theme functions.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GAMELY_VERSION', '1.1.1' );

/* ---------------------------------------------------------------
 * Setup
 * ------------------------------------------------------------- */
add_action( 'after_setup_theme', function () {
	add_theme_support( 'title-tag' );
	add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	register_nav_menus( array( 'footer' => __( 'Footer site links', 'gamely' ) ) );
} );

/**
 * When switching from an older copy of this theme in another folder (e.g. "gamely" to
 * "gamely-labs-theme"), carry its Customizer settings over: author name and ad code.
 */
add_action( 'after_switch_theme', function ( $old_name, $old_theme = null ) {
	if ( ! $old_theme instanceof WP_Theme || 0 !== strpos( $old_theme->get_stylesheet(), 'gamely' ) ) {
		return;
	}
	$old_mods = get_option( 'theme_mods_' . $old_theme->get_stylesheet(), array() );
	if ( ! is_array( $old_mods ) ) {
		return;
	}
	foreach ( $old_mods as $key => $value ) {
		if ( 0 === strpos( (string) $key, 'gamely_' ) && '' === (string) get_theme_mod( $key, '' ) ) {
			set_theme_mod( $key, $value );
		}
	}
}, 10, 2 );

add_action( 'wp_enqueue_scripts', function () {
	wp_enqueue_style( 'gamely-fonts', 'https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Figtree:wght@400;500;600;700;800&display=swap', array(), null );
	wp_enqueue_style( 'gamely', get_stylesheet_uri(), array( 'gamely-fonts' ), GAMELY_VERSION );
	wp_enqueue_script( 'gamely', get_template_directory_uri() . '/assets/theme.js', array(), GAMELY_VERSION, true );
	// Block library styles are only needed if a page uses core blocks beyond plain HTML.
	if ( is_front_page() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'global-styles' );
	}
} );

add_filter( 'wp_resource_hints', function ( $urls, $relation ) {
	if ( 'preconnect' === $relation ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
	}
	return $urls;
}, 10, 2 );

// Lighter pages: drop the emoji script.
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* ---------------------------------------------------------------
 * Tool catalog (from the Gamely Labs Tools plugin)
 * ------------------------------------------------------------- */
function gamely_tools() {
	return function_exists( 'gl_tools_catalog' ) ? gl_tools_catalog() : array();
}

function gamely_tools_in( $cat ) {
	return array_filter( gamely_tools(), function ( $t ) use ( $cat ) {
		return $t['cat'] === $cat;
	} );
}

function gamely_tool_url( $tool ) {
	return home_url( '/' . $tool['slug'] . '/' );
}

/** The catalog entry for the page being viewed, or null. */
function gamely_current_tool() {
	if ( ! is_page() ) {
		return null;
	}
	$slug = get_post_field( 'post_name', get_queried_object_id() );
	foreach ( gamely_tools() as $key => $tool ) {
		if ( $tool['slug'] === $slug ) {
			$tool['key'] = $key;
			return $tool;
		}
	}
	return null;
}

function gamely_cat_label( $cat ) {
	return 'lawn' === $cat ? __( 'Lawn tools', 'gamely' ) : __( 'Garden tools', 'gamely' );
}

/* ---------------------------------------------------------------
 * Icons (inline stroke SVG)
 * ------------------------------------------------------------- */
function gamely_icon( $name, $size = 28, $stroke = 'currentColor' ) {
	$paths = array(
		'leaf'    => '<path d="M12 21V11"/><path d="M12 11c0-4 3-7 7-7 0 4-3 7-7 7z"/><path d="M12 14c0-3-2.5-5.5-6-5.5 0 3 2.5 5.5 6 5.5z"/>',
		'drop'    => '<path d="M12 3s6 6.5 6 11a6 6 0 0 1-12 0c0-4.5 6-11 6-11z"/>',
		'sack'    => '<path d="M6 8h12l-1 13H7z"/><path d="M9 8V5a3 3 0 0 1 6 0v3"/><path d="M10 13h4"/>',
		'flask'   => '<path d="M9 3h6"/><path d="M10 3v6l-5 9a2 2 0 0 0 2 3h10a2 2 0 0 0 2-3l-5-9V3"/><path d="M7.5 15h9"/>',
		'grid'    => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/>',
		'bed'     => '<path d="M3 10h18v9H3z"/><path d="M3 14h18"/><path d="M8 10V6M12 10V4M16 10V7"/>',
		'doc'     => '<path d="M4 4h16v16H4z"/><path d="M8 9h8M8 13h5"/>',
		'shield'  => '<path d="M12 3l8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7z"/><path d="M9 12l2 2 4-4"/>',
		'printer' => '<path d="M6 9V3h12v6"/><rect x="4" y="9" width="16" height="8" rx="2"/><path d="M8 17v4h8v-4"/>',
		'chevron' => '<path d="M6 9l6 6 6-6"/>',
		'menu'    => '<path d="M4 7h16M4 12h16M4 17h16"/>',
		'close'   => '<path d="M6 6l12 12M18 6L6 18"/>',
		'arrow'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
	);
	if ( ! isset( $paths[ $name ] ) ) {
		return '';
	}
	return '<svg width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr( $stroke ) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $paths[ $name ] . '</svg>';
}

/** Coloured icon tile for a tool. */
function gamely_tool_icon( $tool, $class = '', $size = 28 ) {
	$svg = gamely_icon( $tool['icon'], $size, $tool['ink'] );
	if ( '' === $svg && ! empty( $tool['svg'] ) ) {
		// Icon shapes supplied by the plugin's catalog. Accept only self-closing basic shapes whose
		// attributes hold numbers, letters, spaces and path punctuation; anything else is ignored.
		$shapes = '#^(\s*<(path|ellipse|circle|rect|line)(\s+[a-z0-9]+="[a-zA-Z0-9 .,\-()]*")*\s*/>)+\s*$#';
		if ( preg_match( $shapes, $tool['svg'] ) ) {
			$svg = '<svg width="' . (int) $size . '" height="' . (int) $size . '" viewBox="0 0 24 24" fill="none" stroke="' . esc_attr( $tool['ink'] ) . '" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">' . $tool['svg'] . '</svg>';
		}
	}
	return '<span class="g-ico ' . esc_attr( $class ) . '" style="background:' . esc_attr( $tool['tint'] ) . '">' . $svg . '</span>';
}

/* ---------------------------------------------------------------
 * Site pages that may or may not exist yet (About, Contact, legal)
 * ------------------------------------------------------------- */
function gamely_site_links() {
	$links = array();
	$want  = array(
		'about'          => __( 'About', 'gamely' ),
		'contact'        => __( 'Contact', 'gamely' ),
		'privacy-policy' => __( 'Privacy policy', 'gamely' ),
		'terms'          => __( 'Terms', 'gamely' ),
	);
	foreach ( $want as $slug => $label ) {
		$page = get_page_by_path( $slug );
		if ( $page && 'publish' === $page->post_status ) {
			$links[ $slug ] = array( 'label' => $label, 'url' => get_permalink( $page ) );
		}
	}
	return $links;
}

/* ---------------------------------------------------------------
 * SEO basics: meta description + breadcrumb structured data
 * ------------------------------------------------------------- */
add_action( 'wp_head', function () {
	$desc = '';
	if ( is_front_page() ) {
		$desc = __( 'Free lawn, garden and yard calculators for homeowners: sprinkler run times, fertilizer, lime, raised bed soil and square foot garden planning. Every tool shows its math.', 'gamely' );
	} elseif ( is_home() ) {
		$desc = __( 'Practical, sourced guides for lawn, garden and yard projects: when to fertilize, how to water new seed, overseeding, sod, soil tests and more.', 'gamely' );
	} elseif ( is_singular() ) {
		$post = get_queried_object();
		$desc = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 30, '…' );
	}
	if ( $desc ) {
		echo '<meta name="description" content="' . esc_attr( wp_strip_all_tags( $desc ) ) . '">' . "\n";
	}

	$tool = gamely_current_tool();
	if ( $tool ) {
		$data = array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => array(
				array( '@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => home_url( '/' ) ),
				array( '@type' => 'ListItem', 'position' => 2, 'name' => gamely_cat_label( $tool['cat'] ), 'item' => home_url( '/#' . $tool['cat'] ) ),
				array( '@type' => 'ListItem', 'position' => 3, 'name' => $tool['title'], 'item' => gamely_tool_url( $tool ) ),
			),
		);
		echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n";
	}

	$head_code = get_theme_mod( 'gamely_ads_head', '' );
	if ( $head_code ) {
		echo $head_code . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput -- admin-provided ad code, saved only by users with unfiltered_html.
	}
}, 2 );

/* ---------------------------------------------------------------
 * Customizer: author name and ad code
 * ------------------------------------------------------------- */
function gamely_sanitize_code( $value ) {
	return current_user_can( 'unfiltered_html' ) ? $value : wp_kses_post( $value );
}

add_action( 'customize_register', function ( $wp_customize ) {
	$wp_customize->add_section( 'gamely_site', array( 'title' => __( 'Gamely: author & ads', 'gamely' ), 'priority' => 30 ) );

	$wp_customize->add_setting( 'gamely_author', array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
	$wp_customize->add_control( 'gamely_author', array( 'label' => __( 'Author name shown on tool pages', 'gamely' ), 'section' => 'gamely_site', 'type' => 'text' ) );

	$ads = array(
		'gamely_ads_head'    => __( 'AdSense code for <head> (auto ads / verification)', 'gamely' ),
		'gamely_ad_content'  => __( 'Ad unit: below each calculator', 'gamely' ),
		'gamely_ad_sidebar'  => __( 'Ad unit: tool page sidebar', 'gamely' ),
		'gamely_ad_home'     => __( 'Ad unit: homepage between sections', 'gamely' ),
	);
	foreach ( $ads as $id => $label ) {
		$wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => 'gamely_sanitize_code' ) );
		$wp_customize->add_control( $id, array( 'label' => $label, 'section' => 'gamely_site', 'type' => 'textarea' ) );
	}
} );

/** Print an ad slot only when ad code has been added in the Customizer. */
function gamely_ad( $slot ) {
	$code = get_theme_mod( 'gamely_ad_' . $slot, '' );
	if ( ! $code ) {
		return;
	}
	echo '<aside class="g-ad g-ad--' . esc_attr( $slot ) . '" aria-label="' . esc_attr__( 'Advertisement', 'gamely' ) . '"><span class="g-ad__label">' . esc_html__( 'Advertisement', 'gamely' ) . '</span>' . $code . '</aside>'; // phpcs:ignore WordPress.Security.EscapeOutput
}

/** Put the content ad right after the calculator on tool pages. */
add_filter( 'the_content', function ( $content ) {
	if ( ! is_page() || ! gamely_current_tool() || ! get_theme_mod( 'gamely_ad_content', '' ) ) {
		return $content;
	}
	ob_start();
	gamely_ad( 'content' );
	$ad = ob_get_clean();
	// After the tool's closing wrapper (the first </div> that ends the .gl-tool mount).
	$pos = strpos( $content, 'data-gl-tool=' );
	if ( false === $pos ) {
		return $content;
	}
	$end = strpos( $content, '</div>', $pos );
	return false === $end ? $content : substr_replace( $content, '</div>' . $ad, $end, 6 );
}, 20 );

/* ---------------------------------------------------------------
 * Header navigation
 * ------------------------------------------------------------- */
function gamely_nav() {
	$current = gamely_current_tool();
	echo '<ul class="g-nav__list">';
	foreach ( array( 'lawn', 'garden' ) as $cat ) {
		$tools = gamely_tools_in( $cat );
		if ( ! $tools ) {
			continue;
		}
		$is_current = $current && $current['cat'] === $cat;
		$id         = 'g-menu-' . $cat;
		echo '<li class="g-nav__item' . ( $is_current ? ' is-current' : '' ) . '">';
		echo '<button type="button" class="g-nav__toggle" aria-expanded="false" aria-controls="' . esc_attr( $id ) . '">' . esc_html( gamely_cat_label( $cat ) ) . gamely_icon( 'chevron', 16 ) . '</button>';
		echo '<ul class="g-menu" id="' . esc_attr( $id ) . '">';
		foreach ( $tools as $tool ) {
			$aria = ( $current && $current['slug'] === $tool['slug'] ) ? ' aria-current="page"' : '';
			echo '<li><a href="' . esc_url( gamely_tool_url( $tool ) ) . '"' . $aria . '>' . gamely_tool_icon( $tool, 'g-ico--sm', 22 ) . '<span><strong>' . esc_html( $tool['short'] ) . '</strong><small>' . esc_html( wp_trim_words( $tool['blurb'], 9, '…' ) ) . '</small></span></a></li>';
		}
		echo '</ul></li>';
	}
	$guides = (int) get_option( 'page_for_posts' );
	if ( $guides && 'publish' === get_post_status( $guides ) ) {
		$on   = is_home() || is_singular( 'post' );
		$aria = is_home() ? ' aria-current="page"' : '';
		echo '<li class="g-nav__item' . ( $on ? ' is-current' : '' ) . '"><a class="g-nav__link" href="' . esc_url( get_permalink( $guides ) ) . '"' . $aria . '>' . esc_html__( 'Guides', 'gamely' ) . '</a></li>';
	}
	foreach ( gamely_site_links() as $slug => $link ) {
		if ( 'about' === $slug || 'contact' === $slug ) {
			$aria = is_page( $slug ) ? ' aria-current="page"' : '';
			echo '<li class="g-nav__item' . ( is_page( $slug ) ? ' is-current' : '' ) . '"><a class="g-nav__link" href="' . esc_url( $link['url'] ) . '"' . $aria . '>' . esc_html( $link['label'] ) . '</a></li>';
		}
	}
	echo '</ul>';
	echo '<div class="g-nav__mobile-cta"><a class="g-btn g-btn--forest" href="' . esc_url( home_url( '/#tools' ) ) . '">' . esc_html__( 'Browse all tools', 'gamely' ) . '</a></div>';
}

/* ---------------------------------------------------------------
 * Byline for tool pages
 * ------------------------------------------------------------- */
function gamely_byline() {
	$parts  = array();
	$author = get_theme_mod( 'gamely_author', '' );
	if ( $author ) {
		$about   = get_page_by_path( 'about' );
		$parts[] = $about && 'publish' === $about->post_status
			? sprintf( __( 'By %s', 'gamely' ), '<a href="' . esc_url( get_permalink( $about ) ) . '">' . esc_html( $author ) . '</a>' )
			: sprintf( __( 'By %s', 'gamely' ), esc_html( $author ) );
	}
	$parts[] = sprintf( __( 'Updated %s', 'gamely' ), esc_html( get_the_modified_date( 'F Y' ) ) );
	if ( false !== stripos( get_post_field( 'post_content', get_the_ID() ), '<h2>Sources</h2>' ) ) {
		$parts[] = '<a href="#sources">' . esc_html__( 'Sources: university extension guides', 'gamely' ) . '</a>';
	}
	return implode( ' · ', $parts );
}

/** Byline for guides: author, published and updated dates, sources link. */
function gamely_post_byline() {
	$parts  = array();
	$author = get_theme_mod( 'gamely_author', '' );
	if ( $author ) {
		$about   = get_page_by_path( 'about' );
		$parts[] = $about && 'publish' === $about->post_status
			? sprintf( __( 'By %s', 'gamely' ), '<a href="' . esc_url( get_permalink( $about ) ) . '">' . esc_html( $author ) . '</a>' )
			: sprintf( __( 'By %s', 'gamely' ), esc_html( $author ) );
	}
	$published = get_the_date( 'F j, Y' );
	$modified  = get_the_modified_date( 'F j, Y' );
	$parts[]   = esc_html( $published );
	if ( $modified !== $published ) {
		$parts[] = sprintf( __( 'Updated %s', 'gamely' ), esc_html( $modified ) );
	}
	if ( false !== stripos( get_post_field( 'post_content', get_the_ID() ), '<h2>Sources</h2>' ) ) {
		$parts[] = '<a href="#sources">' . esc_html__( 'Sources', 'gamely' ) . '</a>';
	}
	return implode( ' · ', $parts );
}

/* ---------------------------------------------------------------
 * Guides: structured data and an in-article ad slot
 * ------------------------------------------------------------- */
add_action( 'wp_head', function () {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	$post   = get_queried_object();
	$author = get_theme_mod( 'gamely_author', '' );
	$about  = get_page_by_path( 'about' );
	$data   = array(
		'@context'         => 'https://schema.org',
		'@type'            => 'Article',
		'headline'         => get_the_title( $post ),
		'description'      => get_the_excerpt( $post ),
		'datePublished'    => get_the_date( 'c', $post ),
		'dateModified'     => get_the_modified_date( 'c', $post ),
		'mainEntityOfPage' => get_permalink( $post ),
		'publisher'        => array( '@type' => 'Organization', 'name' => get_bloginfo( 'name' ), 'url' => home_url( '/' ) ),
	);
	if ( $author ) {
		$data['author'] = array( '@type' => 'Person', 'name' => $author );
		if ( $about ) {
			$data['author']['url'] = get_permalink( $about );
		}
	}
	echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n";
}, 3 );

add_filter( 'the_content', function ( $content ) {
	if ( ! is_singular( 'post' ) || ! get_theme_mod( 'gamely_ad_content', '' ) ) {
		return $content;
	}
	// Before the second section heading, so readers get into the guide first.
	$first = strpos( $content, '<h2' );
	$second = false === $first ? false : strpos( $content, '<h2', $first + 3 );
	if ( false === $second ) {
		return $content;
	}
	ob_start();
	gamely_ad( 'content' );
	return substr_replace( $content, ob_get_clean(), $second, 0 );
}, 20 );

/** "Related guides" for a tool page (from the plugin's tool tags). */
function gamely_related_guides( $tool_key ) {
	return function_exists( 'gl_tools_guides_for' ) ? gl_tools_guides_for( $tool_key, 3 ) : array();
}
