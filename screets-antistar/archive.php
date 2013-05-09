<?php get_header(); ?>

<section id="main-content" class="Main twelve columns offset-by-one">
	
	<?php 
	// Breadcrumbs
	require THEME_PATH . '/breadcrumbs.php'; ?>

	<?php
	if ( have_posts() ) : ?>
		
		<header class="page-header">
			<?php
			// get description
			if(is_tag())
				$cat_desc = category_description();
			else
				$cat_desc = tag_description();
			
			if(!empty($cat_desc))
				echo '<hgroup>';
			
			// find titles
			if(is_day()){
				$header_title = get_the_date();
				$archive_name = __('Archive', 'antistar');
				
			} elseif(is_month()) {
				$header_title = get_the_date('F Y');
				$archive_name = __('Archive', 'antistar');
				
			} elseif(is_year()) {
				$header_title = get_the_date('Y');
				$archive_name = __('Archive', 'antistar');
			
			} elseif(is_category()){
				$header_title = single_cat_title('', false);
				$archive_name = __('Category', 'antistar');
			
			} elseif(is_tag()){
				$header_title = single_tag_title( '', false);
				$archive_name = __('Tag', 'antistar');
				$cat_desc = tag_description();
				
			}
			
			// Header Class
			$header_class = array();
			if($opts['sc_highlight_cat'] == $cat) // highlight category
				$header_class = 'highlight-icon';
			
			if($opts['sc_blog_cat'] == $cat) // blog category
				$header_class = 'blog-icon';
			
				
			?>
			
			<h1 class="<?php echo $header_class; ?>">
				<?php echo $header_title;  ?> 
				
				<?php if($opts['sc_highlight_cat'] != $cat and $opts['sc_blog_cat'] != $cat): ?>
				
					<div class="archive-name"><?php echo $archive_name; ?></div>
					
				<?php endif; ?>
			</h1>
			
			
			<?php
			if(!empty($cat_desc))
				echo apply_filters( 'category_archive_meta', '<h2 class="subheader">' . $cat_desc . '</h2>' ) . '</hgroup>';
			?>
			
			<?php
			if(is_category()){
				// show sub-caegories
				$sub_cats = get_categories(array('child_of'=>$cat, 'hide_empty'=>false));
				
				if(!empty($sub_cats)):?>
					<ul class="subcats">
						<li>
							<img src="<?php echo THEME_URL; ?>/images/category_arrow_icon.png" alt="" width="31" height="31" border="0" />
							<ul>
								<?php foreach($sub_cats as $c): ?>
									<li>
										<a href="<?php echo get_category_link($c->term_id); ?>">
											<?php echo $c->name; ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</li>
					</ul>
				<?php endif; ?>
			<?php } ?>
		</header>
		
		
		<div class="Multi-layout"></div>
		<div id="loader"><img src="<?php echo THEME_URL; ?>/images/ajax_loader.gif" width="16" height="16" alt="Loader"/> <?php _e('Loading', 'antistar'); ?></div>
	
	<?php else : ?>
		
		<hr/>
		
		<article id="post-0" class="post no-results not-found">
			<header>
				<h1><?php _e( 'Nothing Found', 'screets' ); ?></h1>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'screets' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .entry-content -->
		</article><!-- #post-0 -->

	<?php
	endif; 
	
	// destroy previous query
	wp_reset_query();
	?>
	
</section>

<script type="text/javascript" src="<?php echo THEME_URL;?>/js/jquery.wookmark.min.js"></script>
<script type="text/javascript">
	(function ( $ ) {		
		
		var handler = null;
		var page = 1;
		var isLoading = false;
		var preventLoad = false;
		var apiURL = '<?php echo THEME_URL; ?>/core/ajax_get_posts.php';
		
		// Prepare layout options.
		var options = {
			autoResize: true, // This will auto-update the layout when the browser window is resized.
			container: $('.Multi-layout'), // Optional, used for some extra CSS styling
			offset: 20, // Optional, the distance between grid items
			itemWidth: 220 // Optional, the width of a grid item
		};
		
		/**
		 * When scrolled all the way to the bottom, add more tiles.
		 */
		function onScroll(event) {
			// Only check when we're not still waiting for data.
			if(!isLoading && !preventLoad) {
				// Check if we're within 100 pixels of the bottom edge of the broser window.
				var closeToBottom = ($(window).scrollTop() + $(window).height() > $(document).height() - 100);
				
				if(closeToBottom) {
				  loadData();
				}
			}
		};
		
		/**
		 * Refreshes the layout.
		 */
		function applyLayout() {
		  // Clear our previous layout handler.
		  if(handler) handler.wookmarkClear();
		  
		  // Create a new layout handler.
		  handler = $('.Multi-layout .cell');
		  handler.wookmark(options);
		};
		
		/**
		 * Loads data from the API.
		 */
		function loadData() {
		  isLoading = true;
		  $('#loader').show();
		  
		  $.ajax({
			type		: 'GET',
			url			: apiURL,
			data		: { num_posts : <?php echo get_option('posts_per_page'); ?>, page_no: page  <?php echo (!empty($cat)) ? ', cat_ID:'.$cat : -1; ?> <?php echo (!empty($tag)) ? ", tag:'$tag'" : null; ?>},
			dataType	: 'html',
			success		: onLoadData
		  });
		};
		
		/**
		 * Receives data from the API, creates HTML for images and updates the layout
		 */
		function onLoadData(data) {
		  isLoading = false;
		  $('#loader').hide();
		  
		  // Increment page index for future calls.
		  page++;
		  
		  if(data.length){
		  	  // Add image HTML to the page
			  $('.Multi-layout').append(data);
			  
			  // Apply layout.
			  applyLayout();
		  }else {
		  	preventLoad = true;
		  }
		};
	  
		$(document).ready(new function() {
		  // Capture scroll event.
		  $(document).bind('scroll', onScroll);
		  
		  // Load first data from the API.
		  loadData();
		});
		
		
		
	}( jQuery ));
</script>

<?php get_footer(); ?>