<?php get_header(); ?>

	<main role="main">
		<!-- section -->
		<section>

			<?php passio_search(); // search json file as well ?>

			<h1><?php 
				echo sprintf( __( '%s Search Results for ', 'PASSIO' ), $wp_query->found_posts ); 
				$tag_search = get_query_var('tag', false);
				if ($tag_search) {
					$tag = str_replace('\\', '', urldecode($wp_query->query_vars['tag']));
					echo "keyword <span class='keyword'>$tag</span>";
				} else {
					echo get_search_query(); 
				}
			?></h1>

			<?php get_template_part('loop'); ?>

			<?php //get_template_part('pagination'); ?>

		</section>
		<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>



