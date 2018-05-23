<?php get_header(); ?>

	<main role="main">
		<!-- section -->
		<section>

			<!-- article -->
			<article id="post-404">

				<h1><?php _e( "Dang! Looks like you're lost", 'PASSIO' ); ?></h1>
				<p class="lead">
					You can try searching  from the navigation bar or simply <a href="<?php echo home_url(); ?>"><?php _e( 'returning home', 'PASSIO' ); ?></a>.</p>
				<p class="lead">Think we can improve something?  Please let us know by <a href="mailto:<?php echo CONTACT; ?>?Subject=PASSIO%20improvements">emailing us</a>!
				</p>

			</article>
			<!-- /article -->

		</section>
		<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>