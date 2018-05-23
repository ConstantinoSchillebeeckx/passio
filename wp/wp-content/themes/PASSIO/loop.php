<?php

// get BC video library data
$dat = get_db();

if (have_posts()): while (have_posts()) : the_post(); ?>
	<? $category_slug = get_the_category()[0]->slug; ?>

	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ($category_slug == 'masters-unplugged') { // info for master ?>

			<div class="row"><div class="col-sm-12">
				<img src="/wp/wp-content/uploads/susan-250x250.png" class="img-responsive center-block" style="width:250px;" alt="Responsive image"><br>
				<div class="text-center text-muted"><h4>Susan E. Mackinnon, MD</h4>
				<p>Shoenberg Professor, Division Chief<br>Plastic & Reconstructive Surgery<br>Washington University School of Medicine</p>
				<h3>PREFACE</h3></div>
				<div class="row"><div class="col-sm-10 col-sm-offset-1">
					<p>The chapters that will follow share what I consider to be the most important knowledge that I have on any particular topic. My oldest daughter Megan is an orthopedic hand surgeon. I wrote these chapters from my heart to her. Every time I would work on these chapters, I would first put myself in that intimate space to convey something that I care deeply about to someone who I love so much. I was very surprised to see how different this writing is. These chapters include my experience that I’ve learned combining clinical problems with answers generated in the laboratory over many decades. This is the type of information that a maser surgeon-scientist would generate, but combined with an intensely heartfelt message.</p>
				</div></div>
			</div></div><hr>

		<?php } ?>

		<div class="row" style="margin-top:20px; margin-bottom:20px;">

				<!-- post thumbnail -->
				<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
					<div class="col-sm-3"> <!-- thumb col -->
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php the_post_thumbnail(array(250,250), array( 'class' => 'img-rounded hidden-xs' )); // Declare pixel size you need inside the array ?>
						</a>
					</div> <!-- /col -->
				<?php endif; ?>
				<!-- /post thumbnail -->

			<div class=<?php echo has_post_thumbnail() ? '"col-sm-9"': '"col-sm-12"'; ?> > <!-- post info col -->

				<!-- post title -->
				<div class="row"><div class="col-sm-12">
				<h2 style="margin-top:0;">
					<i class="text-muted fa fa-chevron-right" aria-hidden="true"></i><a style="margin-left:6px;" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				</h2>
				</div></div>
				<!-- /post title -->

			<!-- post details -->
			<?php
				$id = get_post_meta($post->ID, "BC_ID_stand", true);
				if (!$id) { $id = get_post_meta($post->ID, "BC_ID_presentation", true); }


				if ($category_slug == 'masters-unplugged') { ?>
						<div class="row" style="margin-top:10px;"><div class="col-sm-12">
						<strong><?php the_author(); ?></strong>
						</div></div>
						<div class="row" style="margin-top:5px;"><div class="col-sm-12">
						<span class="fa fa-calendar fa-lg"></span><?php echo ' '; the_date(); ?>
						</div></div>
						<div class="row" style="margin-top:10px;"><div class="col-sm-12">
						<p><? the_excerpt(); ?></p>
						</div></div>
				<?php }

				if (isset($dat->$id)) {
					$post_dat = json_decode($dat->$id->longDescription);

					if ($post_dat) {
						?>
						<div class="row" style="margin-top:10px;"><div class="col-sm-12">
						<?php echo parse_author_dat($post_dat); ?>
						</div></div>
						<div class="row" style="margin-top:5px;"><div class="col-sm-12">
						<?php echo '<span class="fa fa-calendar fa-lg"></span> ' . get_the_time('F j, Y'); ?>
						</div></div>
						<div class="row" style="margin-top:10px;"><div class="col-sm-12">
						<p><?php echo PASSIOexcerpt($post_dat->Description, 300); ?></p>
						</div></div>
						<div class="row" style="margin-top:10px;">
							<div class="col-lg-2 col-lg-offset-10">
								<span class="pull-right">
								<?php echo get_simple_likes_button( get_the_ID() );?>
								<a href="<?php the_permalink(); ?>#discussion">
								<?php
									$comments = get_comments_number(get_the_ID());
									if ($comments) {
										echo sprintf('<i class="text-info fa fa-comments fa-2x"></i></a> %s', $comments);
									} else {
										echo sprintf('<i class="fa fa-comments fa-2x"></i></a> %s', $comments);
									}
								?>
							</span></div>
						</div>
					<?php }
				} else {
					//echo sprintf('<mark class="lead">No data found within BC_DB.json for video ID for %s!</mark>', $id);
				}
			 ?>
			<!-- /post details -->

			</div> <!-- /col -->
		</div><!-- /row -->

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
