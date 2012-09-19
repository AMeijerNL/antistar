<?php
		
/**
* Ajax Submit
* @since 1.0
*/
function sc_ajax_callback() {
    
	// run function
	$response = call_user_func($_POST['func'], $_POST);
	
    // response output
    header( "Content-Type: application/json" );
    echo json_encode($response);
    exit;
}

/**
* Global init of theme
* @since 1.0
*/
function sc_antistar_init(){
	
	/**
	* Register "Slider Image" post type
	*/
	$labels = array(
		'name' => _x('Slider Images', 'post type general name'),
		'singular_name' => _x('Slider Image', 'post type singular name'),
		'add_new' => _x('Add New', 'slider'),
		'add_new_item' => __('Add New Slider Image'),
		'edit_item' => __('Edit Slider Image'),
		'new_item' => __('New Slider Image'),
		'all_items' => __('All Slider Images'),
		'view_item' => __('View Slider Image'),
		'search_items' => __('Search Slider Images'),
		'not_found' =>  __('No slider image found'),
		'not_found_in_trash' => __('No slider images found in Trash'), 
		'parent_item_colon' => '',
		'menu_name' => __('Slider Images')
	);
	$args = array(
		'labels' 				=> $labels,
		'public' 				=> true,
		'capability_type' 		=> 'page',
		'publicly_queryable' 	=> true,
		'show_ui'				=> true, 
		'show_in_menu' 			=> true, 
		'query_var'			 	=> true,
		'rewrite' 				=> true,
		'has_archive' 			=> true, 
		'hierarchical' 			=> false,
		'menu_position' 		=> 5,
		'exclude_from_search' 	=> true,
		'supports' 				=> array( 'title', 'thumbnail', 'page-attributes' )
	);
	
	register_post_type('slider', $args);
}

/**
* Register sidebar(s) and widgetized areas
* @since 1.0
*/
function sc_antistar_widgets_init(){
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary-menu', __( 'Primary Menu', 'antistar' ) );
	register_nav_menu( 'sec-menu', __( 'Secondary Menu', 'antistar' ) );
	
	// Register Homepage Sidebar
	register_sidebar( array(
		'name' => __( 'Homepage (next to the Blog)', 'antistar' ),
		'id' => 'sidebar-home',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	
	// Register Other pages Sidebar
	register_sidebar( array(
		'name' => __( 'Other pages sidebar', 'antistar' ),
		'id' => 'sidebar-other',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',

	) );
}

/**
* Get web font link for <head>
* @since 1.0
*/
function sc_get_font_link(){
	if(SC_WEB_FONT == null)
		return false;
		
	// defaults
	$family = SC_WEB_FONT.':400';
	$subset = 'latin';
	
	// Font supports bold style?
	if(SC_FONT_BOLD == true)
		$family .= ',700';
	
	// Font supports italic style?
	if(SC_FONT_ITALIC == true)
		$family .= ',400italic';
	
	// Font supports bold-italic style?
	if(SC_FONT_ITALIC == true and SC_FONT_BOLD == true)
		$family .= ',700italic';
	
	// Font supports Latin Extended character set?
	if(SC_FONT_LATIN_EXT == true)
		$subset .= ',latin-ext';
	
	// Font supports Cyrillic character set?
	if(SC_FONT_CYRILLIC == true)
		$subset .= ',latin-ext';
	
	return '<link href="http://fonts.googleapis.com/css?family='.$family.'&subset='.$subset.'" rel="stylesheet" type="text/css">';
}

/**
* Get formatted date
* @since 1.0
*/
function sc_get_date(){
	global $opts;
	
	// Time-Ago format
	if($opts['sc_date_format'] == 1){
		return '<time class="date" datetime="'.get_the_date('c').'" title="'. get_the_date().'">'.sc_time_ago(get_the_time('U')).'</time>';
	
	// Default date format
	} else
		return '<time class="date" datetime="'.get_the_date('c').'">'.get_the_date().'</time>';
	
}

/**
* Format Timestamp from timestamps
* @since 1.0
*/
function sc_time_ago($timestamp) {
	// define periods
	$periods = array('second', 'minute', 'hour', 'day', 'week', 'month', 'year', 'decade');
	
	$lengths = array('60','60','24','7','4.35','12','10');
	
	// get difference
	$diff = current_time('timestamp') - $timestamp;
	
	for($j = 0; $diff >= $lengths[$j] && $j < count($lengths)-1; $j++)
	   $diff /= $lengths[$j];
	
	// round the float
	$diff = round($diff);
	
	// period is single or plural?
	if($diff != 1)
	   $periods[$j].= 's';
	
	return sprintf(__( '%s '.$periods[$j].' ago', 'antistar'), $diff);
	
}

/**
* Get comments
* @since 1.0
*/
function sc_comments($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'antistar' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'antistar' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="avatar">
				<?php
					$avatar_size = 32;
					if ( '0' != $comment->comment_parent )
						$avatar_size = 32; // for replies

					// avatar
					echo get_avatar( $comment, $avatar_size );
				?>
			</div>
			<div class="comment-content">
				<?php
					// author
					echo '<span class="author">'.get_comment_author_link().'</span>';
				?>
				<?php comment_text(); ?>
				
				<footer class="comment-meta">
					<span class="comment-author vcard">
						<?php						
							// time
							echo '<a href="'.esc_url( get_comment_link( $comment->comment_ID ) ).'" title="'.get_comment_date().' @'.get_comment_time().'"><time datetime="'.get_comment_time( 'c' ).'">'.sc_time_ago(get_comment_date('U')).'</time></a>';
						?>
						
						<?php edit_comment_link( __( 'Edit', 'antistar' ), '<span class="edit-link">', '</span>' ); ?>
					</span><!-- .comment-author .vcard -->

					<?php if ( $comment->comment_approved == '0' ) : ?>
						<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation', 'antistar' ); ?></em>
						<br />
					<?php endif; ?>
					
					<span class="reply">
						<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'antistar' ).'<span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</span>
				</footer>
			</div>
		</article><!-- #comment-## -->
		<div class="clear"></div>
	<?php
			break;
	endswitch;
}

/**
* Check if liked
* @param $type Content type (1:post, 2:page)
* @since 1.0
*/
function sc_is_liked($ID, $type=1) {
	global $wpdb;
	
	// get IP address
	$IP = ip2long($_SERVER['REMOTE_ADDR']);
	
	// get current user info
	$current_user = wp_get_current_user();
	
	// get current user id
	$user_id = (is_user_logged_in()) ? $current_user->ID : NULL;
	
	// For users (with USER ID)
	$like_id = $wpdb->get_var($wpdb->prepare('SELECT `like_id` 
												FROM '.$wpdb->prefix.'likes 
											WHERE 
												(`user_id` = %s OR `ip` = %s) AND 
												`id` =%s AND `type` = %s
											LIMIT 1', $user_id, $IP, $ID, $type
											)
							  );
	
	if(!empty($like_id))
		return true;
		
	return false;
	
}

/**
* Get total likes
* @param $type Content type (1:post, 2:page, 3:comment)
* @since 1.0
*/
function sc_total_likes($ID, $type=1) {
	global $wpdb;
	
	return  $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM '.$wpdb->prefix.'likes
									WHERE `ID`=%s AND `type`=%s', $ID, $type) );
									
}

/**
* Like button
* @since 1.0
*/
function sc_like($params) {
	global $wpdb;
	
	// get IP address
	$IP = ip2long($_SERVER['REMOTE_ADDR']);
	
	// get current user info
	$current_user = wp_get_current_user();
	
	// prepare data
	$data = array(
		'user_id'	=> $current_user->ID,
		'ID'		=> $params['id'],
		'type'		=> $_POST['type'],
		'ip'		=> $IP
	);
	if($wpdb->insert($wpdb->prefix.'likes', $data, array('%d','%d','%d')))
		return array('success'=>true);
		
	return array('success'=>false);
	
}

/**
* Unlike button
* @since 1.0
*/
function sc_unlike($params) {
	global $wpdb;
	
	// get IP address
	$IP = ip2long($_SERVER['REMOTE_ADDR']);
	
	// get current user info
	$current_user = wp_get_current_user();
	
	// get current user id
	$user_id = (is_user_logged_in()) ? $current_user->ID : NULL;
	
	// prepare sql
	$sql = 'DELETE FROM '.$wpdb->prefix.'likes 
			WHERE (`user_id`=%s OR `ip`=%s) AND
				`type`=%s AND
				`ID`=%s';
	
	// unlike now
	if($wpdb->query($wpdb->prepare($sql, $user_id, $IP, $params['type'], $params['id'])))
		return array('success'=>true);
		
	return array('success'=>false);
	
}

/**
* Get the like button
* @since 1.0
*/
function sc_get_like_button($ID, $type) {

	// defaults
	$html = null;
	
	if(empty($ID) or empty($type))
		return false;
		
		
	// find type
	switch($type){
		case 'post'			: $type=1; break;
		case 'page'			: $type=2; break;
		case 'comment'		: $type=3; break;
		default: return false;
	}
		
	// Unlike button
	if(sc_is_liked($ID, $type)){
		$html .= '<span class="btn_unlike blue"><a href="javascript:void(0)" obj_id="'.$ID.'" type="'.$type.'">'.__('Unlike', 'antistar').'</a></span> ';
	
	// Like button
	} else 
		$html .= '<span class="btn_like blue"><a href="javascript:void(0)" obj_id="'.$ID.'" type="'.$type.'">'.__('Like', 'antistar').'</a></span> ';
	
	// Calculate total likes
	$total_likes = sc_total_likes($ID, $type);
	
	// Total likes
	$html .= '<span class="total_likes">';
	if($total_likes > 0)
		$html .= '(<span class="no">'.$total_likes.'</span>)';
	$html .= '</span>';
	
	return $html;
}


/**
* Get title
* @since 1.0
*/
function sc_page_title(){
	global $page, $paged;

	wp_title( '|', true, 'right' );
	bloginfo( 'name' ); // Blog name
	$site_description = get_bloginfo( 'description', 'display' ); //site desc for homepage
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s'), max( $paged, $page ) );
}

/**
* Display navigation to next/previous pages when applicable
* @since 1.0
*/
function sc_content_nav($nav_id) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>">
			<div class="nav-next"><span class="meta-nav">&rarr;</span> <?php previous_posts_link( __( 'Prev', 'antistar' ) ); ?></div>
			<div class="nav-prev"><?php next_posts_link( __( 'Next', 'antistar' ) ); ?> <span class="meta-nav">&larr;</span></div>
			<div class="clear"></div>
		</nav><!-- #nav-above -->
		
	<?php endif;
}

/**
* Set excerpt length
* @since 1.0
*/
function sc_set_excerpt_len($length) {
	return SC_MAX_EXCERPT_LEN;
}

?>