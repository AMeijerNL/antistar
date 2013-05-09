<?php get_header(); ?>

<div id="main-content" class="Main details twelve columns offset-by-one" role="main">
	<?php 
	// Breadcrumbs
	require THEME_PATH . '/breadcrumbs.php'; ?>
	
	<?php 
	
	
	while ( have_posts() ) : the_post(); ?>
		
		<?php 
			get_template_part( 'post', get_post_format() ); 
		?>
	
	
	<?php endwhile; // end of the loop. ?>
		
	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>