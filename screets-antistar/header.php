<?php
global $opts;
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width,initial-scale=1">
		
<title><?php sc_page_title(); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />

<!-- Styles -->
<link rel="stylesheet" href="<?php echo THEME_URL; ?>/css/base.css">
<link rel="stylesheet" href="<?php echo THEME_URL; ?>/css/grid.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<!--[if lt IE 9]>
<script src="<?php echo THEME_URL; ?>/js/html5.js" type="text/javascript"></script>
<script src="<?php echo THEME_URL; ?>/js/selectivzr-min.js" type="text/javascript"></script>
<![endif]-->

<?php
// Site Logo
$sc_logo = (!empty($opts['sc_site_logo'])) ? $opts['sc_site_logo'] : THEME_URL.'/images/logo.png';
?>

<!-- Facebook Opengraph tags -->
<meta property="og:title" content="<?php echo sc_page_title(); ?>" />
<meta property="og:description" content="<?php echo $opts['sc_site_tagline'];?>" />
<meta property="og:site_name" content="<?php if(!empty($opts['sc_welcome_title'])): echo $opts['sc_welcome_title']; endif; ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo HOMEPAGE; ?>" />
<meta property="og:image" content="<?echo $sc_logo;?>" />

<!-- Twitter Cards (fallback to OG tags) -->
<meta name="twitter:card" content="summary">

<?php
	// insert jquery
	wp_enqueue_script( 'jquery' );
	
	// insert fancybox js
	wp_deregister_script( 'fancybox');
	wp_register_script( 'fancybox', THEME_URL . '/js/fancybox/jquery.fancybox.pack.js?v=2.1.0');
	wp_enqueue_script( 'fancybox' );
	
	// insert jquery responsive slide plugin
	if(is_home()){
		wp_deregister_script( 'responsive-slides');
		wp_register_script( 'responsive-slides', THEME_URL . '/js/jquery.responsiveslides.min.js');
		wp_enqueue_script( 'responsive-slides' );
	}
	
	// JW Player
	wp_deregister_script( 'jwplayer');
	wp_register_script( 'jwplayer', THEME_URL . '/js/mediaplayer/jwplayer.js');
	wp_enqueue_script( 'jwplayer' );
	
	// insert base.js of antistar theme
	wp_register_script( 'antistar-js', THEME_URL . '/js/antistar.js');
	wp_enqueue_script( 'antistar-js' );
	
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if (is_singular() && get_option('thread_comments'))
		wp_enqueue_script('comment-reply');
	
	wp_head();
?>

<!-- fancyBox -->
<link rel="stylesheet" href="<?php echo THEME_URL; ?>/js/fancybox/jquery.fancybox.css?v=2.1.0" type="text/css" media="screen" />
 
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600' rel='stylesheet' type='text/css'>


</head>

<body <?php body_class(); ?>>
	
	
	<div class="container">
		<header class="Sidebar three columns">
			<div class="logo">
				<a href="<?php echo HOMEPAGE; ?>"><img src="<?echo $sc_logo;?>" class="scale-with-grid" alt=""/></a>
				<h1><?php echo $opts['sc_site_tagline'];?></h1>
			</div>
			
			<aside class="main-nav">
			<?php
			// Show Primary Menu	
			if(has_nav_menu('primary-menu')): ?>
				
					<?php 
					$args = array(
						'theme_location'	=> 'primary-menu'
					);
					wp_nav_menu( $args );
					?>
			<?php else: ?>
				<p>
					<small style="color:#999; text-transform:none;">
						(<?php _e('Primary Menu is empty', 'antistar'); ?>)
					</small>
				</p>
							
			<?php endif; ?>
			</aside>
			
			<div class="mobile-menu" align="center">
				<?php
				/**
				* Dropdown menu for mobile
				*/
				$menu_name = 'primary-menu';
				
				/**
				 * Tack on the blank option for urls not in the menu
				 */
				add_filter( 'wp_nav_menu_items', 'dropdown_add_blank_item', 10, 2 );
				function dropdown_add_blank_item( $items, $args ) {
					if ( isset( $args->walker ) && is_object( $args->walker ) && method_exists( $args->walker, 'is_dropdown' ) ) {
						if ( ( ! isset( $args->menu ) || empty( $args->menu ) ) && isset( $args->theme_location ) ) {
							$theme_locations = get_nav_menu_locations();
							$args->menu = wp_get_nav_menu_object( $theme_locations[ $args->theme_location ] );
						}
						$title = isset( $args->dropdown_title ) ? wptexturize( $args->dropdown_title ) : '&mdash; ' . $args->menu->name . ' &mdash;';
						if ( ! empty( $title ) )
							$items = '<option value="" class="blank">' . apply_filters( 'dropdown_blank_item_text', $title, $args ) . '</option>' . $items;
					}
					return $items;
				}
				
				/**
				 * Remove empty options created in the sub levels output
				 */
				add_filter( 'wp_nav_menu_items', 'dropdown_remove_empty_items', 10, 2 );
				function dropdown_remove_empty_items( $items, $args ) {
					if ( isset( $args->walker ) && is_object( $args->walker ) && method_exists( $args->walker, 'is_dropdown' ) )
						$items = str_replace( "<option></option>", "", $items );
					return $items;
				}
				
				/**
				 * Overrides the walker argument and container argument then calls wp_nav_menu
				 */
				function dropdown_menu( $args ) {
					// if non array supplied use as theme location
					if ( ! is_array( $args ) )
						$args = array( 'menu' => $args );

					// enforce these arguments so it actually works
					$args[ 'walker' ] = new DropDown_Nav_Menu();
					$args[ 'items_wrap' ] = '<select id="%1$s" class="%2$s ' . apply_filters( 'dropdown_menus_class', 'dropdown-menu' ) . '">%3$s</select>';

					// custom args for controlling indentation of sub menu items
					$args[ 'indent_string' ] = isset( $args[ 'indent_string' ] ) ? $args[ 'indent_string' ] : '&ndash;&nbsp;';
					$args[ 'indent_after' ] =  isset( $args[ 'indent_after' ] ) ? $args[ 'indent_after' ] : '';

					return wp_nav_menu( $args );
				}
				
				class DropDown_Nav_Menu extends Walker_Nav_Menu {

					// easy way to check it's this walker we're using to mod the output
					function is_dropdown() {
						return true;
					}

					/**
					 * @see Walker::start_lvl()
					 * @since 3.0.0
					 *
					 * @param string $output Passed by reference. Used to append additional content.
					 * @param int $depth Depth of page. Used for padding.
					 */
					function start_lvl( &$output, $depth ) {
						$output .= "</option>";
					}

					/**
					 * @see Walker::end_lvl()
					 * @since 3.0.0
					 *
					 * @param string $output Passed by reference. Used to append additional content.
					 * @param int $depth Depth of page. Used for padding.
					 */
					function end_lvl( &$output, $depth ) {
						$output .= "<option>";
					}

					/**
					 * @see Walker::start_el()
					 * @since 3.0.0
					 *
					 * @param string $output Passed by reference. Used to append additional content.
					 * @param object $item Menu item data object.
					 * @param int $depth Depth of menu item. Used for padding.
					 * @param int $current_page Menu item ID.
					 * @param object $args
					 */
					function start_el( &$output, $item, $depth, $args ) {
						global $wp_query;
						$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
						
						$selected = '';
						
						$class_names = $value = '';

						$classes = empty( $item->classes ) ? array() : (array) $item->classes;
						$classes[] = 'menu-item-' . $item->ID;
						$classes[] = 'menu-item-depth-' . $depth;

						$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_unique( array_filter( $classes ) ), $item, $args ) );
						$class_names = ' class="' . esc_attr( $class_names ) . '"';
						
						// select current item
						if ( apply_filters( 'dropdown_menus_select_current', true ) ){
							if( !is_home() and !is_front_page() )
								$selected = in_array( 'current-menu-item', $classes ) ? ' selected="selected"' : '';
						}
						
						$output .= $indent . '<option' . $class_names .' value="'. $item->url .'"'. $selected .'>';

						// push sub-menu items in as we can't nest optgroups
						$indent_string = str_repeat( apply_filters( 'dropdown_menus_indent_string', $args->indent_string, $item, $depth, $args ), ( $depth ) ? $depth : 0 );
						$indent_string .= !empty( $indent_string ) ? apply_filters( 'dropdown_menus_indent_after', $args->indent_after, $item, $depth, $args ) : '';

						$item_output = $args->before . $indent_string;
						$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
						$item_output .= $args->after;

						$output .= apply_filters( 'walker_nav_menu_dropdown_start_el', $item_output, $item, $depth, $args );
					}

					/**
					 * @see Walker::end_el()
					 * @since 3.0.0
					 *
					 * @param string $output Passed by reference. Used to append additional content.
					 * @param object $item Page data object. Not used.
					 * @param int $depth Depth of page. Not Used.
					 */
					function end_el( &$output, $item, $depth ) {
						$output .= apply_filters( 'walker_nav_menu_dropdown_end_el', "</option>\n", $item, $depth);
					}
				}
				
				$args = array(
					'theme_location'		=>  $menu_name
				);
				
				dropdown_menu( $args );
				?></div>
			
			<div class="clearfix"></div>
			
			<!-- Search form -->
			<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
				<input type="text" value="<?php echo @$_GET['s']; ?>" name="s" class="search-field" />
			</form>
			
			<!-- Social Icons -->
			<?php
			if(is_array($opts['sc_social_icons'])): ?>
			<ul class="social-icons">
				<?php
				// Social Icons
				foreach($opts['sc_social_icons'] as $class => $uri):
					if(!empty($uri)):
				?>
					<li class="<?php echo $class; ?>">
						<a href="<?php echo $uri; ?>" target="_blank">&nbsp;</a>
					</li>
				<?php endif; endforeach; ?>
			</ul>
			<?php endif; ?>
			
			
			<!-- Sublinks -->
			<aside class="sub-nav">
				<?php
				// Show Secondary Menu	
				if(has_nav_menu('sec-menu')):
				
					$args = array(
						'theme_location'	=> 'sec-menu'
					);
					wp_nav_menu( $args ); 
				
				else: ?>
					<p>
						<small style="color:#999; text-transform:none;">
							(<?php _e('Secondary Menu is empty', 'antistar'); ?>)
						</small>
					</p>
				<?php endif; ?>
				
			</aside>
			
			
			<p class="copyright"><?php echo $opts['sc_copyright_note']; ?></p>
		</header>
		
		
		
		
		
		