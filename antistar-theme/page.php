<?php get_header(); ?>

		<div class="Main details twelve columns offset-by-one" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'post', 'page' ); ?>
				

			<?php endwhile; // end of the loop. ?>

			
			<?php get_sidebar(); ?>
		</div>
		
<?php get_footer(); ?>