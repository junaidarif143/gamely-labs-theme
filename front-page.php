<?php
/**
 * Homepage.
 */
get_header();
$lawn   = gamely_tools_in( 'lawn' );
$garden = gamely_tools_in( 'garden' );
$all    = gamely_tools();
$soon   = function_exists( 'gl_tools_coming_soon' ) ? gl_tools_coming_soon() : array();
?>
<main>
	<section class="g-wrap g-hero">
		<div class="g-hero__copy">
			<p class="g-kicker"><?php esc_html_e( 'Free lawn, garden & yard calculators', 'gamely' ); ?></p>
			<h1><?php esc_html_e( 'Plan the project.', 'gamely' ); ?><br><?php esc_html_e( 'Skip the guesswork.', 'gamely' ); ?></h1>
			<p class="g-lead"><?php esc_html_e( 'How long to water, how many plants fit, how much soil, seed or lime to buy. Every tool shows its math and comes with a plain-English guide.', 'gamely' ); ?></p>
			<div class="g-hero__actions">
				<?php if ( isset( $all['sprinkler'] ) ) : ?>
					<a class="g-btn g-btn--forest" href="<?php echo esc_url( gamely_tool_url( $all['sprinkler'] ) ); ?>"><?php esc_html_e( 'Water my lawn right', 'gamely' ); ?></a>
				<?php endif; ?>
				<?php if ( isset( $all['sfg'] ) ) : ?>
					<a class="g-btn g-btn--sun" href="<?php echo esc_url( gamely_tool_url( $all['sfg'] ) ); ?>"><?php esc_html_e( 'Plan a garden bed', 'gamely' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
		<div class="g-hero__art">
			<svg viewBox="0 0 460 360" role="img" aria-label="<?php esc_attr_e( 'Illustration of a raised garden bed with seedlings and a watering can', 'gamely' ); ?>">
				<rect x="0" y="0" width="460" height="360" rx="28" fill="#E9E1CF"/>
				<circle cx="370" cy="78" r="38" fill="#F2C14E"/>
				<path d="M0 230 Q 120 170 240 215 T 460 200 V 360 H 0 Z" fill="#9DB57A"/>
				<path d="M0 270 Q 150 225 300 262 T 460 255 V 360 H 0 Z" fill="#6E8F4E"/>
				<rect x="70" y="232" width="250" height="70" rx="6" fill="#8A5E3B"/>
				<rect x="70" y="232" width="250" height="16" rx="6" fill="#6B4A2F"/>
				<line x1="153" y1="248" x2="153" y2="302" stroke="#6B4A2F" stroke-width="4"/>
				<line x1="237" y1="248" x2="237" y2="302" stroke="#6B4A2F" stroke-width="4"/>
				<g stroke="#2F4A2C" stroke-width="5" stroke-linecap="round" fill="#4E7A3A">
					<path d="M110 232 V 196"/><ellipse cx="98" cy="196" rx="14" ry="8"/><ellipse cx="122" cy="186" rx="14" ry="8"/>
					<path d="M195 232 V 182"/><ellipse cx="182" cy="184" rx="15" ry="9"/><ellipse cx="208" cy="172" rx="15" ry="9"/><ellipse cx="194" cy="160" rx="12" ry="8"/>
					<path d="M280 232 V 200"/><ellipse cx="268" cy="200" rx="13" ry="8"/><ellipse cx="292" cy="192" rx="13" ry="8"/>
				</g>
				<g transform="translate(330 168)">
					<path d="M10 30 h60 l-6 54 h-48 z" fill="#C8693C"/>
					<path d="M70 40 l40 -26" stroke="#C8693C" stroke-width="10" stroke-linecap="round"/>
					<path d="M18 30 q22 -30 44 0" fill="none" stroke="#A9532C" stroke-width="7"/>
					<g fill="#7FB3D5"><circle cx="118" cy="22" r="4"/><circle cx="126" cy="36" r="4"/><circle cx="114" cy="44" r="3.5"/></g>
				</g>
				<g fill="#FFFDF7" opacity="0.85"><ellipse cx="110" cy="70" rx="40" ry="14"/><ellipse cx="140" cy="62" rx="30" ry="14"/></g>
			</svg>
		</div>
	</section>

	<section class="g-wrap" aria-label="<?php esc_attr_e( 'Why use our calculators', 'gamely' ); ?>">
		<div class="g-trust">
			<div class="g-trust__item"><?php echo gamely_icon( 'doc', 28, '#5E7F3E' ); // phpcs:ignore ?><span><strong><?php esc_html_e( 'Shows its math.', 'gamely' ); ?></strong> <?php esc_html_e( 'Formulas and a worked example on every page.', 'gamely' ); ?></span></div>
			<div class="g-trust__item"><?php echo gamely_icon( 'shield', 28, '#5E7F3E' ); // phpcs:ignore ?><span><strong><?php esc_html_e( 'Backed by extension research', 'gamely' ); ?></strong> <?php esc_html_e( 'from US land-grant universities.', 'gamely' ); ?></span></div>
			<div class="g-trust__item"><?php echo gamely_icon( 'printer', 28, '#5E7F3E' ); // phpcs:ignore ?><span><strong><?php esc_html_e( 'Print-ready results.', 'gamely' ); ?></strong> <?php esc_html_e( 'Take your plan to the garden center.', 'gamely' ); ?></span></div>
		</div>
	</section>

	<div id="tools"></div>
	<?php
	$groups = array(
		'lawn'   => array( __( 'Lawn tools', 'gamely' ), $lawn ),
		'garden' => array( __( 'Garden tools', 'gamely' ), $garden ),
	);
	$i = 0;
	foreach ( $groups as $cat => $group ) :
		if ( ! $group[1] ) {
			continue;
		}
		$i++;
		?>
		<section class="g-wrap g-section" id="<?php echo esc_attr( $cat ); ?>" aria-labelledby="h-<?php echo esc_attr( $cat ); ?>">
			<div class="g-section__head">
				<h2 id="h-<?php echo esc_attr( $cat ); ?>"><?php echo esc_html( $group[0] ); ?></h2>
			</div>
			<div class="g-cards">
				<?php foreach ( $group[1] as $tool ) : ?>
					<a class="g-card" href="<?php echo esc_url( gamely_tool_url( $tool ) ); ?>">
						<?php echo gamely_tool_icon( $tool ); // phpcs:ignore ?>
						<span class="g-card__title"><?php echo esc_html( $tool['short'] ); ?></span>
						<span class="g-card__text"><?php echo esc_html( $tool['blurb'] ); ?></span>
						<span class="g-card__cta"><?php echo esc_html( $tool['cta'] ); ?> →</span>
					</a>
				<?php endforeach; ?>
				<?php if ( 'garden' === $cat && $soon ) : ?>
					<div class="g-card g-card--soon">
						<span class="g-card__title"><?php esc_html_e( 'Coming soon', 'gamely' ); ?></span>
						<span class="g-card__text"><?php echo esc_html( implode( ' · ', $soon ) ); ?></span>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php if ( 1 === $i && get_theme_mod( 'gamely_ad_home', '' ) ) : ?>
			<div class="g-wrap g-section"><?php gamely_ad( 'home' ); ?></div>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php
	$latest     = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 3 ) );
	$guides_url = get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : '';
	if ( $latest ) :
		?>
		<section class="g-wrap g-section" aria-labelledby="h-guides">
			<div class="g-section__head">
				<h2 id="h-guides"><?php esc_html_e( 'Latest guides', 'gamely' ); ?></h2>
				<?php if ( $guides_url ) : ?><a href="<?php echo esc_url( $guides_url ); ?>" style="font-weight:700"><?php esc_html_e( 'All guides →', 'gamely' ); ?></a><?php endif; ?>
			</div>
			<div class="g-posts">
				<?php foreach ( $latest as $latest_post ) : $GLOBALS['post'] = $latest_post; setup_postdata( $latest_post ); get_template_part( 'parts/guide-card' ); endforeach; wp_reset_postdata(); ?>
			</div>
		</section>
	<?php endif; ?>

	<section class="g-wrap g-section">
		<div class="g-how">
			<h2><?php esc_html_e( 'How our calculators work', 'gamely' ); ?></h2>
			<div class="g-how__grid">
				<div class="g-how__step"><span class="g-how__num">1</span><strong><?php esc_html_e( 'Measure what matters', 'gamely' ); ?></strong><span><?php esc_html_e( 'We ask for the few numbers that change the answer, like your soil type or sprinkler rate, and show you how to measure them.', 'gamely' ); ?></span></div>
				<div class="g-how__step"><span class="g-how__num">2</span><strong><?php esc_html_e( 'Get a real plan', 'gamely' ); ?></strong><span><?php esc_html_e( 'Not just one number: bags to buy, run times per zone, application schedules and costs.', 'gamely' ); ?></span></div>
				<div class="g-how__step"><span class="g-how__num">3</span><strong><?php esc_html_e( 'Check the math', 'gamely' ); ?></strong><span><?php esc_html_e( 'Every guide shows the formula and a worked example, with sources from university extension services.', 'gamely' ); ?></span></div>
			</div>
		</div>
	</section>
</main>
<?php
get_footer();
