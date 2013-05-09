<?php get_header(); ?>

<div id="main-content" class="Main twelve columns offset-by-one">
	<!-- Welcome -->
	<div class="welcome row">
		<?php if(!empty($opts['sc_welcome_title'])): ?>
			<h1><?php echo $opts['sc_welcome_title']; ?></h1>
		<?php endif; ?>
		<p class="lead"><?php echo $opts['sc_welcome_msg']; ?></p>
	</div>
	
	
	<?php
	/**
	* Slider Images
	*/	
	$args = array(
		'post_type'	=> 'slider',
		'orderby'	=> 'date'
	);
	query_posts($args);

	if ( have_posts()) : ?>
		<div class="slider">
			<ul class="images">
				<?php
				while ( have_posts() ) : the_post(); 
					$sc_uri_open = null;
					$sc_uri_close = null;
						
					// get link
					$slider_uri = get_post_meta(get_the_ID(), 'sc_slider_uri', true);
					$slider_target = get_post_meta(get_the_ID(), 'sc_slider_target', true);
					
					
					// prepare link
					if(!empty($slider_uri)){
						$sc_uri_open = '<a href="'.$slider_uri.'" target="'.(($slider_target == 1) ? '_blank':'').'">';
						
						$sc_uri_close = '</a>';
					}
				?>
					<li> 
						<?php
							// add link
							echo $sc_uri_open;
							
							// Featured image
							echo get_the_post_thumbnail(get_the_ID(), 'slider-img', array('class'=>'scale-with-grid', 'alt' => strip_tags(get_the_content()), 'title' => ''));
							
							echo $sc_uri_close;?>
					</li>
				<?php endwhile; ?>
			</ul>
		
		</div>
		
		<script type="text/javascript">
			jQuery(document).ready(function($) {
					
				/**
				* Homepage Sider
				*/
				$('.slider .images').responsiveSlides({
					maxwidth : 700,
					speed : <?php echo $opts['sc_slider_speed']; ?>,
					timeout : <?php echo $opts['sc_slider_timeout']; ?>,
					nav : <?php echo ($opts['sc_slider_show_nav']) ? 'true' : 'false'; ?>,
					namespace : 'centered-btns'
				});
				
			});
		</script>
		
		<style type="text/css">
			
			/** Homepage Slider  **/
			.slider { position: relative; list-style: none; overflow: hidden; width: 100%; padding: 0; margin: 20px 0; border-bottom:1px solid #DADADA; padding-bottom:20px; }
			.slider li { position: absolute; display: none; width: 100%; left: 0; top: 0;  margin:0; }
			.slider li:first-child { position: relative; display: block; float: left; }
			.slider img { display: block; height: auto; float: left; width: 100%; border: 0; }
			
			.centered-btns_nav { position: absolute; -webkit-tap-highlight-color: rgba(0, 0, 0, 0); top: 53%; left: 0; opacity:.5; text-indent: -9999px; overflow: hidden; text-decoration: none; height: 61px; width: 38px; background: transparent url("<?php echo THEME_URL;?>/images/slider_theme.gif") no-repeat left top; margin-top: -45px; }
			.centered-btns_nav:hover { opacity:.7; }
			.centered-btns_nav:active { opacity:1; }
			.centered-btns_nav.next { left: auto; background-position: right top; right: 0; }
			
			<?php 
			// Don't display slider images on mobile devices
			if(!$opts['sc_slider_show_mobile']): ?>
				/* All Mobile Sizes (devices and browser) */
				@media only screen and (max-width: 767px) {
					.slider { display:none; }
					}
			<?php endif; ?>
		</style>
	<?php else: ?>
		
		<p class="slider">
			<small class="subheader"><?php _e('Please insert some slider images into <strong>Image Slider Category</strong>', 'antistar'); ?></small>
		</p>
		
	<?php 
	endif; 
	
	// destroy previous query
	wp_reset_query();
	?>
	
	<!-- Highlights -->
	<div class="highlights row">
		<h2 class="show_all"><a href="<?php echo get_category_link($opts['sc_highlight_cat']);?>"><?php echo get_cat_name($opts['sc_highlight_cat']); ?></a></h2>
		
		<?php
		/**
		* Highlights
		*/
		query_posts('cat='.$opts['sc_highlight_cat'].'&posts_per_page=3');
		$i=0;
		if ( have_posts() ) : while ( have_posts() ) : the_post();
			$class=null;
			
			// define class
			if($i==0) $class = 'alpha';
			if($i==2) $class = 'omega'; ?>
			
			<article class="four columns <?php echo $class; ?>">
				<header>
					<a href="<?php the_permalink();?>">
						<?php
						// Featured image
						echo get_the_post_thumbnail(get_the_ID(), 'featured-img', array('class'=>'scale-with-grid', 'alt' => get_the_title(), 'title'=> ''));
						?>
						<h3><?php
							// Title
							the_title();
							?>
						</h3>
					</a>
				</header>
				
				<?php
				// categories
				$cats = get_the_category();
				if($cats){
					$seperator = ' &bull; ';
					$output = null;
					echo '<div class="category">';
					foreach($cats as $c){
						// don't show highlight category
						if($opts['sc_highlight_cat'] != $c->term_id){
							$output .= $c->cat_name . $seperator;
						}
					}
					echo trim($output, $seperator);
					
					echo '</div>';
				}
				?>
			</article>
			
			<?php
			$i++;
		
		endwhile; else: ?>
			
			<p>
				<small class="subheader"><?php _e('Nothing Found', 'antistar'); ?></small>
			</p>
			
		<?php 
		endif; 
		
		// destroy previous query
		wp_reset_query();
		?>
		
		
	</div>
	
	<hr class="double"/>
	
	<div class="home-details row">
		<!-- BLOG	-->
		<div class="Blog seven columns alpha" role="main">
			<h2><a href="<?php echo get_category_link($opts['sc_blog_cat']);?>"><?php echo get_cat_name($opts['sc_blog_cat']); ?> </a></h2>
			
			<div class="blog-posts">
				<?php		
				query_posts('cat='.$opts['sc_blog_cat'].'&posts_per_page='.$opts['sc_total_blog_posts']);
				if ( have_posts() ) : while ( have_posts() ) : the_post();
				
					get_template_part('post', get_post_format());
				
				endwhile; else: ?>
					<article id="post-0" class="post no-results not-found">
						<header>
							<h1><?php _e('Nothing Found', 'antistar'); ?></h1>
						</header>
						
						<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'antistar' ); ?></p>
							<?php get_search_form(); ?>
					</article>
				<?php 
				endif; 
				
				// destroy previous query
				wp_reset_query();	
				?>
			</div>
			
			<div align="center">
				<a class="button" href="<?php echo get_category_link($opts['sc_blog_cat']);?>"><?php _e('Show all', 'antistar'); ?></a>
			</div>
		</div>
		
		<!-- Homepage Sidebar -->
		<div class="Sidebar2 four columns offset-by-one omega" role="complementary">
			<?php if(!dynamic_sidebar('sidebar-home')) : ?>
		
			<aside id="archives" class="widget">
				<?php _e('Please edit your sidebar in widget area', 'antistar'); ?>
			</aside>
		
			<?php endif; ?>
		</div>
		
		<div class="clearfix"></div>
	</div>
	
</div>


<?php get_footer(); ?>