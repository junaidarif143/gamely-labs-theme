<?php
/**
 * Guides index (the WordPress "posts page").
 */
get_header();
$page_id = (int) get_option( 'page_for_posts' );
?>
<main class="g-wrap">
	<nav class="g-crumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'gamely' ); ?>">
		<ol>
			<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'gamely' ); ?></a></li>
			<li aria-current="page"><?php esc_html_e( 'Guides', 'gamely' ); ?></li>
		</ol>
	</nav>
	<header class="g-titleband">
		<div class="g-titleband__text">
			<h1><?php echo esc_html( $page_id ? get_the_title( $page_id ) : __( 'Guides', 'gamely' ) ); ?></h1>
			<p class="g-titleband__lead"><?php esc_html_e( 'Practical, sourced how-tos for lawn, garden and yard projects. Each guide links to the calculator that does the math for you.', 'gamely' ); ?></p>
		</div>
	</header>
	<div class="g-layout" style="display:block">
		<?php if ( have_posts() ) : ?>
			<div class="g-posts">
				<?php while ( have_posts() ) : the_post(); get_template_part( 'parts/guide-card' ); endwhile; ?>
			</div>
			<div class="g-pagination"><?php echo paginate_links(); // phpcs:ignore ?></div>
		<?php else : ?>
			<p><?php esc_html_e( 'Guides are on their way.', 'gamely' ); ?> <a href="<?php echo esc_url( home_url( '/#tools' ) ); ?>"><?php esc_html_e( 'Browse the calculators', 'gamely' ); ?></a></p>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
