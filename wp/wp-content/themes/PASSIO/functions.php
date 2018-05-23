<?php
define("CONTACT", "info@passioeducation.com");

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

function is_mobile() {

    require_once 'Mobile_Detect.php'; // http://mobiledetect.net/
    $detect = new Mobile_Detect;

    if ( $detect->isMobile() || stristr($_SERVER['HTTP_USER_AGENT'], 'iPad') ) {
        return true;
    }
    return false;
}




if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Add Support for Custom Backgrounds - Uncomment below if you're going to use
    /*add_theme_support('custom-background', array(
	'default-color' => 'FFF',
	'default-image' => get_template_directory_uri() . '/img/bg.jpg'
    ));*/

    // Add Support for Custom Header - Uncomment below if you're going to use
    /*add_theme_support('custom-header', array(
	'default-image'			=> get_template_directory_uri() . '/img/headers/default.jpg',
	'header-text'			=> false,
	'default-text-color'		=> '000',
	'width'				=> 1000,
	'height'			=> 198,
	'random-default'		=> false,
	'wp-head-callback'		=> $wphead_cb,
	'admin-head-callback'		=> $adminhead_cb,
	'admin-preview-callback'	=> $adminpreview_cb
    ));*/

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('PASSIO', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

class bootstrap_navbar_walker extends Walker_Nav_Menu
{
    /**
     * start_lvl
     *
     * @param  string  $output HTML output.
     * @param  integer $depth  Menus total depth.
     * @param  array   $args   Parameters.
     */
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
        // Class name dropdown-menu is Bootstrap, sub-menu is WordPress.
        $output .= "\n<ul class=\"dropdown-menu sub-menu\">\n";
    }
    /**
     * start_el
     *
     * @param  string  $output  HTML output.
     * @param  object  $item    Current menu item.
     * @param  integer $depth   Current items depth.
     * @param  array   $args    Parameters.
     */
    public function start_el(&$output, $item, $depth = 0, $args = array())
    {
        // WP classes.
        $classes   = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Join and escape class names.
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? esc_attr($class_names) : '';
        // Apply and escape id
        $id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
        $id = $id ? 'id="' . esc_attr($id) . '"' : '';
        // Anchor attributes
        $atts = array(
            'title'  => !empty($item->attr_title) ? $item->attr_title : '',
            'target' => !empty($item->target)     ? $item->target     : '',
            'rel'    => !empty($item->xfn)        ? $item->xfn        : '',
            'href'   => !empty($item->url)        ? $item->url        : '',
        );

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= "$attr=\"$value\" ";
            }
        }
        // Check if title is divider, header or other menu item.
        if ($item->attr_title == 'divider') {
            $output .= "<li $id class=\"divider\">";
        } elseif ($item->attr_title == 'header') {
            $output .= "<li $id class=\"dropdown-header\">{$args->link_before}{$item->title}{$args->link_after}";
        } else {
            // If menu item has children add Bootstrap dropdown functions.
            if(in_array('menu-item-has-children', $classes)) {
                $output .= "<li $id class=\"dropdown $class_names\">"
                        .  "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">{$args->link_before}{$item->title}{$args->link_after} <b class=\"caret\"></b></a>";
            } else {
                $output .= "<li $id class=\"$class_names\">"
                        .  "<a $attributes>{$args->link_before}{$item->title}{$args->link_after}</a>";
            }
        }
    }
}


// HTML5 Blank navigation
function PASSIO_nav()
{
	$menu = '<div class="collapse navbar-collapse" id="nav-collapse"><ul class="nav navbar-nav navbar-right">';
	$menu .= wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => false,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '%3$s',
		'depth'           => 0,
		'walker' => new bootstrap_navbar_walker()
		)
	);

	// if user is logged in, show a 'log out' menu; otherwise a 'log in' menu
	if (is_user_logged_in()) {
		$menu .= sprintf("<li><a href='%s'>Logout</a></li>", wp_logout_url());
	} else {
		if (strpos($_SERVER['REQUEST_URI'],'category') !== false) { // if on the category/publications page, redirect to there instead of a post/page
			$redirect_link = get_category_link( get_cat_ID( 'publications' ));
		} else {
			$redirect_link = get_permalink();
		}
		$menu .= sprintf("<li><a href='/wp/wp-login.php?redirect_to=%s'>Login</a></li>", $redirect_link);
	}
	echo $menu;
	get_template_part('searchform');
}

// Load HTML5 Blank scripts (header.php)
function PASSIO_header_scripts()
{

    wp_register_script('conditionizr', get_template_directory_uri() . '/js/lib/conditionizr-4.3.0.min.js', array(), '4.3.0'); // Conditionizr
    wp_enqueue_script('conditionizr'); // Enqueue it!

    wp_register_script('modernizr', get_template_directory_uri() . '/js/lib/modernizr-2.7.1.min.js', array(), '2.7.1'); // Modernizr
    wp_enqueue_script('modernizr'); // Enqueue it!

    wp_register_script('passio_js', get_template_directory_uri() . '/js/passio.js', array('jquery'), '2');
    wp_enqueue_script('passio_js'); // Enqueue it!
    wp_localize_script( 'passio_js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

}

// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'PASSIO'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'PASSIO'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'PASSIO') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'PASSIO'),
        'description' => __('Description for this widget-area...', 'PASSIO'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'PASSIO'),
        'description' => __('Description for this widget-area...', 'PASSIO'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    // echo str_replace($big, '%#%', get_pagenum_link($big));
    // echo max(1, get_query_var('paged'));
    $big = 999999999;
    // echo paginate_links(array(
        // 'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        // 'format' => '?paged=%#%',
        // 'current' => max(1, get_query_var('paged')),
        // 'total' => $wp_query->max_num_pages
    // ));
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'PASSIO') . '</a>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function PASSIOgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// given a Username, function will return
// a string comprised of first and last
// name
function passio_user_name_to_name($user) {
	if (strpos($user, '@') !== false) {
		$user = get_user_by( 'login', $user );
		echo $user->first_name . ' ' . $user->last_name;
	} else {
		echo $user;
	}

}


// awesome semantic comment
function better_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'article' == $args['style'] ) {
		$tag = 'article';
		$add_below = 'comment';
	} else {
		$tag = 'article';
		$add_below = 'comment';
	}

	?>
	<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' :'parent') ?> id="comment-<?php comment_ID() ?>">
		<div class="row"> <!-- comment row -->
		<div class="col-sm-12">
			<div class="row comment-row">
				<div class="col-sm-1">
					<figure class="gravatar"><?php echo get_avatar( $comment, 65, 'mystery','Author’s gravatar', array( 'class' => array('img-rounded', 'img-responsive')) ); ?></figure>
				</div>
				<div class="col-sm-11 comment-col">
					<div class="comment-meta post-meta" role="complementary">
						<h5>
							<?php //if (get_user_role() == 'administrator') { echo "<i class='fa fa-star star'></i>"; } ?>
							<a class="comment-author-link" href="<?php comment_author_url(); ?>" itemprop="author"><?php passio_user_name_to_name(get_comment_author()); ?></a>
						</h5>
						<time class="comment-meta-item" datetime="<?php comment_date('Y-m-d') ?>" itemprop="datePublished"><?php comment_date('jS F Y') ?></time>

						<?php if ($comment->comment_approved == '0') : ?>
						<p class="comment-meta-item">Your comment is awaiting moderation.</p>
						<?php endif; ?>
					</div>

					<div class="comment-content post-content" itemprop="text">
						<?php comment_text() ?>
						<div class="comment-reply">
							<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?> | <?php edit_comment_link('Edit this comment','',''); ?>
						</div>

					</div>
				</div>
			</div>
<hr>
	<?php }

// end of awesome semantic comment

function better_comment_close() {
	echo '</div> <!-- /col --> </div> <!-- /row --> </article>';
}


/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'PASSIO_header_scripts'); // Add Custom Scripts to wp_head
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
//add_action('init', 'create_post_type_html5'); // Add our HTML5 Blank Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination
add_action( 'register_form', 'passio_register_form' );
add_action( 'user_register', 'passio_user_register' );

// super easy way to move javascript to footer
remove_action('wp_head', 'wp_print_scripts');
remove_action('wp_head', 'wp_print_head_scripts', 9);
remove_action('wp_head', 'wp_enqueue_scripts', 1);
add_action('wp_footer', 'wp_print_scripts', 5);
add_action('wp_footer', 'wp_enqueue_scripts', 5);
add_action('wp_footer', 'wp_print_head_scripts', 5);


// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'PASSIOgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images
add_filter( 'registration_errors', 'passio_registration_errors', 10, 3 );

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether
remove_filter( 'the_content', 'wpautop' );








































// ================================
// PASSIO video/page functions
// ================================



// BrightCove can't guarantee the case of keywords
// so we assume all are lower case except for a short
// list (CAP_TAGS) which require the given case
// this checks an array of keywords and returns it
// with the proper capitalization
function check_keyword_case($keywords) {

    $cap_tags = explode(',', CAP_TAGS); // list of tags with proper capitalization (brightcove can't guarantee case)
    $cap_tags_lower = array_map('strtolower', $cap_tags);

    foreach ($keywords as $i => $tag) {
        if (in_array(strtolower($tag), $cap_tags_lower)) {
            $ix = array_search(strtolower($tag), $cap_tags_lower);
            $tag = $cap_tags[$ix];
        } else {
            $tag = strtolower($tag);
        }
        $keywords[$i] = $tag;
    }
    return $keywords;
}



// display publication title and tags
// requires video object
// will do all the proper echo-ing
function publication_head($query) {


    // display title & tags
    $post_dat = json_decode(rtrim($query->longDescription, "\0"));
    echo '<p>' . parse_author_dat($post_dat) . '</p>';
    echo '<p><i class="fa fa-calendar fa-lg"></i> ' . get_the_time('F j, Y') . '</p>';
	if ( isset($post_dat->Disclosure) && $post_dat->Disclosure != '' ) {
	    echo sprintf('<p class="text-muted"><strong>Disclosure</strong>: %s</p>', $post_dat->Disclosure);
	} else {
	    echo '<p class="text-muted"><strong>Disclosure</strong>: No authors have a financial interest in any of the products, devices, or drugs mentioned in this production or publication.</p>';

	}
    echo sprintf('<p>%s</p>', $post_dat->Description);

    // remove excluded tags
    $display_tags = array();
    $exclude_tags = explode(',', EXCLUDE_TAGS);
    foreach ($query->tags as $tag) {
        if (!in_array($tag, $exclude_tags)) {
            $display_tags[] = $tag;
        }
    }
    if (count($display_tags)) {
        echo '<p class="keywords"><b>Keywords: </b>';

        $display_tags = check_keyword_case($display_tags);
        foreach ($display_tags as $tag) {
            echo sprintf('<span class="keyword"><a href="%s/?s=+&tag=%s">%s</a></span>', get_home_url(), urlencode($tag), $tag);
        }
        echo '</p>';
    }

}

// takes time and returns total seconds
// http://stackoverflow.com/a/4834230/1153897
function time_code_to_s($time_code) {
    sscanf($time_code, "%d:%d:%d", $hours, $minutes, $seconds);

    return isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;
}

// display table of contents for publication video
// requires object for video
// will do all the proper echo-ing
function publication_toc($url, $type) {
	echo sprintf('<div class="row toc"><div class="col-sm-12" id="toc-%s">', $type);
    $times = array();
	if (!empty($url)) {
		$toc = parse_vtt($url);
		echo print_toc($toc, $type);

		// create an array of time codes to be used with highlighting function
		foreach ($toc as $time => $val) {
			$times[] = time_code_to_s($time);
		}
    }
    echo '</div></div>';

    // script to automatically check playback position
    // to highlight current chapter
    if ($type == 'standard') {
    ?>
    <script>
	videojs('standard').ready(function() {
		var standardPlayer = this;
		standardPlayer.on('timeupdate', function(evt) {
			time = Math.round(standardPlayer.currentTime());
			highlightChapter(time, 'standard', <?php echo json_encode(array_reverse($times)); ?>);
		})
	})
    </script>
    <?php
    } else if ($type == 'extended') {
    ?>
    <script>
	videojs('extended').ready(function() {
		var extendedPlayer = this;
		extendedPlayer.on('timeupdate', function(evt) {
			time = Math.round(extendedPlayer.currentTime());
			highlightChapter(time, 'extended', <?php echo json_encode(array_reverse($times)); ?>);
		})
	})
    </script>
    <?php
    } else if ($type == 'dressing') {
    ?>
    <script>
	videojs('dressing').ready(function() {
		var extendedPlayer = this;
		extendedPlayer.on('timeupdate', function(evt) {
			time = Math.round(extendedPlayer.currentTime());
			highlightChapter(time, 'dressing', <?php echo json_encode(array_reverse($times)); ?>);
		})
	})
    </script>
    <?php
    }


}


// generate html out of parsed vtt file
// requires output of parse_vtt()
// type {string} standard or extended
// returns valid html
function print_toc($vtt, $type) {

    $frus_icon = '<i style="margin-right:5px" class="fa fa-bolt" aria-hidden="true" title="Frustration"></i>';
    $slow_icon = '<i style="margin-right:5px" class="fa fa-clock-o" aria-hidden="true" title="Slow down"></i>';

    $html = '<h4>Table of Contents</h4>';
    if (has_category('surgical-procedures')) {
        $html .= '<p>';
        $html .= '<span style="margin-right:10px">' . $slow_icon . '- <u><a style="color:#444" href="slow-down/">Slow down</a></u></span>';
        $html .= '<span>' . $frus_icon . '- <u><a style="color:#444" href="surgical-frustration/">Frustration</a></u></span>';
        $html .= '</p>';
    }
    foreach ($vtt as $time => $val) {
        
        $icon = '';
        if (strpos($val, '<c.frus>') !== false) {
            $icon .= $frus_icon;
        }
        if (strpos($val, '<c.slow>') !== false) {
            $icon .= $slow_icon;
        }
        $val = str_replace('<c.frus>','',$val);
        $val = str_replace('<c.slow>','',$val);
        $val = str_replace('</c>','',$val);

        $html .= sprintf('<div id="toc-%s-%s"><a href="#" onclick="seek(this, %s, %s); return false;"><b><u>%s</u></b><span style="color:rgb(68,68,68)"> - %s %s</span></a></div>', $type, time_code_to_s($time), time_code_to_s($time), $type, $time, $icon, $val);
    }

    return $html;

}


// parses a vtt file into a class
// keys will be start time
// value will be title
function parse_vtt($url) {
    $in = file($url, FILE_SKIP_EMPTY_LINES);

    $dat = array();
    $key = '';
    foreach ($in as $line) {
        if (strpos($line, 'WEBVTT') === false && trim($line) != '') { // skip header
            if (strpos($line, '-->') !== false) {
                $key = explode('.000', trim($line))[0];
            } else {
                $dat[$key] = trim($line);
            }
        }
    }
    return $dat;

}

// parses author data into proper html (surrounded by <p>)
// expects json of form { "Authors" : {"1": "xxx", etc}, "Author Information": {"1": "YYY", etc} }
function parse_author_dat($dat) {

    $auths = array();
    if ($dat->Authors) {
	    foreach ($dat->Authors as $key => $val) {
		if (is_array($val)) {
			$clean = '';
			foreach ($val as $person) {
				$clean .= sprintf(' <b><span rel="tooltip" data-toggle="tooltip" title data-original-title="%s">%s<sup>%s</sup></span></b>', $dat->{'Author Information'}->$key, $person, $key);
			}
			$auths[] = $clean;
		} else {
			$auths[] = sprintf('<b><span rel="tooltip" data-toggle="tooltip" data-placement="top" title data-original-title="%s">%s<sup>%s</sup></span></b>', $dat->{'Author Information'}->$key, $val, $key);
		}
	    }
	    return implode(', ', $auths);
    }
}

// response message for user needing to login
function must_login($query = null, $msg = 'to view the full article and videos on this page.') {

	if ($query) {
		$post_dat = json_decode($query->longDescription);

		echo sprintf('<h1>%s</h1>', $post_dat->Title);
		echo '<p>' . parse_author_dat($post_dat) . '</p>';
		echo '<p><i class="fa fa-calendar fa-lg"></i> ' . get_the_time('F j, Y') . '</p>';
		echo '<p>' . $post_dat->Description . '</p>';
	}

	echo sprintf('<br><div class="alert alert-warning" role="alert"><p class="lead"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> You must <a href="/wp/wp-login.php?redirect_to=%s">login</a> %s</p></div>', get_permalink(), $msg);
}

// given a string of text, will return it
// as excerpt form (truncated to length)
// length = max count of characters to return
function PASSIOexcerpt($text, $length = 800, $read_more=true) {
	$url = sprintf('<b><a href="%s">[Read more]</a></b>', get_permalink());
	if (strlen($text) > $length) {
		if ($read_more) {
			return substr($text, 0, $length) . '... ' . $url;
		} else {
			return substr($text, 0, $length) . '... ';
		}
	} else {
		return $text;
	}
}

// ================================
// PASSIO video/page functions
// ================================








































// ================================
// wordpress customization
// ================================

/**
 * Redirect non-admin users to home page
 *
 * This function is attached to the 'admin_init' action hook.
 */
function redirect_non_admin_users() {
    global $required_capability, $redirect_to;      
    // Is this the admin interface?
    if (
        // Look for the presence of /wp-admin/ in the url
        stripos($_SERVER['REQUEST_URI'],'/wp-admin/') !== false
        &&
        // Allow calls to async-upload.php
        stripos($_SERVER['REQUEST_URI'],'async-upload.php') == false
        &&
        // Allow calls to admin-ajax.php
        stripos($_SERVER['REQUEST_URI'],'admin-ajax.php') == false
    ) {         
        // Does the current user fail the required capability level?
        if (!current_user_can('manage_options')) {              
            if ($redirect_to == '') { $redirect_to = get_option('home'); }              
            // Send a temporary redirect
            wp_redirect($redirect_to,302);              
        }           
    }       
}
add_action('init','redirect_non_admin_users',0);





// custom logo at login
function passio_login_logo() { ?>
    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/play.svg) !important;
            padding-bottom: 30px !important;
        }
	.register { /* this removes the "register for site" message */
	    visibility:hidden; height:0px; padding-top:0px !important; padding-bottom:0px !important;
	}
    </style>
<?php }
add_action( 'login_enqueue_scripts', 'passio_login_logo' );

add_filter( 'login_headerurl', 'custom_loginlogo_url' );
function custom_loginlogo_url($url) {
    return 'http://www.passioeducation.com';
}




// returns a string with the current logged in user role
function get_user_role() {
	global $current_user;
	return $current_user->roles[0];
}

// remove the # of posts column from the users table
// remove username column from users table
// add various other columns
add_action('manage_users_columns','remove_user_posts_column');
function remove_user_posts_column($column_headers) {
	unset($column_headers['posts']);
	unset($column_headers['email']);
	$column_headers['title'] = 'Title';
	$column_headers['location'] = 'Location';
	$column_headers['work'] = 'Work';
	$column_headers['specialty'] = 'Specialty';
	$column_headers['user_type'] = 'User Type';
	$column_headers['sign_up'] = 'Registered';
	$column_headers['last_login'] = 'Last login';
	return $column_headers;
}

// display data for custom columns
add_action('manage_users_custom_column',  'passio_show_user_id_column_content', 10, 3);
function passio_show_user_id_column_content($value, $column_name, $user_id) {
    $user = get_userdata( $user_id );
	if ( 'title' == $column_name ) {
		return $user->title;
	} else if ( 'location' == $column_name ) {
		return $user->location;
	} else if ( 'work' == $column_name ) {
		return $user->work;
	} else if ( 'specialty' == $column_name ) {
		return $user->specialty;
	} else if ( 'user_type' == $column_name ) {
		return $user->user_type;
	} else if ( 'sign_up' == $column_name ) {
		if (intval($user->sign_up) > 0) {
			$date = date('m/d/Y', intval($user->sign_up)-3600*5);
		}
		return $date;
	} else if ( 'last_login' == $column_name ) {
		if (intval($user->last_login) > 0) {
			$date = date('m/d/Y H:i:s', intval($user->last_login)-3600*5);
		}
		return $date;
	}
    return $value;
}

// sort user.php table by custom field
function user_sortable_columns( $columns ) {
	$columns['user_type'] = 'user_type';
	$columns['title'] = 'title';
	$columns['location'] = 'location';
	$columns['work'] = 'work';
	$columns['specialty'] = 'specialty';
	$columns['sign_up'] = 'sign_up';
	$columns['last_login'] = 'last_login';
	return $columns;
}
add_filter( 'manage_users_sortable_columns', 'user_sortable_columns' );

function user_column_orderby( $vars ) {
	if ( isset( $vars['orderby'] ) && 'user_type' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'user_type',
			'orderby' => 'meta_value',
			'order'     => 'asc'
		) );
	}
	if ( isset( $vars['orderby'] ) && 'title' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'title',
			'orderby' => 'meta_value',
			'order'     => 'asc'
		) );
	}
	if ( isset( $vars['orderby'] ) && 'location' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'location',
			'orderby' => 'meta_value',
			'order'     => 'asc'
		) );
	}
	if ( isset( $vars['orderby'] ) && 'work' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'work',
			'orderby' => 'meta_value',
			'order'     => 'asc'
		) );
	}
	if ( isset( $vars['orderby'] ) && 'specialty' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'specialty',
			'orderby' => 'meta_value',
			'order'     => 'asc'
		) );
	}
	if ( isset( $vars['orderby'] ) && 'sign_up' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'sign_up',
			'orderby' => 'meta_value',
			'order'     => 'asc'
		) );
	}
	if ( isset( $vars['orderby'] ) && 'last_login' == $vars['orderby'] ) {
		$vars = array_merge( $vars, array(
			'meta_key' => 'last_login',
			'orderby' => 'meta_value',
			'order'     => 'asc'
		) );
	}
	return $vars;
}
add_filter( 'request', 'user_column_orderby' );

// hide the default username and email
// from registration form
add_action('login_head', function(){
?>
    <style>
        #registerform > p:first-child{
            display:none;
        }
        #registerform > p:nth-child(2){
            display:none;
        }
    </style>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#registerform > p:first-child').css('display', 'none');
	    $('#registerform > p:nth-child(2)').css('display', 'none');
        });
    </script>
<?php
});



//Remove error for username, only show error for email only.
add_filter('registration_errors', function($wp_error, $sanitized_user_login, $user_email){
    if(isset($wp_error->errors['empty_username'])){
        unset($wp_error->errors['empty_username']);
    }

    if(isset($wp_error->errors['username_exists'])){
        unset($wp_error->errors['username_exists']);
    }
    return $wp_error;
}, 10, 3);

// we are hiding the default username and email
// in the registration form - these will be set
// by the custom email address field
add_action('login_form_register', function(){
    if(isset($_POST['email']) && !empty($_POST['email'])){
        $_POST['user_login'] = $_POST['email'];
	$_POST['user_email'] = $_POST['email'];
    }
});



// add custom fields to admin page
function add_custom_user_profile_fields( $user ) {
?>
	<h3><?php _e('Profile Information', 'your_textdomain'); ?></h3>

	<table class="form-table">
		<tr>
			<th>
				<label for="title"><?php _e('Title', 'your_textdomain'); ?></label>
			</th>
			<td>
				<?php $type = get_the_author_meta( 'title', $user->ID );
				$types = ["Dr","Mr","Mrs","Ms"];
				echo '<select id="title" name="title">';
				foreach ($types as $val) {
					$selected = '';
					if ($val == $type) {
						$selected = 'selected';
					}
					echo sprintf('<option %s value="%s">%s</option>', $selected, $val, $val);
				}
				echo '</select><br>';
				?>
				<span class="description"><?php _e('Please enter your title.', 'your_textdomain'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="work"><?php _e('Institution/Company', 'your_textdomain'); ?></label>
			</th>
			<td>
				<input type="text" name="work" id="work" value="<?php echo esc_attr( get_the_author_meta( 'work', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter your Institution/Company.', 'your_textdomain'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="specialty"><?php _e('Surgical specialty', 'your_textdomain'); ?></label>
			</th>
			<td>
				<input type="text" name="specialty" id="specialty" value="<?php echo esc_attr( get_the_author_meta( 'specialty', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter your specialty.', 'your_textdomain'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="user_type"><?php _e('User type', 'your_textdomain'); ?></label>
			</th>
			<td>
				<?php $type = get_the_author_meta( 'user_type', $user->ID );
				$types = ["Attending Surgeon", "Resident/Fellow", "Medical Student", "Physician", "Medical Professional", "Industry", "Patient", "Other"];
				echo '<select id="user_type" name="user_type">';
				echo '<option selected disabled hidden style="display: none" value=""></option>';
				foreach ($types as $key => $val) {
					$selected = '';
					if ($val == $type) {
						$selected = 'selected';
					}
					echo sprintf('<option %s value="%s">%s</option>', $selected, $val, $val);
				}
				echo '</select><br>';
				?>
				<span class="description"><?php _e('Please enter your user type.', 'your_textdomain'); ?></span>
			</td>
		</tr>
		<tr>
			<th>
				<label for="location"><?php _e('Location', 'your_textdomain'); ?></label>
			</th>
			<td>
				<input type="text" name="location" id="location" value="<?php echo esc_attr( get_the_author_meta( 'location', $user->ID ) ); ?>" class="regular-text" /><br />
				<span class="description"><?php _e('Please enter your location.', 'your_textdomain'); ?></span>
			</td>
		</tr>
	</table>
<?php }

// save custom fields when updating on admin page
function save_custom_user_profile_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return FALSE;

	update_user_meta( $user_id, 'title', $_POST['title'] );
	update_user_meta( $user_id, 'work', $_POST['work'] );
	update_user_meta( $user_id, 'specialty', $_POST['specialty'] );
	update_user_meta( $user_id, 'user_type', $_POST['user_type'] );
	update_user_meta( $user_id, 'location', $_POST['location'] );
}

add_action( 'show_user_profile', 'add_custom_user_profile_fields' );
add_action( 'edit_user_profile', 'add_custom_user_profile_fields' );

add_action( 'personal_options_update', 'save_custom_user_profile_fields' );
add_action( 'edit_user_profile_update', 'save_custom_user_profile_fields' );




// add custom fields to registration form
function passio_register_form() {

    $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
    $last_name = ( ! empty( $_POST['last_name'] ) ) ? trim( $_POST['last_name'] ) : '';
    $email = ( ! empty( $_POST['user_email'] ) ) ? trim( $_POST['user_email'] ) : '';
    $work = ( ! empty( $_POST['work'] ) ) ? trim( $_POST['work'] ) : '';
    $specialty = ( ! empty( $_POST['specialty'] ) ) ? trim( $_POST['specialty'] ) : '';

        ?>
      <p>
            <label for="title"><?php _e( 'Title*', 'mydomain' ) ?><br />
                <select id="title" name="title" style="margin-bottom:20px;">
                        <option selected disabled hidden style='display: none' value=''></option>
                        <option value="Dr">Dr</option>
                        <option value="Mr">Mr</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Ms">Ms</option>
                </select></label>
        </p>
        <p>
            <label for="first_name"><?php _e( 'First Name*', 'mydomain' ) ?><br />
                <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( wp_unslash( $first_name ) ); ?>" size="25" /></label>
        </p>
        <p>
            <label for="last_name"><?php _e( 'Last Name*', 'mydomain' ) ?><br />
                <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( wp_unslash( $last_name ) ); ?>" size="25" /></label>
        </p>
        <p>
                <label for="email"><?php _e('Email*') ?><br />
                <input type="email" name="email" id="email" class="input" value="<?php echo esc_attr( wp_unslash( $email ) ); ?>" size="25" /></label>
        </p>
      <p>
            <label for="user_type"><?php _e( 'User type*', 'mydomain' ) ?><br />
                <select id="user_type" name="user_type" style="margin-bottom:20px;">
                        <option selected disabled hidden style='display: none' value=''></option>
                        <option value="Attending Surgeon">Attending Surgeon</option>
                        <option value="Resident/Fellow">Resident/Fellow</option>
                        <option value="Medical Student">Medical Student</option>
                        <option value="Physician">Physician</option>
                        <option value="Medical Professional">Medical Professional</option>
                        <option value="Industry">Industry</option>
                        <option value="Patient">Patient</option>
                        <option value="Other">Other</option>
                </select></label>
        </p>
        <p>
            <label for="work"><?php _e( 'Institution/Company*', 'mydomain' ) ?><br />
                <input type="text" name="work" id="work" class="input" value="<?php echo esc_attr( wp_unslash( $work ) ); ?>" size="25" /></label>
        </p>
        <p>
            <label for="specialty"><?php _e( 'Surgical Specialty', 'mydomain' ) ?><br />
                <input type="text" name="specialty" id="specialty" class="input" value="<?php echo esc_attr( wp_unslash( $specialty ) ); ?>" size="25" /></label>
        </p>
        <?php
    }



// ensure all required fields have something in them
function passio_registration_errors( $errors, $sanitized_user_login, $user_email ) {

        if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
            $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: Please type your first name.', 'mydomain' ) );
        }
        if ( empty( $_POST['last_name'] ) || ! empty( $_POST['last_name'] ) && trim( $_POST['last_name'] ) == '' ) {
            $errors->add( 'last_name_error', __( '<strong>ERROR</strong>: Please type your last name.', 'mydomain' ) );
        }
        if ( empty( $_POST['work'] ) || ! empty( $_POST['work'] ) && trim( $_POST['work'] ) == '' ) {
            $errors->add( 'work_error', __( '<strong>ERROR</strong>: Please type your institution or company.', 'mydomain' ) );
        }
        if ( empty( $_POST['title'] ) || ! empty( $_POST['title'] ) && trim( $_POST['title'] ) == '' ) {
            $errors->add( 'title_error', __( '<strong>ERROR</strong>: Please type your title.', 'mydomain' ) );
        }
        if ( empty( $_POST['user_type'] ) || ! empty( $_POST['user_type'] ) && trim( $_POST['user_type'] ) == '' ) {
            $errors->add( 'title_error', __( '<strong>ERROR</strong>: Please enter your user type.', 'mydomain' ) );
        }

        return $errors;
}

// update user metadata with supplied information
function passio_user_register( $user_id ) {
	if ( ! empty( $_POST['first_name'] ) ) {
            update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
        }
	if ( ! empty( $_POST['last_name'] ) ) {
            update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) );
        }
	if ( ! empty( $_POST['work'] ) ) {
            update_user_meta( $user_id, 'work', trim( $_POST['work'] ) );
        }
	if ( ! empty( $_POST['specialty'] ) ) {
            update_user_meta( $user_id, 'specialty', trim( $_POST['specialty'] ) );
        }
	if ( ! empty( $_POST['title'] ) ) {
            update_user_meta( $user_id, 'title', trim( $_POST['title'] ) );
        }
	if ( ! empty( $_POST['user_type'] ) ) {
            update_user_meta( $user_id, 'user_type', trim( $_POST['user_type'] ) );
        }

	// store IPs http://stackoverflow.com/a/3003233/1153897
	$remote = getenv('REMOTE_ADDR');
	$forwarded = getenv('HTTP_X_FORWARDED_FOR');
	update_user_meta( $user_id, 'remote_ip', $remote);
	update_user_meta( $user_id, 'forwarded_ip', $forwarded);
	update_user_meta( $user_id, 'sign_up', time() );
	update_user_meta( $user_id, 'last_login', time() );

	$loc = 'unknown';
	# give preference to forwarded ip, then remote
	if ($forwarded != '') {
		$url = sprintf('http://api.ipinfodb.com/v3/ip-city/?key=28304b361ee03e7140519d07ff0af5d1ae2bdf1908e54ed78630368e6f814c0f&ip=%s', $forwarded);
		$loc_arr = explode(';', file_get_contents($url));
		$loc = sprintf('%s, %s, %s', $loc_arr[6], $loc_arr[5], $loc_arr[4]);
	} else if ($remote != '') {
		$url = sprintf('http://api.ipinfodb.com/v3/ip-city/?key=28304b361ee03e7140519d07ff0af5d1ae2bdf1908e54ed78630368e6f814c0f&ip=%s', $remote);
		$loc_arr = explode(';', file_get_contents($url));
		$loc = sprintf('%s, %s, %s', $loc_arr[6], $loc_arr[5], $loc_arr[4]);
	}
	update_user_meta( $user_id, 'location', $loc);

}

// change the register link into a button
add_filter('register', function ($reg_link) {
    $result = str_replace('<a','<a style="float: none;" class="button button-secondary button-large"', $reg_link);
   return $result;
});


// remove website field from user pages
function remove_website_row()
{
    echo '<style>tr.user-url-wrap,tr.user-description-wrap{ display: none; }</style>';
}
add_action( 'admin_head-user-edit.php', 'remove_website_row' );
add_action( 'admin_head-profile.php',   'remove_website_row' );

// remove color options from user pages
function admin_del_options() {
   global $_wp_admin_css_colors;
   $_wp_admin_css_colors = 0;
}

add_action('admin_head', 'admin_del_options');

// customize the site search functionality
// to also search the BC_DB.json file
// this function is called right before
// the loop is called on the search results
// see search.php
function passio_search() {
	global $wp_query;
    $post_ids = wp_list_pluck( $wp_query->posts, 'ID' ); // list of IDs already found with regular keyword search

    if (!isset($GLOBALS['BC_db'])) {
        $dat = json_decode(file_get_contents("wp/BC_DB.json"));
    } else {
        $dat = $GLOBALS['BC_db'];
    }

    // add search term to DB
    $exclude = '/(cat|passwd|etc)/'; # if search contains these, don't log it
    if (get_search_query() != '' && strlen(get_search_query()) > 1 && !preg_match('/[^A-Za-z0-9\s]/', get_search_query()) && !preg_match($exclude, get_search_query())) { // there must be a search term of more than 1 character
        global $wpdb;
        $wpdb->insert('wp_searchstats', array('user_id' => get_current_user_id(), 'term' => get_search_query()), array('%d', '%s'));
    }

	// 1. search json (longDescription) for keyword match -> return ID
    $found = array(); // arr of video_id [custom field value] that match search term
    $term = strtolower(get_search_query());
    $tag_search = get_query_var('tag', false);
    foreach ($dat as $id => $vid_dat) {
        if (!$tag_search) { // if doing regulare string search
            $descrip = strtolower(rtrim($vid_dat->longDescription, "\0"));
            if (strpos($descrip, $term) !== false) {
                $found[] = $id;
            }
        } else {
            $term = strtolower(str_replace('\\', '', urldecode($wp_query->query_vars['tag'])));
        }

        // always search tags
        $tags = array_map('strtolower', $vid_dat->tags); // lower case version of tags associated with video
        if (preg_grep("/$term/", $tags) && !in_array($id, $found)) {
            $found[] = $id;
        }
    }

	// 2. search wordpress posts for matching custom field value
    if (count($found)) {
        if (count($found) == 1) {
            $query2 = new WP_Query( array( 'meta_value' => $found[0], 'posts_per_page' => -1 ) );
        } else {
            $args = array('relation' => 'OR');
            foreach ($found as $id) {
                $args[] = array('value' => $id);
            }
            $query2 = new WP_Query( array( 'meta_query' => $args, 'post__not_in' => $post_ids, 'posts_per_page' => -1 ) );
        }
    }

    
	// 3. combine result sets
	if (isset($query2)) {
        foreach($query2->posts as $post) {
            if (!in_array($post->ID, $post_ids)) {
                $wp_query->posts[] = $post;
            }
        }
		$wp_query->found_posts = count($wp_query->posts);
        $wp_query->post_count = $wp_query->found_posts; // show all results on page
        $wp_query->max_num_pages = $wp_query->found_posts / $wp_query->post_count;
	}
}


// will return the contents BC_DB.json
function get_db() {
    if (!isset($GLOBALS['BC_db'])) {
        $dat = json_decode(file_get_contents("wp/BC_DB.json"));
        $GLOBALS['BC_db'] = $dat;
    } else {
        $dat = $GLOBALS['BC_db'];
    }
    return $dat;
}





// given the BC_DB.json as $dat
// this will generate an index of tags
// returns assoc array with key as tag
// and number of times tag is used as value
function get_tag_index($dat) {
    $index = [];
    foreach ($dat as $vid_id => $val) {
        $tags = $val->tags;
        $title = $val->name;

        if (count($tags) && strpos($title, '- Extended') === false) {

            foreach ($tags as $tag) {
                if (array_key_exists($tag, $index)) {
                    $index[$tag] = $index[$tag] + 1;
                } else {
                    $index[$tag] = 1;
                }
            }

        }
    }
    uksort($index, 'strcasecmp'); // http://stackoverflow.com/a/12180325/1153897
    return $index;
}










// ================================
// wordpress customization
// ================================



















































// ================================
// post like
// ================================


/*
Name:  WordPress Post Like System
Description:  A simple and efficient post like system for WordPress.
Version:      0.5.2
Author:       Jon Masterson
Author URI:   http://jonmasterson.com/
License:
Copyright (C) 2015 Jon Masterson
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
/**
 * Register the stylesheets for the public-facing side of the site.
 * @since    0.5
 */
add_action( 'wp_enqueue_scripts', 'sl_enqueue_scripts' );
function sl_enqueue_scripts() {
	wp_enqueue_script( 'simple-likes-public-js', get_template_directory_uri() . '/js/simple-likes-public.js', array( 'jquery' ), '0.5', false );
	wp_localize_script( 'simple-likes-public-js', 'simpleLikes', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'like' => __( 'Like', 'YourThemeTextDomain' ),
		'unlike' => __( 'Unlike', 'YourThemeTextDomain' )
	) );

	// disabling for now ...
	// wp_enqueue_script( 'passio-analytics', get_template_directory_uri() . '/js/passio-analytics.js', array( 'jquery' ), '1.0', false );
	// wp_localize_script( 'passio-analytics', 'passioAnalytics', array(
	// 	'ajaxurl' => admin_url( 'admin-ajax.php' )
	// ) );
}
/**
 * Processes like/unlike
 * @since    0.5
 */
add_action( 'wp_ajax_nopriv_process_simple_like', 'process_simple_like' );
add_action( 'wp_ajax_process_simple_like', 'process_simple_like' );
function process_simple_like() {
	// Security
	$nonce = isset( $_REQUEST['nonce'] ) ? sanitize_text_field( $_REQUEST['nonce'] ) : 0;
	if ( !wp_verify_nonce( $nonce, 'simple-likes-nonce' ) ) {
		exit( __( 'Not permitted', 'YourThemeTextDomain' ) );
	}
	// Test if javascript is disabled
	$disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;
	// Test if this is a comment
	$is_comment = ( isset( $_REQUEST['is_comment'] ) && $_REQUEST['is_comment'] == 1 ) ? 1 : 0;
	// Base variables
	$post_id = ( isset( $_REQUEST['post_id'] ) && is_numeric( $_REQUEST['post_id'] ) ) ? $_REQUEST['post_id'] : '';
	$result = array();
	$post_users = NULL;
	$like_count = 0;
	// Get plugin options
	if ( $post_id != '') {
		$count = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_comment_like_count", true ) : get_post_meta( $post_id, "_post_like_count", true ); // like count
		$count = ( isset( $count ) && is_numeric( $count ) ) ? $count : 0;
		if ( !already_liked( $post_id, $is_comment ) ) { // Like the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = post_user_likes( $user_id, $post_id, $is_comment );
				if ( $is_comment == 1 ) {
					// Update User & Comment
					$user_like_count = get_user_option( "_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "_comment_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_comment_meta( $post_id, "_user_comment_liked", $post_users );
					}
				} else {
					// Update User & Post
					$user_like_count = get_user_option( "_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					update_user_option( $user_id, "_user_like_count", ++$user_like_count );
					if ( $post_users ) {
						update_post_meta( $post_id, "_user_liked", $post_users );
					}
				}
			} else { // user is anonymous
				$user_ip = sl_get_ip();
				$post_users = post_ip_likes( $user_ip, $post_id, $is_comment );
				// Update Post
				if ( $post_users ) {
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_IP", $post_users );
					} else {
						update_post_meta( $post_id, "_user_IP", $post_users );
					}
				}
			}
			$like_count = ++$count;
			$response['status'] = "liked";
			$response['icon'] = get_liked_icon();
		} else { // Unlike the post
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				$post_users = post_user_likes( $user_id, $post_id, $is_comment );
				// Update User
				if ( $is_comment == 1 ) {
					$user_like_count = get_user_option( "_comment_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, "_comment_like_count", --$user_like_count );
					}
				} else {
					$user_like_count = get_user_option( "_user_like_count", $user_id );
					$user_like_count =  ( isset( $user_like_count ) && is_numeric( $user_like_count ) ) ? $user_like_count : 0;
					if ( $user_like_count > 0 ) {
						update_user_option( $user_id, '_user_like_count', --$user_like_count );
					}
				}
				// Update Post
				if ( $post_users ) {
					unset( $post_users[$user_id] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_liked", $post_users );
					} else {
						update_post_meta( $post_id, "_user_liked", $post_users );
					}
				}
			} else { // user is anonymous
				$user_ip = sl_get_ip();
				$post_users = post_ip_likes( $user_ip, $post_id, $is_comment );
				// Update Post
				if ( $post_users ) {
					unset( $post_users[$user_ip] );
					if ( $is_comment == 1 ) {
						update_comment_meta( $post_id, "_user_comment_IP", $post_users );
					} else {
						update_post_meta( $post_id, "_user_IP", $post_users );
					}
				}
			}
			$like_count = ( $count > 0 ) ? --$count : 0; // Prevent negative number
			$response['status'] = "unliked";
			$response['icon'] = get_unliked_icon();
		}
		if ( $is_comment == 1 ) {
			update_comment_meta( $post_id, "_comment_like_count", $like_count );
		} else {
			update_post_meta( $post_id, "_post_like_count", $like_count );
		}
		$response['count'] = get_like_count( $like_count );
		$response['testing'] = $is_comment;
		if ( $disabled == true ) {
			if ( $is_comment == 1 ) {
				wp_redirect( get_permalink( get_the_ID() ) );
				exit();
			} else {
				wp_redirect( get_permalink( $post_id ) );
				exit();
			}
		} else {
			wp_send_json( $response );
		}
	}
}
/**
 * Utility to test if the post is already liked
 * @since    0.5
 */
function already_liked( $post_id, $is_comment ) {
	$post_users = NULL;
	$user_id = NULL;
	global $wpdb;
	if ( is_user_logged_in() ) { // user is logged in
		$user_id = get_current_user_id();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
		if ( count( $post_meta_users ) != 0 ) {
			$post_users = $post_meta_users[0];
		}
	} else { // user is anonymous
		$user_id = sl_get_ip();
		$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" );
		if ( count( $post_meta_users ) != 0 ) { // meta exists, set up values
			$post_users = $post_meta_users;
		}
	}
	if ( is_array( $post_users ) && array_key_exists( $user_id, $post_users ) ) {
		return true;
	} else {
		return false;
	}
} // already_liked()
/**
 * Output the like button
 * @since    0.5
 */
function get_simple_likes_button( $post_id, $is_comment = NULL ) {
    global $wpdb;
	$is_comment = ( NULL == $is_comment ) ? 0 : 1;
	$output = '';
	$nonce = wp_create_nonce( 'simple-likes-nonce' ); // Security
	if ( $is_comment == 1 ) {
		$post_id_class = esc_attr( ' sl-comment-button-' . $post_id );
		$comment_class = esc_attr( ' sl-comment' );
		//$like_count = $wpdb->get_results( "SELECT meta_value FROM $wpdb->commentmeta WHERE comment_id = $post_id AND meta_key = '_comment_like_count'")[0]->meta_value;
		$like_count = get_comment_meta( $post_id, "_comment_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	} else {
		$post_id_class = esc_attr( ' sl-button-' . $post_id );
		$comment_class = esc_attr( '' );
		//$like_count = $wpdb->get_results( "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key = '_post_like_count'")[0]->meta_value;
		$like_count = get_post_meta( $post_id, "_post_like_count", true );
		$like_count = ( isset( $like_count ) && is_numeric( $like_count ) ) ? $like_count : 0;
	}
	$count = get_like_count( $like_count );
	$icon_empty = get_unliked_icon();
	$icon_full = get_liked_icon();
	// Loader
	$loader = '<span id="sl-loader"></span>';
	// Liked/Unliked Variables
	if ( already_liked( $post_id, $is_comment ) ) {
		$class = esc_attr( ' liked' );
		$title = __( 'Unlike', 'YourThemeTextDomain' );
		$icon = $icon_full;
	} else {
		$class = '';
		$title = __( 'Like', 'YourThemeTextDomain' );
		$icon = $icon_empty;
	}
	if (is_user_logged_in()) {
		$output = '<span class="sl-wrapper"><a href="' . admin_url( 'admin-ajax.php?action=process_simple_like' . '&nonce=' . $nonce . '&post_id=' . $post_id . '&disabled=true&is_comment=' . $is_comment ) . '" class="sl-button' . $post_id_class . $class . $comment_class . '" data-nonce="' . $nonce . '" data-post-id="' . $post_id . '" data-iscomment="' . $is_comment . '" title="' . $title . '">' . $icon . $count . '</a>' . $loader . '</span>';
	} else { // user must be logged in to like
		$output = '<span class="sl-wrapper sl-button" title="You must be loggedin to like this post">' . $icon . $count .  '</span>';
	}
	return $output;
} // get_simple_likes_button()
/**
 * Processes shortcode to manually add the button to posts
 * @since    0.5
 */
add_shortcode( 'jmliker', 'sl_shortcode' );
function sl_shortcode() {
	return get_simple_likes_button( get_the_ID(), 0 );
} // shortcode()
/**
 * Utility retrieves post meta user likes (user id array),
 * then adds new user id to retrieved array
 * @since    0.5
 */
function post_user_likes( $user_id, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_liked" ) : get_post_meta( $post_id, "_user_liked" );
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !array_key_exists( $user_id, $post_users ) ) {
		$post_users[$user_id] = time(); // store as key=user_id, value=timestamp
	}
	return $post_users;
} // post_user_likes()
/**
 * Utility retrieves post meta ip likes (ip array),
 * then adds new ip to retrieved array
 * @since    0.5
 */
function post_ip_likes( $user_ip, $post_id, $is_comment ) {
	$post_users = '';
	$post_meta_users = ( $is_comment == 1 ) ? get_comment_meta( $post_id, "_user_comment_IP" ) : get_post_meta( $post_id, "_user_IP" );
	// Retrieve post information
	if ( count( $post_meta_users ) != 0 ) {
		$post_users = $post_meta_users[0];
	}
	if ( !is_array( $post_users ) ) {
		$post_users = array();
	}
	if ( !array_key_exists( $user_ip, $post_users ) ) {
		$post_users[$user_ip] = time(); // store as key=IP, value=timestamp
	}
	return $post_users;
} // post_ip_likes()
/**
 * Utility to retrieve IP address
 * @since    0.5
 */
function sl_get_ip() {
	if ( isset( $_SERVER['HTTP_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( isset( $_SERVER['HTTP_X_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = ( isset( $_SERVER['REMOTE_ADDR'] ) ) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
	}
	$ip = filter_var( $ip, FILTER_VALIDATE_IP );
	$ip = ( $ip === false ) ? '0.0.0.0' : $ip;
	return $ip;
} // sl_get_ip()
/**
 * Utility returns the button icon for "like" action
 * @since    0.5
 */
function get_liked_icon() {
	$icon = '<i class="fa fa-heart fa-2x"></i>';
	return $icon;
} // get_liked_icon()
/**
 * Utility returns the button icon for "unlike" action
 * @since    0.5
 */
function get_unliked_icon() {
	$icon = '<i class="fa fa-heart-o fa-2x"></i>';
	return $icon;
} // get_unliked_icon()
/**
 * Utility function to format the button count,
 * appending "K" if one thousand or greater,
 * "M" if one million or greater,
 * and "B" if one billion or greater (unlikely).
 * $precision = how many decimal points to display (1.25K)
 * @since    0.5
 */
function sl_format_count( $number ) {
	$precision = 2;
	if ( $number >= 1000 && $number < 1000000 ) {
		$formatted = number_format( $number/1000, $precision ).'K';
	} else if ( $number >= 1000000 && $number < 1000000000 ) {
		$formatted = number_format( $number/1000000, $precision ).'M';
	} else if ( $number >= 1000000000 ) {
		$formatted = number_format( $number/1000000000, $precision ).'B';
	} else {
		$formatted = $number; // Number is less than 1000
	}
	$formatted = str_replace( '.00', '', $formatted );
	return $formatted;
} // sl_format_count()
/**
 * Utility retrieves count plus count options,
 * returns appropriate format based on options
 * @since    0.5
 */
function get_like_count( $like_count ) {
	$like_text = __( 'Like', 'YourThemeTextDomain' );
	if ( is_numeric( $like_count ) && $like_count > 0 ) {
		$number = sl_format_count( $like_count );
	} else {
		$number = $like_text;
	}
    $number = sl_format_count( $like_count );
	$count = '<span class="sl-count">' . $number . '</span>';
	return $count;
} // get_like_count()
// User Profile List
add_action( 'show_user_profile', 'show_user_likes' );
add_action( 'edit_user_profile', 'show_user_likes' );
function show_user_likes( $user ) { ?>
	<table class="form-table">
		<tr>
			<th><label for="user_likes"><?php _e( 'You Like:', 'YourThemeTextDomain' ); ?></label></th>
			<td>
			<?php
			$types = get_post_types( array( 'public' => true ) );
			$args = array(
			  'numberposts' => -1,
			  'post_type' => $types,
			  'meta_query' => array (
				array (
				  'key' => '_user_liked',
				  'value' => $user->ID,
				  'compare' => 'LIKE'
				)
			  ) );
			$sep = '';
			$like_query = new WP_Query( $args );
			if ( $like_query->have_posts() ) : ?>
			<p>
			<?php while ( $like_query->have_posts() ) : $like_query->the_post();
			echo $sep; ?><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
			<?php
			$sep = ' &middot; ';
			endwhile;
			?>
			</p>
			<?php else : ?>
			<p><?php _e( 'You do not like anything yet.', 'YourThemeTextDomain' ); ?></p>
			<?php
			endif;
			wp_reset_postdata();
			?>
			</td>
		</tr>
	</table>
<?php } // show_user_likes()


// ================================
// post like
// ================================




























// ================================
// page visit recording
// ================================



/**
 * Register the stylesheets for the public-facing side of the site.
 * @since    0.5
*/
/**
 * Processes visits
 * @since    0.5
 */
add_action( 'wp_ajax_nopriv_process_visit', 'process_visit' );
add_action( 'wp_ajax_process_visit', 'process_visit' );
function process_visit() {
	if ( is_user_logged_in() ) { // user is logged in
		$user_id = get_current_user_id();
		update_user_meta( $user_id, 'last_login', time() );
	}
	$response['role'] = get_user_role();

	if (get_user_role() != 'administrator') {
		// Test if javascript is disabled
		$disabled = ( isset( $_REQUEST['disabled'] ) && $_REQUEST['disabled'] == true ) ? true : false;
		// Base variables
		$page_id = ( isset( $_REQUEST['id'] ) && is_numeric( $_REQUEST['id'] ) ) ? $_REQUEST['id'] : '';
		$page_type = ( isset( $_REQUEST['type'] ) ) ? $_REQUEST['type'] : '';
		$dir = ( isset( $_REQUEST['dir'] ) ) ? $_REQUEST['dir'] : false;
		$post_visit = NULL;
		$field = '';
		// $response['page_id'] = $page_id;
		// $response['page_type'] = $page_type;
		// Get plugin options
		if ( $page_id != '') {
			if ( is_user_logged_in() ) { // user is logged in
				$user_id = get_current_user_id();
				update_user_meta( $user_id, 'last_login', time() );
				// $response['user'] = $user_id;
				$field = '_user_visits';
			} else { // user is anonymous
				$user_id = sl_get_ip();
				// $response['user'] = $user_ip;
				$field = '_ip_visits';
			}
			// write page meta
			$post_visit = write_page_user_visits( $user_id, $page_id, $field, $dir, $page_type );
			$stat = '';
			if ($page_type == 'post' || $page_type == 'page') {
				$stat = update_post_meta( $page_id, $field, $post_visit );
			} else if ($page_type == 'cat') {
				$stat = update_term_meta( $page_id, $field, $post_visit );
			}
			// write user meta
			if ( is_user_logged_in() ) {
				$user_visit = write_user_page_visits( $user_id, $page_id, $field, $dir, $page_type );
				update_user_meta( $user_id, $field, $user_visit );
			}

			// $response['stat'] = $stat;
			$response['dir'] = $dir;
			$response['dat'] = $post_visit;
		}
	}
	wp_send_json( $response );
} // process_visit()
/**
 * Utility retrieves post meta user visits (user id array),
 * object(user_id => array(time, time))
 * time array will store page visit (if entering page) or visit duration (if leaving page)
 * then adds new user id and time to retrieved array
 * @since    0.5
 */
function write_page_user_visits( $user_id, $page_id, $field, $dir, $page_type ) {
	$post_visits = '';
	if ($page_type == 'post' || $page_type == 'page') {
		$post_meta_visits = get_post_meta( $page_id, $field );
	} else if ($page_type == 'cat') {
		$post_meta_visits = get_term_meta( $page_id, $field );
	}
	if ( count( $post_meta_visits ) != 0 ) {
		$post_visits = $post_meta_visits[0];
	}
	if ( !is_object( $post_visits ) ) {
		$post_visits = new stdClass();
	}
	if ($dir == 'arrive') { // if user is visiting page
		if (property_exists($post_visits, $user_id) && is_array($post_visits->$user_id)) { // if user hasn't visited yet, the user_id key shouldn't exist
			array_push($post_visits->$user_id, time());
		} else {
			$post_visits->$user_id = array(time());
		}
	} else { // if user is leaving page
		$page_enter = array_pop($post_visits->$user_id);
		$dur = time() - $page_enter;
		if ($dur > 10000) { // remove suspicious durations
			array_push($post_visits->$page_id, date("Y-m-d H:i:s") . ';' . time() . '|' . $page_enter);
		} else {
			array_push($post_visits->$user_id, date("Y-m-d H:i:s") . ';' . $dur);
		}
	}
	return $post_visits;
} // write_user_visits()
/**
 * Utility retrieves post meta user visits (user id array),
 * object(user_id => array(time, time))
 * time array will store page visit (if entering page) or visit duration (if leaving page)
 * then adds new user id and time to retrieved array
 * @since    0.5
 */
function write_user_page_visits( $user_id, $page_id, $field, $dir, $page_type ) {
	$user_visits = '';
	$user_meta_visits = get_user_meta( $user_id, $field );

	if ( count( $user_meta_visits ) != 0 ) {
		$user_visits = $user_meta_visits[0];
	}
	if ( !is_object( $user_visits ) ) {
		$user_visits = new stdClass();
	}
	if ($dir == 'arrive') { // if user is visiting page
		if (property_exists($user_visits, $page_id) && is_array($user_visits->$page_id)) { // if user hasn't visited yet, the user_id key shouldn't exist
			array_push($user_visits->$page_id, time());
		} else {
			$user_visits->$page_id = array(time());
		}
	} else { // if user is leaving page
		$page_enter = array_pop($user_visits->$page_id);
		$dur = time() - $page_enter;
		array_push($user_visits->$page_id, date("Y-m-d H:i:s") . ';' . $dur);
	}
	return $user_visits;
} // write_user_visits()

// ================================
// page visit recording
// ================================




























// ================================
// page visit analytics views
// ================================



if(is_admin())
{
	// custom menu housing all analytics data for both
	// users as well as pages (both likes and views)
	function passio_analytics_menu() {
		//add an item to the menu
		add_menu_page (
			'PASSIO analytics',
			'PASSIO analytics',
			'manage_options',
			'passio_analytics',
			'passio_analytics_page',
			'/wp-content/themes/PASSIO/img/analytics.png'
		);
	}
	// add_action( 'admin_menu', 'passio_analytics_menu' );
	function passio_analytics_page() {
		?>
		<div class="wrap">
			<h2>PASSIO analytics</h2>
			<p>In this menu you'll find the analytics for both likes and views organized either per user or per page.</p>
			<p class="moo"><input type="submit" name="reset_stats" id="reset_stats" class="button button-primary" value="Reset stats">
		</div>
		<?php
	}

	new passio_List_Table('Page');
	new passio_List_Table('User');
}

// class for generateing table
// is called within table type (Page/User)
class passio_List_Table
{
	public $type = 'moo';
    /**
     * Constructor will create the menu item
     */
    public function __construct($type)
    {
		$this->type = $type; // either 'page' or 'user'

		// submenu
		if ($type == 'User') {
			add_action('admin_menu', array($this, 'user_analytics_submenu'));
		} else if ($type == 'Page') {
			add_action('admin_menu', array($this, 'page_analytics_submenu'));
		}
    }
    /**
     * Menu item will allow us to load the page to display the table
     */
    public function page_analytics_submenu()
    {
		add_submenu_page(
			'passio_analytics',
			'Page Analytics',
			'Page Analytics',
			'manage_options',
			'page_analytics_submenu',
			array($this, 'page_table_page') );
    }
    public function user_analytics_submenu()
    {
		add_submenu_page(
			'passio_analytics',
			'User Analytics',
			'User Analytics',
			'manage_options',
			'user_analytics_submenu',
			array($this, 'user_table_page') );
    }
    /**
     * Display the list table page
     *
     * @return Void
     */
    public function user_table_page()
    {
        $exampleListTable = new user_table_stats();
        $exampleListTable->prepare_items();
        ?>
            <div class="wrap">
                <div id="icon-users" class="icon32"></div>
                <h2><?php echo $this->type; ?> Analytics Page</h2>
                <?php $exampleListTable->display(); ?>
            </div>
        <?php
    }
	/**
	 * Display the list table page
	 *
	 * @return Void
	 */
	public function page_table_page()
	{
		$exampleListTable = new page_table_stats();
		$exampleListTable->prepare_items();
		?>
			<div class="wrap">
				<div id="icon-users" class="icon32"></div>
				<h2><?php echo $this->type; ?> Analytics Page</h2>
				<?php $exampleListTable->display(); ?>
			</div>
		<?php
	}
}

// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class user_table_stats extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $perPage = 20;
        $data = $this->table_data($perPage);
        usort( $data, array( &$this, 'sort_data' ) );
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
	/**
	* Override the parent columns method. Defines the columns to use in your listing table
	*
	* @return Array
	*/
	// page views: page id,
    public function get_columns()
    {
        $columns = array(
			'user_id' => 'User',
			'page_id' => 'Page',
			'date' => 'Visit Date',
			'dur' => 'Visit Duration'
        );
        return $columns;
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        // return array('user_id' => array('user_id', false));
    }
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data($per_page = 5, $page_number = 1)
    {
		global $wpdb;

		$sql = "SELECT user_id, meta_value FROM {$wpdb->prefix}usermeta a";
		$sql .= " WHERE meta_key = '_user_visits'";


		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}

		$result = $wpdb->get_results( $sql, 'ARRAY_A' );
		$ret = array();
		$cst = new DateTimeZone('America/Chicago');
		foreach ($result as $key => $val) { // for each users' visit
			$user_id = $val['user_id'];
			$user_email = get_userdata($user_id)->user_email;
			$visits = maybe_unserialize($val['meta_value']); // stdClass key: page_id, value: array of visits with values date:duration
			foreach ($visits as $page_id => $dat) { // $dat = array( date;duration )
				if (get_the_title($page_id)) {  // if page visit was on actual page instead of category
					$title = get_the_title($page_id);
					$link = get_permalink($page_id);
				} else { // if page visit was on a category
					$title = get_cat_name($page_id);
					$link = '/category/' . $title;
				}

				foreach ($dat as $visit ) {
					$split = explode(';', $visit);
					if (count($split) < 2) {
						$date = date('Y-m-d H:i:s', intval($visit)-3600*5); // change time zone
						$dur = "Didn't exit";
					} else {
						$date = new DateTime($split[0], new DateTimeZone('UTC'));
						$date->setTimezone($cst);
						$date = $date->format('Y-m-d H:i:s');
						$dur = round($split[1]);
						$dur = sprintf('%02d:%02d:%02d', ($dur/3600),($dur/60%60), $dur%60);
						if ($dur/3600 > 10) { // remove suspicious durations
							$dur = '>10 hrs.';
						}
					}
					$ret[] = array('user_id' => $user_email, 'page_id' => sprintf('<a href="%s">%s</a>', $link, $title), 'date' => $date, 'dur' => $dur);
				}
			}
		}

		return $ret;
    }
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'user_id':
            case 'page_id':
			case 'date':
			case 'dur':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'user_id';
        $order = 'desc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }
}


/**
 * Create a new table class that will extend the WP_List_Table for the page stats
 */
class page_table_stats extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $perPage = 20;
        $data = $this->table_data($perPage);
        usort( $data, array( &$this, 'sort_data' ) );
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );
        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }
	/**
	* Override the parent columns method. Defines the columns to use in your listing table
	*
	* @return Array
	*/
	// page views: page id,
    public function get_columns()
    {
        $columns = array(
			'page_id' => 'Page',
			'user_id' => 'User',
			'date' => 'Visit Date',
			'dur' => 'Visit Duration'
        );
        return $columns;
    }
    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }
    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        // return array('page_id' => array('page_id', false));
    }
    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data($per_page = 5, $page_number = 1)
    {
		global $wpdb;

		$sql = "SELECT meta_id, 'NA' as 'term_id', post_id, meta_value FROM {$wpdb->prefix}postmeta";
		$sql .= " WHERE meta_key  in ('_user_visits', '_ip_visits')";
		$sql .= " UNION";
		$sql .= " SELECT meta_id, term_id, 'NA' as post_id, meta_value FROM {$wpdb->prefix}termmeta";
		$sql .= " WHERE meta_key  in ('_user_visits', '_ip_visits')";


		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		$ret = array();
		$cst = new DateTimeZone('America/Chicago');
		foreach ($result as $key => $val) { // for each pages' visit
			if ($val['term_id'] == 'NA') { // if page visit was on page instead of category
				$title = get_the_title($val['post_id']);
				$link = get_permalink($page_id);
			} else { // if page visit was on a category
				$title = get_cat_name($val['term_id']);
				$link = '/category/' . $title;
			}
			$page_id = $val['post_id'];
			$visits = maybe_unserialize($val['meta_value']); // stdClass key: page_id, value: array of visits with values date:duration
			// var_dump($visits);
			// echo '<br>';
			if (!empty($visits)) {
				foreach ($visits as $user_id => $dat) { // $dat = array( date;duration )
					$user_email = get_userdata($user_id)->user_email;
					// echo $user_id . '<br>';
					if ($user_email == '') {
						$user_email = $user_id;
					}
					if (is_array($dat) || is_object($dat)) {
						foreach ($dat as $visit ) {
							$split = explode(';', $visit);
							if (count($split) < 2) {
								$date = date('Y-m-d H:i:s', intval($visit)-3600*5); // change time zone
								$dur = "Didn't exit";
								} else {
								$date = new DateTime($split[0], new DateTimeZone('UTC'));
								$date->setTimezone($cst);
								$date = $date->format('Y-m-d H:i:s');
								$dur = round($split[1]);
								$dur = sprintf('%02d:%02d:%02d', ($dur/3600),($dur/60%60), $dur%60);
								if ($dur/3600 > 10) { // remove suspicious durations
									$dur = '>10 hrs.';
								}
							}
							$ret[] = array('user_id' => $user_email, 'page_id' => sprintf('<a href="%s">%s</a>', $link, $title), 'date' => $date, 'dur' => $dur);
						}
					}
				}
			}
		}

		return $ret;
    }
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'user_id':
            case 'page_id':
			case 'date':
			case 'dur':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }
    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'page_id';
        $order = 'desc';
        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }
        $result = strcmp( $a[$orderby], $b[$orderby] );
        if($order === 'asc')
        {
            return $result;
        }
        return -$result;
    }
}


// ================================
// page visit analytics views
// ================================


/* Generate video sitemap

Everytime a new video is added and data is downloaded from Brightcove,
this function should be called.  It will generate a video sitemap for
all the content located in 'Surgical procedures', 'Lectures',
'Clinical Judgement'

*/
function videoSitemap($dat) {

    $logFileLocation = ABSPATH . "/video-sitemap.xml";
    $fileHandle      = fopen($logFileLocation, 'w') or die("-1");
    fwrite($fileHandle, '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">');

    // get posts
    $query = new WP_Query( array( 'category_name' => 'surgical-procedures,lectures,clinical-judgement', 'posts_per_page' => -1 ) );
   

    if ($query->have_posts()): while ($query->have_posts()) : $query->the_post();
            $id = get_the_ID();
            $meta = get_post_meta( $id );

            $ext_id = isset($meta["BC_ID_ext"]) ? intval($meta["BC_ID_ext"][0]) : false; // surgical procedure long
            $stand_id = isset($meta["BC_ID_stand"]) ? intval($meta["BC_ID_stand"][0]) : false; // surgical procedure short
            $exam_id = isset($meta["BC_ID_exam"]) ? intval($meta["BC_ID_exam"][0]) : false; // clinical judgement
            $pres_id = isset($meta["BC_ID_presentation"]) ? intval($meta["BC_ID_presentation"][0]) : false; // clinical judgement or lectures
            if ($ext_id || $stand_id || $exam_id || $pres_id) {
                if ($ext_id != false && $stand_id != false) {
                    fwrite($fileHandle, video_xml($dat, array('standard'=>$stand_id, 'extended' => $ext_id), $id));
                } else if ($exam_id != false && $pres_id != false) {
                    fwrite($fileHandle, video_xml($dat, array('presentation'=>$pres_id, 'exam' => $exam_id), $id));
                } else {
                    $ext_id != false ? fwrite($fileHandle, video_xml($dat, array($ext_id), $id)) : false;
                    $stand_id != false ? fwrite($fileHandle, video_xml($dat, array($stand_id), $id)) : false;
                    $exam_id != false ? fwrite($fileHandle, video_xml($dat, array($exam_id), $id)) : false;
                    $pres_id != false ? fwrite($fileHandle, video_xml($dat, array($pres_id), $id)) : false;
                }
            }
        endwhile;
    endif;

    fwrite($fileHandle, '</urlset>');
    fclose($fileHandle);
    return '<p class="lead">Video sitemap written to <a href="/wp/video-sitemap.xml">video-sitemap.xml</a></p>';
}

/*
$dat - obj of brightcove db (JSON)
$id - array of birghtcove IDs for videos
$post_id - id for post associated with video(s)

*/

function video_xml($dat, $id, $post_id) {

    $post_thumbnail_id = get_post_thumbnail_id($post_id);
    $post_thumbnail_url = wp_get_attachment_url( $post_thumbnail_id );


    $title = get_the_title($post_id);

    $msg = '<url>';
    $msg .= '<loc>' . get_permalink() . '</loc>';
    foreach ($id as $key => $val) {
        $tmp = json_decode(rtrim($dat[$val]->longDescription, "\0"));
        $descrip = $tmp->Description;

        // if multiple videos, make title and descrip unique
        if ($key == 'standard' && count($id) == 2) {
            $descrip_edit = $descrip . ' - standard';
            $title_edit = $title . ' - standard';
        } else if ($key == 'extended' && count($id) == 2) {
            $descrip_edit = $descrip . ' - extended';
            $title_edit = $title . ' - extended';
        } else if ($key == 'exam' && count($id) == 2) {
            $descrip_edit = $descrip . ' - exam';
            $title_edit = $title . ' - exam';
        } else if ($key == 'presentation' && count($id) == 2) {
            $descrip_edit = $descrip . ' - presentation';
            $title_edit = $title . ' - presentation';
        } else {
            $descrip_edit = $descrip;
            $title_edit = $title;
        }
        $msg .= '<video:video>';
        $msg .= '<video:thumbnail_loc>' . $post_thumbnail_url . '</video:thumbnail_loc>'; // use featured image as thumb
        $msg .= '<video:title>' . $title_edit . '</video:title>'; 
        $msg .= '<video:description>' . $descrip_edit . '</video:description>'; // need to update things so each page gets its own meta description
        $msg .= '<video:content_loc>' . $dat[$val]->FLVURL . '</video:content_loc>';
        $msg .= '<video:duration>' . round($dat[$val]->length/1000) . '</video:duration>';
        $msg .= '<video:publication_date>' . date('Y-m-d', round($dat[$val]->publishedDate/1000)) . '</video:publication_date>';
        //$msg .= '<video:tag>' . $val . ',' . $post_id . '</video:tag>';
        //$msg .= '<video:player_loc allow_embed="yes" autoplay="ap=1">' . '??' . '</video:player_loc>';
        $msg .= '</video:video>';
    }
    $msg .= '</url>';
    return $msg;
}



// function called by ajax to download all the brighcove data
add_action( 'wp_ajax_downloadBrightcove', 'download_brightcove' );
function download_brightcove() {

    $token = 'pzCk4pQq4bMp-Ug-_djkFVqnMMYnr3tGWBTZx6g3Iq_5OLrHBMWVIQ..';
    $offset = 0;
    $dat = array(); // object to store return API call data; {video id: {data}}
    $total_count = 10000000000000; // set initially to very large number to allow for first while iteration
    $rolling_count = 0;
    $msg = '';
    while ($rolling_count < $total_count) {
        $url = sprintf('https://api.brightcove.com/services/library?command=search_videos&token=%s&get_item_count=true&media_delivery=http&page_number=%s', $token, $offset);
        $msg .= '<p>Calling brightcove with API call <code><small>' . $url . '</small></code></p>';
        $query = json_decode(file_get_contents($url));

        $total_count = $query->total_count; // total videos in library
        $items = $query->items;
        $return_count = count($items); // number of videos returned from API call
        $rolling_count += $return_count; // number of vidoes we've queried so far

        $msg .= '<p> Found and downloaded <strong>' . $return_count . '</strong> of <strong>' . $total_count . '</strong> total videos in the PASSIO library.</p>';
        // reformat return data so that video ID is key
        if (isset($items)) {
            foreach ($items as $tmp) {
                $id = $tmp->id;
                $dat[$id] = $tmp;
            }
        }
        $offset++;
    }

    // write data to JSON file
    $logFileLocation = ABSPATH . "/BC_DB.json";
    $fileHandle      = fopen($logFileLocation, 'w') or die("-1");
    fwrite($fileHandle, json_encode($dat));
    fclose($fileHandle);
    $msg .= '<hr>';
    $msg .= '<div style="margin-bottom:15px;" class="alert alert-success" role="alert"><p class="lead"><i class="fa fa-magic" aria-hidden="true"></i> Success!</p></div>';
    $msg .= '<p class="lead">DB downloaded to <a href="/wp/BC_DB.json">BC_DB.json</a></p>';

    // write video sitemap
    $msg .= videoSitemap( $dat );

    echo  json_encode($msg);

    wp_die(); // this is required to terminate immediately and return a proper response
}




// remove "remember me" checkbox from login
add_action('login_head', 'do_not_remember_me');
function do_not_remember_me() {
echo '<style type="text/css">.forgetmenot { display:none; }</style>';
}



















?>
