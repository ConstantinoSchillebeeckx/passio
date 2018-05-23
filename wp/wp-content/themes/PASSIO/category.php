<?php get_header(); ?>

	<main role="main" id="<?php echo 'cat-'. get_the_category()[0]->cat_ID; ?>">

		<!-- section -->
		<section>

			<h1></i><?php single_cat_title(); ?></h1>

			<?php get_template_part('loop'); ?>

			<?php get_template_part('pagination'); ?>

		</section>
		<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>