<?php
/**
 * A single guide (blog post): reading column, "On this page", tools used in the guide.
 */
get_header();

while ( have_posts() ) :
	the_post();
	$content = apply_filters( 'the_content', get_the_content() );
	$lede    = '';
	if ( preg_match( '#<p class="gl-lede">(.*?)</p>#s', $content, $m ) ) {
		$lede    = $m[1];
		$content = str_replace( $m[0], '', $content );
	}
	$guides_url = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/' );
	$tool_keys  = array_filter( explode( ',', (string) get_post_meta( get_the_ID(), '_gl_tools', true ) ) );
	$all_tools  = gamely_tools();
	$used       = array();
	foreach ( $tool_keys as $k ) {
		if ( isset( $all_tools[ $k ] ) ) {
			$used[] = $all_tools[ $k ];
		}
	}
	?>
	<main class="g-wrap">
		<nav class="g-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'gamely' ); ?>">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'gamely' ); ?></a></li>
				<li><a href="<?php echo esc_url( $guides_url ); ?>"><?php esc_html_e( 'Guides', 'gamely' ); ?></a></li>
				<li aria-current="page"><?php the_title(); ?></li>
			</ol>
		</nav>

		<header class="g-titleband">
			<div class="g-titleband__text">
				<p class="g-kicker" style="margin-bottom:12px"><?php esc_html_e( 'Guide', 'gamely' ); ?></p>
				<h1><?php the_title(); ?></h1>
				<?php if ( $lede ) : ?>
					<p class="g-titleband__lead"><?php echo wp_kses_post( $lede ); ?></p>
				<?php endif; ?>
				<p class="g-meta"><?php echo gamely_post_byline(); // phpcs:ignore ?></p>
			</div>
		</header>

		<div class="g-layout">
			<article class="g-main g-content" id="g-article">
				<?php echo $content; // phpcs:ignore -- filtered by the_content ?>
				<?php gamely_author_box(); ?>

				<?php if ( $used ) : ?>
					<section class="g-cta-tools" aria-labelledby="g-tools-in-guide">
						<h2 id="g-tools-in-guide"><?php esc_html_e( 'Calculators for this project', 'gamely' ); ?></h2>
						<div class="g-cards">
							<?php foreach ( $used as $tool ) : ?>
								<a class="g-card" href="<?php echo esc_url( gamely_tool_url( $tool ) ); ?>">
									<?php echo gamely_tool_icon( $tool ); // phpcs:ignore ?>
									<span class="g-card__title"><?php echo esc_html( $tool['short'] ); ?></span>
									<span class="g-card__text"><?php echo esc_html( $tool['blurb'] ); ?></span>
									<span class="g-card__cta"><?php echo esc_html( $tool['cta'] ); ?> →</span>
								</a>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endif; ?>
			</article>

			<aside class="g-side" aria-label="<?php esc_attr_e( 'More on this page', 'gamely' ); ?>">
				<div class="g-panel" id="g-toc-panel" hidden>
					<h2 class="g-panel__title"><?php esc_html_e( 'On this page', 'gamely' ); ?></h2>
					<ul class="g-toc" id="g-toc"></ul>
				</div>
				<?php if ( $used ) : ?>
					<div class="g-panel">
						<h2 class="g-panel__title"><?php esc_html_e( 'Tools in this guide', 'gamely' ); ?></h2>
						<div class="g-related">
							<?php foreach ( $used as $tool ) : ?>
								<a href="<?php echo esc_url( gamely_tool_url( $tool ) ); ?>"><?php echo gamely_tool_icon( $tool, 'g-ico--sm', 22 ); // phpcs:ignore ?><span><?php echo esc_html( $tool['title'] ); ?></span></a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				<?php gamely_ad( 'sidebar' ); ?>
			</aside>
		</div>

		<?php
		$more = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 3, 'post__not_in' => array( get_the_ID() ) ) );
		if ( $more ) :
			?>
			<section class="g-section" style="padding-top:0;padding-bottom:24px" aria-labelledby="g-more-guides">
				<div class="g-section__head"><h2 id="g-more-guides"><?php esc_html_e( 'More guides', 'gamely' ); ?></h2><a href="<?php echo esc_url( $guides_url ); ?>"><?php esc_html_e( 'All guides →', 'gamely' ); ?></a></div>
				<div class="g-posts">
					<?php foreach ( $more as $more_post ) : $GLOBALS['post'] = $more_post; setup_postdata( $more_post ); ?>
						<?php get_template_part( 'parts/guide-card' ); ?>
					<?php endforeach; wp_reset_postdata(); ?>
				</div>
			</section>
		<?php endif; ?>
	</main>
	<?php
endwhile;

get_footer();
