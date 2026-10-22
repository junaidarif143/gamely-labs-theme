<?php
/**
 * Fallback template: blog index, archives, search and single posts.
 */
get_header();
?>
<main class="g-wrap">
	<?php if ( is_singular() ) : ?>
		<?php while ( have_posts() ) : the_post(); ?>
			<nav class="g-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'gamely' ); ?>">
				<ol>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'gamely' ); ?></a></li>
					<li aria-current="page"><?php the_title(); ?></li>
				</ol>
			</nav>
			<header class="g-titleband">
				<div class="g-titleband__text">
					<h1><?php the_title(); ?></h1>
					<p class="g-meta"><?php echo esc_html( sprintf( __( 'Updated %s', 'gamely' ), get_the_modified_date( 'F j, Y' ) ) ); ?></p>
				</div>
			</header>
			<div class="g-layout">
				<article class="g-main g-content"><?php the_content(); ?></article>
			</div>
		<?php endwhile; ?>
	<?php else : ?>
		<header class="g-titleband" style="padding-top:40px">
			<div class="g-titleband__text">
				<h1><?php
				if ( is_search() ) {
					printf( esc_html__( 'Results for “%s”', 'gamely' ), esc_html( get_search_query() ) );
				} elseif ( is_archive() ) {
					echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
				} else {
					esc_html_e( 'Guides', 'gamely' );
				}
				?></h1>
			</div>
		</header>
		<div class="g-layout">
			<div class="g-main">
				<?php if ( have_posts() ) : ?>
					<div class="g-posts">
						<?php while ( have_posts() ) : the_post(); ?>
							<article class="g-post-card">
								<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
								<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26, '…' ) ); ?></p>
								<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read guide →', 'gamely' ); ?></a>
							</article>
						<?php endwhile; ?>
					</div>
					<div class="g-pagination"><?php echo paginate_links(); // phpcs:ignore ?></div>
				<?php else : ?>
					<p><?php esc_html_e( 'Nothing here yet.', 'gamely' ); ?> <a href="<?php echo esc_url( home_url( '/#tools' ) ); ?>"><?php esc_html_e( 'Browse the calculators', 'gamely' ); ?></a></p>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
</main>
<?php
get_footer();
