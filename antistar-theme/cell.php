<?php global $opts; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('cell'); ?>>
	
	<div class="wrapper">
		<?php	
		// Show thumbnail in homepage
		if(has_post_thumbnail()): ?>
		
			<a href="<?php echo the_permalink(); ?>"><?php the_post_thumbnail('cell');?></a>
		
		<?php endif; ?>
		
		<header>
			<h1><a href="<?php the_permalink();?>"><?php the_title(); ?></a></h1>
			
			<div class="cats"><?php 
				// Categories
				$cats = get_the_category();
				
				if($cats){
					$seperator = ' &bull; ';
					$output = null;
					
					foreach($cats as $c){
						if($cat != $c->term_id)
							$output .= '<a href="'.get_category_link($c->term_id).'">'.$c->cat_name.'</a>' . $seperator;
						
					}
					//trim cats
					$_cats = trim($output, $seperator);
					
					if(!empty($_cats))
						echo $_cats;
				}
				?></div>
				
				
		</header>
	</div>
	
</article>