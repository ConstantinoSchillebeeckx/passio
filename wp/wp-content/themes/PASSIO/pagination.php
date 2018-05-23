<!-- pagination -->
<?php
global $wp_query;
$big = 999999999;
$root = str_replace($big . '/', '', get_pagenum_link($big));
$max_pages = $wp_query->max_num_pages;

$current_page = max(1, get_query_var('paged'));
$next_page = min($current_page + 1, $max_pages);
$prev_page = max($current_page - 1, 0);

if ($max_pages > 0) { // only show pagination if needed
?>

<nav>
  <ul class="pager">
    <?php if ($current_page == 1) {
	echo '<li class="previous disabled"><a href="javascript: void(0);">Previous</a></li>';
    } else {
	echo '<li class="previous"><a href="' . $root . $prev_page . '">Previous</a></li>';
    } ?>
    <?php if ($current_page == $max_pages) {
	echo '<li class="next disabled"><a href="javascript: void(0);">Next</a></li>';
    } else {
	echo '<li class="next"><a href="' . $root . $next_page . '">Next</a></li>';
    } ?>
  </ul>
</nav>
<?php } ?>
<!-- /pagination -->