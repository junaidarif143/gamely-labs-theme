<?php
/**
 * Guide card used in lists. Expects the global $post to be set up.
 */
?>
<article class="g-post-card">
	<?php if ( has_post_thumbnail() ) : ?>
		<a class="g-post-card__img" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'medium_large', array( 'alt' => '', 'loading' => 'lazy', 'sizes' => '(max-width: 700px) 100vw, 380px' ) ); ?></a>
	<?php endif; ?>
	<p class="g-post-card__kicker"><?php esc_html_e( 'Guide', 'gamely' ); ?></p>
	<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26, '…' ) ); ?></p>
	<a class="g-post-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read the guide →', 'gamely' ); ?><span class="g-sr"> <?php the_title(); ?></span></a>
</article>
