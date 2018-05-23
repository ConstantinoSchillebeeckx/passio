<?php get_header(); ?>

	<main role="main" id="<?php echo 'page-' . get_the_ID(); ?>">
		<!-- section -->
		<section>

			<h1><?php is_front_page() ? null : the_title();  ?></h1>

		<?php if (have_posts()): while (have_posts()) : the_post(); ?>

			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php the_content(); ?>

				<?php comments_template( '', true ); // Remove if you don't want comments ?>

				<br class="clear">

				<?php edit_post_link(); ?>

			</article>
			<!-- /article -->

		<?php endwhile; ?>

		<?php else: ?>

			<!-- article -->
			<article>

				<h2><?php _e( 'Sorry, nothing to display.', 'PASSIO' ); ?></h2>

			</article>
			<!-- /article -->

		<?php endif; ?>

		</section>
		<!-- /section -->
	</main>

<? !is_page() ? get_sidebar() : NULL; ?>

<?php get_footer(); ?>
