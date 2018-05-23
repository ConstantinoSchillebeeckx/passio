<!-- search -->
<?php
$search_terms = htmlspecialchars( $_GET["s"] );
$get_cat = get_the_category();
$category = $get_cat[0]->slug;

if ($_GET["category_name"]) {
	$search_terms .= sprintf("&category_name=%s", $_GET["category_name"]);
}

?>

<form class="navbar-form navbar-left" role="form" action="<?php bloginfo('siteurl'); ?>/" id="searchform" method="get">
    <label for="s" class="sr-only">Search</label>
    <div class="input-group">
        <input type="text" class="form-control" id="s" name="s" placeholder="Search" value="<?php echo get_search_query(); ?>">
        <input type="hidden" value="1" name="sentence" />
        <input type="hidden" value="post" name="post_type" />
        <input type="hidden" value="surgical-procedures,clinical-judgement,lectures" name="category_name" />
        <div class="input-group-btn">
	    <div class="input-group-btn"><button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button></div>
        </div>
    </div>
</form>
<!-- /search -->
