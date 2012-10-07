<?php get_header(); ?>

<div class="Main twelve columns offset-by-one" role="main">
	
	<?php 
	// Breadcrumbs
	require THEME_PATH . '/breadcrumbs.php'; ?>

	
	<div class="Blog"><?php if ( have_posts() ) : ?>
	
	
	
		<header class="page-header">
	
			<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'antistar' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
	
		</header>
	
	
	
		<?php sc_content_nav( 'nav-above' ); ?>
	
	
	
		<?php /* Start the Loop */ ?>
	
		<?php while ( have_posts() ) : the_post(); ?>
	
	
	
			<?php
	
				/* Include the Post-Format-specific template for the content.
	
				 * If you want to overload this in a child theme then include a file
	
				 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
	
				 */
	
				$post_format = get_post_format();
	
				if(!empty($post_format))
	
					get_template_part( 'post', get_post_format() );
	
			?>
	
	
	
		<?php endwhile; ?>
	
		
	
		<?php sc_content_nav( 'nav-below' ); ?>
	
		
	
		
	
	<?php else : ?>
	
	
	
		<article id="post-0" class="post no-results not-found">
	
			<header class="entry-header">
	
				<h1 class="entry-title"><?php _e( 'Nothing Found', 'antistar' ); ?></h1>
	
			</header><!-- .entry-header -->
	
	
	
			<div class="entry-content">
	
				<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'antistar' ); ?></p>
	
				<?php get_search_form(); ?>
	
			</div><!-- .entry-content -->
	
		</article><!-- #post-0 -->
	
	
	
	<?php endif; ?></div>

	
</section>


<?php get_footer(); ?>