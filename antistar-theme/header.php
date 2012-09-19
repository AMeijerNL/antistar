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
			<?php
			// Site Logo
			$sc_logo = (!empty($opts['sc_site_logo'])) ? $opts['sc_site_logo'] : THEME_URL.'/images/logo.png';
			
			?>
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
				class Walker_Nav_Menu_Dropdown extends Walker_Nav_Menu{
					function start_lvl(&$output, $depth){
					  $indent = str_repeat("\t", $depth); // don't output children opening tag (`<ul>`)
					}
				
					function end_lvl(&$output, $depth){
					  $indent = str_repeat("\t", $depth); // don't output children closing tag
					}
				
					function start_el(&$output, $item, $depth, $args){
					  // add spacing to the title based on the depth
					  $item->title = str_repeat("&nbsp;", $depth * 4).$item->title;
				
					  parent::start_el(&$output, $item, $depth, $args);
				
					  // no point redefining this method too, we just replace the li tag...
					  $output = str_replace('<li', '<option', $output);
					}
				
					function end_el(&$output, $item, $depth){
					  $output .= "</option>\n"; // replace closing </li> with the option tag
					}
				}
				
				$args = array(
					'theme_location'	=> 'primary-menu',
					'items_wrap'      => '<select id="%1$s" class="%2$s">%3$s</select>',
					'container_class' => null,
					'walker'         => new Walker_Nav_Menu_Dropdown()
				);
				
				wp_nav_menu($args);
				
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
		
		
		
		
		
		