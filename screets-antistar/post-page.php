<?php global $opts; ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>
	
	<header class="post-header">
		<h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		
		<?php 
			// Categories
			$cats = get_the_category();
		?>
		
	</header>
	
	<?php	
	// Show thumbnail on home page
	if(has_post_thumbnail()):
		if(!is_single() and !is_page()){
			$size = 'icon';
			$thumb_url = get_permalink();
		}
	endif;
	?>
		
	<div class="seven columns alpha">
		<?php	
		// Show thumbnail on home page
		if(has_post_thumbnail() and !empty($size)): ?>
			<div class="thumb">
				<a href="<?php echo $thumb_url; ?>"><?php the_post_thumbnail($size, array('class'=>'scale-with-grid'));?></a>
			</div>
		<?php endif; ?>
		
		<div class="content"><?php the_content(); ?></div>
		
		<div class="clearfix"></div>
		
		<?php
		/**
		* Tags
		*/
		if(is_single() or is_page()):
			
			// get tags if exists
			$post_tags = get_the_tags();
			
			if($post_tags){	?>
				<?php 
				// edit link
				if(is_single() or is_page())
					edit_post_link(__('Edit'), '<span class="edit-link">[', ']</span>'); 
				?>
				
				<footer class="post-footer">
					
					<?php
					$seperator = '&nbsp; &nbsp;';
					$output = null;
					
					foreach($post_tags as $tag)
						$output .= '<a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a>' . $seperator;
					
					//trim tags
					$_tag = trim($output, $seperator);
					
					if(!empty($_tag))
						echo $_tag;
					
					?>
				</footer>
				
			<?php }//if ?>
			
			
		<?php else: ?>
			
			<footer class="post-footer">&nbsp;</footer>
			
		<?php endif; ?>
			<?php 
			// Comments
			if(is_single() or is_page())
				comments_template( '', true );
			?>
	</div>
</article>





	