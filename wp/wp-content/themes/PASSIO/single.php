<?php get_header();
$user_role = get_user_role(); // user must be logged in to view posts
$dat = get_db();
?>

	<main role="main" id="<?php echo 'post-' . get_the_ID(); ?>">
	<!-- section -->
	<section>

	<?php $category = get_the_category()[0]->slug; ?>
	<?php $login_cat = array('clinical-judgement', 'masters-unplugged'); // categories that require person to be logged in ?>
	<?php if (have_posts() && (isset($user_role) || !in_array($category, $login_cat) ) ): while (have_posts()) : the_post(); ?>

		<!-- article -->
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

			<!-- post title -->
			<h1>
				<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
				<?php echo get_simple_likes_button( get_the_ID() );?>
			</h1>
			<!-- /post title -->

			<?php  // Dynamic Content

				if ( post_password_required( $post ) ) { // password protected pages
					echo get_the_password_form( );
				} else {

					$ext_id = get_post_meta($post->ID, "BC_ID_ext", true);
					$exam_id = get_post_meta($post->ID, "BC_ID_exam", true);
					$stand_id = get_post_meta($post->ID, "BC_ID_stand", true);
					$pres_id = get_post_meta($post->ID, "BC_ID_presentation", true);
					$dressing_id = get_post_meta($post->ID, "BC_ID_dressing", true);
					$prezi_src = explode(',',get_post_meta($post->ID, "prezi_src", true));
					$ext_toc = get_post_meta($post->ID, "BC_TOC_ext", true);
					$stand_toc = get_post_meta($post->ID, "BC_TOC_stand", true);
					$exam_toc = get_post_meta($post->ID, "BC_TOC_exam", true);
					$pres_toc = get_post_meta($post->ID, "BC_TOC_presentation", true);
					$dressing_toc = get_post_meta($post->ID, "BC_TOC_dressing", true);

                    // tags for post
                    // login_all: all videos behind login
                    // login_extended: extended video (if available) behind login
                    $tags = wp_get_post_tags( $post->ID, array( 'fields' => 'slugs' ));

					if (isset($dat->$stand_id)) {
						publication_head($dat->$stand_id);
                        $tmp = json_decode(rtrim($dat->$stand_id->longDescription, "\0"));
					} else if (isset($dat->$pres_id)) {
						publication_head($dat->$pres_id);
                        $tmp = json_decode(rtrim($dat->$pres_id->longDescription, "\0"));
					}

                    // set the meta description, needed for SEO and video sitemap
                    $descrip = $tmp->Description;
                    ?>
                    <script> 
                        var descrip = <?php echo "\"" . $descrip . "\""; ?>

                        jQuery("meta[name='description']").attr("content", descrip);
                    </script>
                    <?php




                    if (!in_array("login_content", $tags) || isset($user_role)) {
                        the_content();
                    } else {
                        must_login(null, 'to view the full article and extended video on this page.');
                    }

                    if (!in_array("login_all", $tags) || isset($user_role)) {
                        if ($stand_id || $pres_id) { 
                        ?>
                        <p style="margin-top:30px;" class="lead"><u><?php echo ($stand_id ? 'Standard Edition' : 'Presentation'); ?></u></p>
                            <div style="display: block; position: relative; max-width: 100%;">
                                <div style="padding-top: 56.25%;">
                                    <video id="standard"
                                        data-account="4741948344001"
                                        data-player="VkErL2xqe"
                                        data-embed="default"
                                        <?php if (!is_mobile()) echo 'data-setup=\'{ "playbackRates": [0.5, 1, 1.25, 1.5, 2] }\''; ?>
                                        data-video-id=<?php echo ($stand_id ? $stand_id : $pres_id);?>
                                        class="video-js"
                                        controls
                                        style="width: 100%; height: 100%; position: absolute; top: 0px; bottom: 0px; right: 0px; left: 0px;">
                                    </video>
                            <script src="//players.brightcove.net/4741948344001/VkErL2xqe_default/index.min.js"></script>
                                   </div>
                                </div>
                            <ol class="vjs-playlist"></ol> 
                        <?php publication_toc(($stand_id ? $stand_toc : $pres_toc), 'standard');
                        }

                        if (!in_array("login_extended", $tags) || isset($user_role)) {
                            if ( $ext_id || $exam_id) {
                            ?>
                            <p style="margin-top:30px;" class="lead"><u><?php echo $ext_id ? 'Extended Edition' : 'Examination'; ?></u></p>
                                <div style="display: block; position: relative; max-width: 100%;">
                                    <div style="padding-top: 56.25%;">
                                        <video id="extended"
                                            data-account="4741948344001"
                                            data-player="VkErL2xqe"
                                            data-embed="default"
                                            <?php if (!is_mobile()) echo 'data-setup=\'{ "playbackRates": [0.5, 1, 1.25, 1.5, 2] }\''; ?>
                                            data-video-id=<?php echo $ext_id ? $ext_id : $exam_id ;?>
                                            class="video-js"
                                            controls
                                            style="width: 100%; height: 100%; position: absolute; top: 0px; bottom: 0px; right: 0px; left: 0px;">
                                        </video>
                                <script src="//players.brightcove.net/4741948344001/VkErL2xqe_default/index.min.js"></script>
                                       </div>
                                    </div>
                                <ol class="vjs-playlist"></ol>
                            <?php $ext_id ? publication_toc($ext_toc, 'extended') : publication_toc($exam_toc, 'extended');
                            }
                            
                            if ( $dressing_id) {
                                $info = json_decode($dat->$dressing_id->longDescription);
                                ?>
                                <p style="margin-top:30px;" class="lead"><u>Dressing Tutorial</u></p>
                                    <?php echo '<p class="description">' . $info->Description . '</p>'; ?>
                                    <div style="display: block; position: relative; max-width: 100%;">
                                        <div style="padding-top: 56.25%;">
                                            <video id="dressing"
                                                data-account="4741948344001"
                                                data-player="VkErL2xqe"
                                                data-embed="default"
                                                data-video-id=<?php echo $dressing_id ;?>
                                                class="video-js"
                                                controls
                                                style="width: 100%; height: 100%; position: absolute; top: 0px; bottom: 0px; right: 0px; left: 0px;">
                                            </video>
                                            <script src="//players.brightcove.net/4741948344001/VkErL2xqe_default/index.min.js"></script>
                                           </div>
                                        </div>
                                    <ol class="vjs-playlist"></ol>
                                <?php publication_toc($dressing_toc, 'dressing');
                                
                            }


                            if ($prezi_src[0] != '') {
                                echo '<div class="row"><div class="col-sm-12">';
                                echo '<p style="margin-top:30px;" class="lead"><u>Prezi</u></p>';

                                foreach ($prezi_src as $src) {
                                    echo '<div class="embed-responsive embed-responsive-16by9" style="margin-bottom:10px">';
                                    echo sprintf('<iframe class="embed-responsive-item" src="%s"></iframe>', $src);
                                    echo '</div>';
                                }
                                echo '</div></div>';
                            }

                            // echo HTML stored in footer custom field
                            echo get_post_meta( $post->ID, 'footer', true);
                        } else {
                            must_login(null, 'to view the full article and extended video on this page.');
                        }
                    } else {
                        must_login();
                    }
            }
			?>


			<?php edit_post_link(); // Always handy to have Edit Post Links available ?>

			<?php comments_template(); ?>

		</article>
		<!-- /article -->

	<?php endwhile; ?>

	<?php else: ?>

		<!-- article -->
		<article>
			<?php
				$id = (get_post_meta($post->ID, "BC_ID_stand", true) ? get_post_meta($post->ID, "BC_ID_stand", true) : get_post_meta($post->ID, "BC_ID_presentation", true));
				must_login($id ? $dat->$id : null);
			?>
		</article>
		<!-- /article -->

	<?php endif; ?>

	</section>
	<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
