<?php /* Template Name: Download Data Template */ get_header(); ?>

	<main role="main">
		<!-- section -->
		<section>

			<h1><?php the_title(); ?></h1>


			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


                <?php 
                    $current_user = wp_get_current_user();
                    if (user_can( $current_user, 'administrator' )) { // if admin
                ?>

                <p class="lead"><span id="spin"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i></span> The PASSIO database is currently being downloaded from Brightcove ... </p>
                <hr>
                <p id="response"></p>

                <script>
                    jQuery(document).ready(function(){ doAJAX({"action": "downloadBrightcove"}) }) 
                </script>

                <?php } else {
                    must_login($id ? $dat->$id : null);
                }
                ?>




			</article>
			<!-- /article -->


		</section>
		<!-- /section -->
	</main>

<?php get_footer(); ?>
