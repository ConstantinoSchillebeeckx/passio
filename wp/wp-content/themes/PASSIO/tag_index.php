<?php /* Template Name: Tag Index Template */ get_header(); ?>

    <?php $dat = get_db(); ?>

    <?php $index = get_tag_index($dat); // key: tag, value: num times tag used
        $index_case = check_keyword_case(array_keys($index)); // key: 0,1,2,3; value: tag with proper capitalization (same sorted order as $index)
    ?>

	<main role="main">
		<!-- section -->
		<section>

			<h1><?php the_title(); ?></h1>

		<?php if (have_posts()): while (have_posts()) : the_post(); ?>

			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                <div class="row">
                    <?php 
                    $count = 0;
                    $div = intval(count($index) / 3);
                    foreach ($index as $tag => $num) {
                        if ($count % $div == 0) {
                            if ($count > 0) {
                                echo "</ul>";
                                echo "</div>";
                            }
                            echo "<div class='col-xs-6 col-sm-3'>";
                            echo "<ul>";
                        }
                        $tag = $index_case[$count];
                        echo sprintf("<li><a style='text-decoration:none' href='%s/?s=+&tag=%s'>%s (%s)</a></li>", get_home_url(), urlencode($tag), $tag, $num);

                        $count += 1;
                    } ?>
                    </ul>
                    </div>
                </div>

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


<?php get_footer(); ?>
