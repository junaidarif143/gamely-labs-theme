<?php
/**
 * Pages. Tool pages get the calculator layout with a sidebar; other pages a simple reading layout.
 */
get_header();

while ( have_posts() ) :
	the_post();
	$tool    = gamely_current_tool();
	$content = apply_filters( 'the_content', get_the_content() );

	// Lift the intro paragraph into the title band on tool pages.
	$lede = '';
	if ( $tool && preg_match( '#<p class="gl-lede">(.*?)</p>#s', $content, $m ) ) {
		$lede    = $m[1];
		$content = str_replace( $m[0], '', $content );
	}
	?>
	<main class="g-wrap">
		<nav class="g-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'gamely' ); ?>">
			<ol>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'gamely' ); ?></a></li>
				<?php if ( $tool ) : ?>
					<li><a href="<?php echo esc_url( home_url( '/#' . $tool['cat'] ) ); ?>"><?php echo esc_html( gamely_cat_label( $tool['cat'] ) ); ?></a></li>
				<?php endif; ?>
				<li aria-current="page"><?php echo esc_html( $tool ? $tool['short'] : get_the_title() ); ?></li>
			</ol>
		</nav>

		<header class="g-titleband">
			<?php if ( $tool ) { echo gamely_tool_icon( $tool, 'g-ico--lg', 34 ); } // phpcs:ignore ?>
			<div class="g-titleband__text">
				<h1><?php the_title(); ?></h1>
				<?php if ( $lede ) : ?>
					<p class="g-titleband__lead"><?php echo wp_kses_post( $lede ); ?></p>
				<?php endif; ?>
				<?php if ( $tool ) : ?>
					<p class="g-meta"><?php echo gamely_byline(); // phpcs:ignore ?></p>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( $tool ) : ?>
			<div class="g-layout">
				<article class="g-main g-content" id="g-article">
					<?php echo $content; // phpcs:ignore -- already filtered by the_content ?>
				</article>
				<aside class="g-side" aria-label="<?php esc_attr_e( 'More on this page', 'gamely' ); ?>">
					<div class="g-panel" id="g-toc-panel" hidden>
						<h2 class="g-panel__title"><?php esc_html_e( 'On this page', 'gamely' ); ?></h2>
						<ul class="g-toc" id="g-toc"></ul>
					</div>
					<?php
					// Same keyword cluster first (from the plugin); otherwise same category.
					$related = function_exists( 'gl_tools_related' ) ? gl_tools_related( $tool['key'], 3 ) : array();
					if ( ! $related ) {
						$related = array_filter( gamely_tools(), function ( $t ) use ( $tool ) {
							return $t['slug'] !== $tool['slug'];
						} );
						uasort( $related, function ( $a, $b ) use ( $tool ) {
							return ( $b['cat'] === $tool['cat'] ) <=> ( $a['cat'] === $tool['cat'] );
						} );
						$related = array_slice( $related, 0, 3 );
					}
					if ( $related ) :
						?>
						<div class="g-panel">
							<h2 class="g-panel__title"><?php esc_html_e( 'Related tools', 'gamely' ); ?></h2>
							<div class="g-related">
								<?php foreach ( $related as $r ) : ?>
									<a href="<?php echo esc_url( gamely_tool_url( $r ) ); ?>"><?php echo gamely_tool_icon( $r, 'g-ico--sm', 22 ); // phpcs:ignore ?><span><?php echo esc_html( $r['title'] ); ?></span></a>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
					<?php $guides = gamely_related_guides( $tool['key'] ); if ( $guides ) : ?>
						<div class="g-panel">
							<h2 class="g-panel__title"><?php esc_html_e( 'Related guides', 'gamely' ); ?></h2>
							<ul class="g-toc">
								<?php foreach ( $guides as $g ) : ?>
									<li><a href="<?php echo esc_url( get_permalink( $g ) ); ?>"><?php echo esc_html( get_the_title( $g ) ); ?></a></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
					<?php gamely_ad( 'sidebar' ); ?>
				</aside>
			</div>
		<?php else : ?>
			<div class="g-layout">
				<article class="g-main g-content">
					<?php echo $content; // phpcs:ignore ?>
				</article>
			</div>
		<?php endif; ?>
	</main>
	<?php
endwhile;

get_footer();
