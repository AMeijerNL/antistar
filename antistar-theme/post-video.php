<?php global $opts; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('basic'); ?>>
	
	
	<header class="post-header">
		<h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		
		<?php 
		// Categories
		$cats = get_the_category();
		
		?>
		
		<div class="header-info">
			<?php 
			// Date (in thefront for home)
			if(!is_single() and !is_page())
				echo sc_get_date() . '&nbsp; &bull; &nbsp;'; 
			
			// Categories
			if($cats){
				$seperator = '&nbsp; &bull; &nbsp;';
				$output = null;
				
				foreach($cats as $c){
					// don't show highlight category
					if($opts['sc_blog_cat'] != $c->term_id or is_single() or is_page()){
						$output .= '<a href="'.get_category_link($c->term_id).'">'.$c->cat_name.'</a>' . $seperator;
					}
				}
				//trim cats
				$_cats = trim($output, $seperator);
				
				if(!empty($_cats)){
					echo (!is_single() and !is_page()) ? '&nbsp; &bull; &nbsp;' : '';
					echo $_cats;
				}
			}
			
			// Date (in the end for single)
			if(is_single() or is_page())
				echo '&nbsp; &mdash; &nbsp;' . sc_get_date();
			?>
		</div>
		
	</header>
	
	<?php
	// Don't show video in search results
	if(!is_search()):
	?>
		<div class="row embed-container" style="margin-bottom:40px;">
			<?php
			// Video Embed
			$sc_embed_uri = get_post_meta(get_the_ID(), 'sc_embed_uri',true);
			
			if(!empty($sc_embed_uri)): ?>
				
				<p><?php echo wp_oembed_get( $sc_embed_uri, array('width' => 700) );?></p>
			
			<?php endif; ?>
		</div>
	<?php endif; ?>
	
	<?php	
	// Show thumbnail on home page
	if(has_post_thumbnail()):
		if(!is_single() and !is_page()){
			$size = 'icon';
			$thumb_url = get_permalink();
		}
	endif;
	?>
		
	<div class="<?php echo (!is_search()) ? 'seven columns alpha' : '';?>">
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
			
			// Edit post
			edit_post_link(__('Edit'), '<span class="edit-link">[', ']</span>'); 
			
			// get tags if exists
			$post_tags = get_the_tags();
			
			if($post_tags){	?>
				
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
	
	<script type="text/javascript">
	
		(function ( $ ) {
			
			$(window).load(function(){
				
				<?php if(is_single() or is_page()): ?>
					
					/**
					* if content has more than one P element, make first element lead
					*/
					if ( $('article.post .content p').size() > 1 )
						$('article.post .content p:first-child').addClass('lead');
					
				<?php endif; ?>
				
			});
		}( jQuery ));
		
	</script>
</article>





	