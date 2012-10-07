<?php
/**
* Get posts by AJAX response
*/
define('WP_USE_THEMES', false);
require_once('../../../../wp-load.php');

// Our variables
$cat_ID = (isset($_GET['cat_ID'])) ? $_GET['cat_ID'] : null;
$num_posts = (isset($_GET['num_posts'])) ? $_GET['num_posts'] : 0;
$page = (isset($_GET['page_no'])) ? $_GET['page_no'] : 0;

$args = array(
       'posts_per_page' => $num_posts,
       'paged'          => $page
);

// filter by category
if(!empty($_GET['cat_ID']))
	$args['cat'] = $_GET['cat_ID'];
	
// filter by tag
if(!empty($_GET['tag']))
	$args['tag'] = $_GET['tag'];

query_posts($args);

// our loop
if (have_posts()) {
	while (have_posts()){
		the_post();
		get_template_part( 'cell', get_post_format() );
	}
}
wp_reset_query();
?>