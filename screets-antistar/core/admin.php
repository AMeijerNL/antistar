<?php
/**
* ANTISTAR © 2012
* Administration functions
*
*/
/**
* Default option values
*/
$sc_opts['sc_date_format'] = array(0,1); // 0: default format, 1:time-ago format
$sc_opts['sc_crop_img_on_single'] = array(0,1); // 0: no-crop, 1:crop image


// Default theme options
$default_theme_options = array(
	'sc_site_logo'			=> '',
	'sc_welcome_title'		=> 'Welcome to Antistar',
	'sc_welcome_msg'		=> '<strong>antistar</strong> is a simple, cute and responsive Wordpress theme which is great for portfolios, artists, small businesses and novices.',
	'sc_date_format'		=> 1, // 0: default format, 1:time-ago format
	'sc_site_tagline'		=> "Change your tagline",
	'sc_highlight_cat'		=> 1,
	'sc_blog_cat'			=> 1,
	'sc_slider_show_mobile'	=> 1, // show slider on mobile devices?
	'sc_slider_image_h'		=> 432,
	'sc_slider_speed'		=> 1000,
	'sc_slider_timeout'		=> 5000,
	'sc_slider_show_nav'	=> true,
	'sc_social_icons'		=> array('facebook'=>'http://www.facebook.com/YOURNAME','twitter'=>'http://twitter.com/YOURNAME' ),
	'sc_copyright_note'		=> '2012 &copy; Antistar Theme',
	'sc_total_blog_posts'	=> 3,
	'sc_crop_img_on_single'	=> 1,
	'sc_address'			=> '77 Street. New York, NY, 12345. USA',
	'sc_phone'				=> '(771) 111-9999',
	'sc_email'				=> 'info@yourdomain.com'
);

// Default Wordpress Settings
$core_settings = array(
	'avatar_default' => 'mystery',
	'avatar_rating' => 'G',
	'default_role' => 'author',
	'comments_per_page' => 20
);

/**
* Theme setup
* @since 1.0
*/
function sc_theme_setup(){
	global $core_settings;
	
	$theme_opts = array();
	
	
	// First we check to see if our default theme settings have been applied.
	$the_theme_status = get_option( 'sc_theme_setup_status' );
	
	// If the theme has not yet been used we want to run our default settings.
	if ( $the_theme_status !== '1' ) {
		
		// Setup default Wordpress settings
		foreach ( $core_settings as $k => $v )
			update_option( $k, $v );
		
		// Add default Blog category
		if(!has_term('mini-blog', 'category')){
			$Blog_cat = wp_insert_term('Mini Blog', 'category',
				array(
					'description'=> '',
					'slug' => 'mini-blog'
				)
			);
		}
		
		// Add default Highlights category
		if(!has_term('highlights', 'category')){
			$Highlights_cat = wp_insert_term('Highlights', 'category',
				array(
					'description'=> '',
					'slug' => 'highlights'
				)
			);
		}
		
		// Update default categories
		$theme_opts['sc_blog_cat'] = $Blog_cat['term_id'];
		$theme_opts['sc_highlight_cat'] = $Highlights_cat['term_id'];
		
		// Create sample blog post
		$post = array(
			'post_title' 		=> 'Readme',
			'post_category' 	=> array($Blog_cat['term_id']),
			'post_content' 		=> '<p>This is a sample post for your blog. Don\'t forget to add at least one Featured Image into each post.</p>',
			'post_type'			=> 'post',
			'post_status'		=> 'publish'
		);
		$post_id = wp_insert_post( $post );
		
		
		// Setup default theme settings
		add_option( 'sc_antistar_theme_options', sc_antistar_get_default_theme_options($theme_opts) );
		
		// Delete dummy post, page and comment.
		wp_delete_post( 1, true );
		wp_delete_post( 2, true );
		wp_delete_comment( 1 );
		
		// Once done, we register our setting to make sure we don't duplicate everytime we activate.
		update_option( 'sc_theme_setup_status', '1' );
		
		// Lets let the admin know whats going on.
		$msg = '<div class="error">
			<p>The ' . get_option( 'current_theme' ) . 'theme has changed your WordPress default <a href="' . admin_url( 'options-general.php' ) . '" title="See Settings">settings</a> and deleted default posts & comments.</p>
		</div>';
		add_action( 'admin_notices', $c = create_function( '', 'echo "' . addcslashes( $msg, '"' ) . '";' ) );
	
	// Else if we are re-activing the theme
	} elseif ( $the_theme_status === '1' and isset( $_GET['activated'] ) ) {
		$msg = '
		<div class="updated">
			<p>The ' . get_option( 'current_theme' ) . ' theme was successfully re-activated.</p>
		</div>';
		add_action( 'admin_notices', $c = create_function( '', 'echo "' . addcslashes( $msg, '"' ) . '";' ) );
	}
}

/**
* Admin Scripts
* @since 1.0
*/
function sc_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('jquery');
	
	wp_register_script('custom-meta-boxes', THEME_URL . '/js/custom-meta-boxes.js');
	wp_enqueue_script('custom-meta-boxes');
}

/**
* Admin Styles
* @since 1.0
*/
function sc_admin_styles() {
	wp_enqueue_style('thickbox');
}
add_action('admin_print_scripts', 'sc_admin_scripts');
add_action('admin_print_styles', 'sc_admin_styles');

/**
* Administration menu
* @since 1.0
*/
function sc_admin_menu() {
	add_menu_page('', 'Antistar', 'administrator', 'sc_opt_pg', 'sc_render_option_page', get_template_directory_uri().'/core/images/ico_screets_16.png', 130);
}

/**
* Theme options Initialization
* @since 1.0
*/
function sc_antistar_options_init(){
	if ( false === sc_antistar_get_theme_options() )
		add_option( 'sc_antistar_theme_options', sc_antistar_get_default_theme_options() );
	
	register_setting(
		'sc_antistar_options', // Option group
		'sc_antistar_theme_options', // Database option
		'sc_antistar_theme_options_validate' // The sanitization callback
	);
	
	/** SECTION: General settings **/
	add_settings_section(
		'general', 			// Unique identifier for the settings section
		__('General', 'antistar'), 				// Section title
		'__return_false', 	// Section callback (we don't want anything)
		'theme_options'		// Menu slug, used to uniquely identify the page
	);
	
	
	// Logo
	add_settings_field( 'sc_theme_render_logo', 
						__( 'Site Logo', 'antistar' ), 
						'sc_theme_render_logo', 
						'theme_options',
						'general'
					  );
	
	// Welcome title
	add_settings_field( 'sc_welcome_title', 
						__( 'Welcome Title', 'antistar' ), 
						'sc_theme_render_welcome_title', 
						'theme_options',
						'general'
					  );
	
	// Welcome message
	add_settings_field( 'sc_welcome_msg', 
						__( 'Welcome Message', 'antistar' ), 
						'sc_theme_render_welcome_msg', 
						'theme_options',
						'general'
					  );
	
	// Site Tagline
	add_settings_field( 'sc_site_tagline', 
						__( 'Site Tagline', 'antistar' ), 
						'sc_theme_render_site_tagline', 
						'theme_options', 
						'general'
					  );
	
	// Highlights category
	add_settings_field( 'sc_highlight_cat', 
						__( 'Highlights Category', 'antistar' ), 
						'sc_theme_render_highlight_cat', 
						'theme_options', 
						'general'
					  );	
					  
	// Blog category
	add_settings_field( 'sc_blog_cat', 
						__( 'Blog Category', 'antistar' ), 
						'sc_theme_render_blog_cat', 
						'theme_options', 
						'general'
					  );
					  
	// Image Slider
	add_settings_field( 'sc_slider_cat', 
						__( 'Image Slider', 'antistar' ), 
						'sc_theme_render_slider_cat', 
						'theme_options', 
						'general'
					  );
					  
					  
	// Social Icons
	add_settings_field( 'sc_social_icons', 
						__( 'Social Icons', 'antistar' ), 
						'sc_theme_render_social_icons', 
						'theme_options', 
						'general'
					  );				  
	// Copyright info
	add_settings_field( 'sc_copyright_note', 
						__( 'Copyright Note', 'antistar' ), 
						'sc_theme_render_copyright_note', 
						'theme_options', 
						'general'
					  );
					  
	// Time Ago field
	add_settings_field( 'sc_date_format', 
						__( 'Date Format', 'antistar' ), 
						'sc_theme_render_date_format', 
						'theme_options',
						'general'
					  );
	
	// Total blog posts on homepage
	add_settings_field( 'sc_total_blog_posts', 
						__( 'Total Blog Posts on Homepage', 'antistar' ), 
						'sc_theme_render_total_blog_posts', 
						'theme_options',
						'general'
					  );
					  
	// Crop featured image on single pages
	add_settings_field( 'sc_crop_img_on_single', 
						__( 'Crop Featured Images on Single Pages to Exact Dimensions', 'antistar' ), 
						'sc_theme_render_crop_img_on_single', 
						'theme_options',
						'general'
					  );
					  
	
	/** SECTION: Contact information		*/
	add_settings_section(
		'contact', 			// Unique identifier for the settings section
		__('Contact Information', 'antistar'), 				// Section title
		'__return_false', 	// Section callback (we don't want anything)
		'theme_options'		// Menu slug, used to uniquely identify the page
	);
	
	// Address
	add_settings_field( 'sc_address', 
						__( 'Address', 'antistar' ), 
						'sc_theme_render_address', 
						'theme_options',
						'contact'
					  );
	
	// Phone
	add_settings_field( 'sc_phone', 
						__( 'Phone', 'antistar' ), 
						'sc_theme_render_phone', 
						'theme_options',
						'contact'
					  );
					  
	// Email
	add_settings_field( 'sc_email', 
						__( 'Email', 'antistar' ), 
						'sc_theme_render_email', 
						'theme_options',
						'contact'
					  );
					  
	
}

/**
* Get theme options
* @since 1.0
*/
function sc_antistar_get_theme_options(){
	return get_option( 'sc_antistar_theme_options', sc_antistar_get_default_theme_options() );
}

/**
* Get default theme options
* @since 1.0
*/
function sc_antistar_get_default_theme_options($args=array()){
	
	global $default_theme_options;
	
	$opts = array_merge($default_theme_options, $args);
		
	return apply_filters( 'sc_antistar_get_default_theme_options', $opts );
}


/**
* Render Logo
* @since 1.0
*/
function sc_theme_render_logo($input){
	// get options
	$opts = sc_antistar_get_theme_options(); ?>

	<input id="upload_image" type="text" size="36" name="sc_antistar_theme_options[sc_site_logo]" value="<?php echo $opts['sc_site_logo']; ?>" />
	
	<input id="upload_image_button" type="button" value="<?php _e('Upload Logo', 'antistar'); ?>" />

	<div class="description"><strong>(!)</strong> <?php _e('Please choose <strong>Full Size</strong> and then click <strong>"Insert into Post"</strong> button after uploading your image', 'antistar'); ?>:</div>
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
			jQuery('#upload_image_button').click(function() {
				formfield = jQuery('#upload_image').attr('name');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				return false;
			});
			
			window.send_to_editor = function(html) {
				imgurl = jQuery('img',html).attr('src');
				jQuery('#upload_image').val(imgurl);
				tb_remove();
			}
		});
	</script>
	
<?php
}

/**
* Render Date-Format field
* @since 1.0
*/
function sc_theme_render_date_format($input){ 
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	
	<select name="sc_antistar_theme_options[sc_date_format]">
		<option value="0" <?php selected($opts['sc_date_format'], 0); ?>><?php _e('Default date format', 'antistar');?></option>
		<option value="1" <?php selected($opts['sc_date_format'], 1); ?>><?php _e('Time-ago format', 'antistar');?></option>
	</select>

<?php }

/**
* Render Highlight category field
* @since 1.0
*/
function sc_theme_render_highlight_cat($input){ 
	// get options
	$opts = sc_antistar_get_theme_options();
	
	// list categories
	wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'sc_antistar_theme_options[sc_highlight_cat]', 'orderby' => 'name', 'selected' => $opts['sc_highlight_cat'], 'hierarchical' => true, 'show_option_none' => false));
	
	echo '<em class="description">(!) '.__('Please use at least one <strong>Featured Image</strong> for each post', 'antistar').'</em>';
	?>

<?php }


/**
* Render Blog category field
* @since 1.0
*/
function sc_theme_render_blog_cat($input){ 
	// get options
	$opts = sc_antistar_get_theme_options();
	
	// list categories
	wp_dropdown_categories(array('hide_empty' => 0, 'name' => 'sc_antistar_theme_options[sc_blog_cat]', 'orderby' => 'name', 'selected' => $opts['sc_blog_cat'], 'hierarchical' => true, 'show_option_none' => false));
	?>

<?php }


/**
* Render Image Slider category field
* @since 1.0
*/
function sc_theme_render_slider_cat($input){ 
	// get options
	$opts = sc_antistar_get_theme_options();
	
	 ?>
	<p><label><input type="checkbox" name="sc_antistar_theme_options[sc_slider_show_mobile]" id="" value="1" <?php checked($opts['sc_slider_show_mobile']); ?>/> &nbsp; <?php _e('Display Homepage Slider on mobile devices', 'antistar'); ?></label></p>
	
	
	<p>
		<!-- Image height -->
		<span class="description"><?php _e('Image Height', 'antistar'); ?>:</span>
		<input type="text" name="sc_antistar_theme_options[sc_slider_image_h]" id="" value="<?php echo $opts['sc_slider_image_h']; ?>"  style="width:50px;" /> px
		&nbsp;&nbsp;&nbsp;&nbsp;
		<!-- Speed -->
		<span class="description"><?php _e('Speed', 'antistar'); ?>:</span>
		<input type="text" name="sc_antistar_theme_options[sc_slider_speed]" id="" value="<?php echo $opts['sc_slider_speed']; ?>"  style="width:50px;" /> ms
		&nbsp;&nbsp;&nbsp;&nbsp;
		<!-- Timeout -->
		<span class="description"><?php _e('Timeout', 'antistar'); ?>:</span>
		<input type="text" name="sc_antistar_theme_options[sc_slider_timeout]" id="" value="<?php echo $opts['sc_slider_timeout']; ?>"  style="width:50px;" /> ms&nbsp;&nbsp;&nbsp;&nbsp;
		
		<!-- Show nav. -->
		<label><span class="description"><?php _e('Show Navigation', 'antistar'); ?>:</span>
		<input type="checkbox" name="sc_antistar_theme_options[sc_slider_show_nav]" id="" value="1" <?php checked($opts['sc_slider_show_nav']); ?>/></label>
		
	</p>
	
	<?php
}


/**
* Render Welcome Title
* @since 1.0
*/
function sc_theme_render_welcome_title($input){
	
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	
	<input type="text" name="sc_antistar_theme_options[sc_welcome_title]" id="" value="<?php echo $opts['sc_welcome_title']; ?>"  style="width:320px;" />
	
		
<?php }

/**
* Render Welcome Message
* @since 1.0
*/
function sc_theme_render_welcome_msg($input){
	
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	<textarea name="sc_antistar_theme_options[sc_welcome_msg]" id="" style="width:320px; height:80px;"><?php echo $opts['sc_welcome_msg']; ?></textarea>
	
		
<?php }

/**
* Render Site Tagline field
* @since 1.0
*/
function sc_theme_render_site_tagline($input){
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	<input type="text" name="sc_antistar_theme_options[sc_site_tagline]" id="" value="<?php echo $opts['sc_site_tagline']; ?>"  style="width:323px;" />
	
		
<?php }

/**
* Render Copyright info
* @since 1.0
*/
function sc_theme_render_copyright_note($input){
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	<input type="text" name="sc_antistar_theme_options[sc_copyright_note]" id="" value="<?php echo $opts['sc_copyright_note']; ?>" style="width:220px; "/>
		
<?php }


/**
* Render Total Blog Posts
* @since 1.0
*/
function sc_theme_render_total_blog_posts($input){
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	<input type="text" name="sc_antistar_theme_options[sc_total_blog_posts]" id="" value="<?php echo $opts['sc_total_blog_posts']; ?>" style="width:80px; "/>
		
<?php }

/**
* Render Crop featured image on single pages
* @since 1.0
*/
function sc_theme_render_crop_img_on_single($input){
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	
	<select name="sc_antistar_theme_options[sc_crop_img_on_single]">
		<option value="1" <?php selected($opts['sc_crop_img_on_single'], 1); ?>><?php _e('Yes', 'antistar');?></option>
		<option value="0" <?php selected($opts['sc_crop_img_on_single'], 0); ?>><?php _e('No', 'antistar');?></option>
	</select>
	
		
<?php }

/**
* Render Address
* @since 1.0
*/
function sc_theme_render_address($input){
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	<textarea name="sc_antistar_theme_options[sc_address]" id="" style="width:280px; "><?php echo $opts['sc_address']; ?></textarea>
		
<?php }

/**
* Render Phone
* @since 1.0
*/
function sc_theme_render_phone($input){
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	<input type="text" name="sc_antistar_theme_options[sc_phone]" id="" value="<?php echo $opts['sc_phone']; ?>" style="width:200px; "/>
		
<?php }

/**
* Render Email
* @since 1.0
*/
function sc_theme_render_email($input){
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	<input type="text" name="sc_antistar_theme_options[sc_email]" id="" value="<?php echo $opts['sc_email']; ?>" style="width:200px; "/>
		
<?php }

/**
* Render Social Icons field
* @since 1.0
*/
function sc_theme_render_social_icons($input){
	// get options
	$opts = sc_antistar_get_theme_options(); ?>
	<style>
		.sc_row { margin-bottom:10px; }
		.sc_social_icon { 
			vertical-align: bottom; position: relative; top: -1px; *overflow: hidden; margin-right:10px; 
		}
	</style>
	
	<!-- Facebook	-->
	<div class="sc_row" title="Facebook">
		<img src="<?php echo THEME_URL;?>/core/images/social_facebook.png" class="sc_social_icon" width="24" height="24" />
		<input type="text" name="sc_antistar_theme_options[sc_social_icons][facebook]" value="<?php echo $opts['sc_social_icons']['facebook']; ?>" size="50" />
	</div>
	
	<!-- Twitter	-->
	<div class="sc_row" title="Twitter">
		<img src="<?php echo THEME_URL;?>/core/images/social_twitter.png" class="sc_social_icon" width="24" height="24" />
		<input type="text" name="sc_antistar_theme_options[sc_social_icons][twitter]" value="<?php echo $opts['sc_social_icons']['twitter']; ?>" size="50" />
	</div>
	
	<!-- Google Plus	-->
	<div class="sc_row" title="Google Plus">
		<img src="<?php echo THEME_URL;?>/core/images/social_google_plus.png" class="sc_social_icon" width="24" height="24" />
		<input type="text" name="sc_antistar_theme_options[sc_social_icons][google_plus]" value="<?php echo $opts['sc_social_icons']['google_plus']; ?>" size="50" />
	</div>
	
	<!-- Skype	-->
	<div class="sc_row" title="Skype">
		<img src="<?php echo THEME_URL;?>/core/images/social_skype.png" class="sc_social_icon" width="24" height="24" />
		<input type="text" name="sc_antistar_theme_options[sc_social_icons][skype]" value="<?php echo $opts['sc_social_icons']['skype']; ?>" size="50" />
	</div>
	
	<!-- LinkedIn	-->
	<div class="sc_row" title="LinkedIn">
		<img src="<?php echo THEME_URL;?>/core/images/social_linkedin.png" class="sc_social_icon" width="24" height="24" />
		<input type="text" name="sc_antistar_theme_options[sc_social_icons][linkedin]" value="<?php echo $opts['sc_social_icons']['linkedin']; ?>" size="50" />
	</div>
	
	<!-- Tumblr	-->
	<div class="sc_row" title="Tumblr">
		<img src="<?php echo THEME_URL;?>/core/images/social_tumblr.png" class="sc_social_icon" width="24" height="24" />
		<input type="text" name="sc_antistar_theme_options[sc_social_icons][tumblr]" value="<?php echo $opts['sc_social_icons']['tumblr']; ?>" size="50" />
	</div>
	
	<!-- Vimeo	-->
	<div class="sc_row" title="Vimeo">
		<img src="<?php echo THEME_URL;?>/core/images/social_vimeo.png" class="sc_social_icon" width="24" height="24" />
		<input type="text" name="sc_antistar_theme_options[sc_social_icons][vimeo]" value="<?php echo $opts['sc_social_icons']['vimeo']; ?>" size="50" />
	</div>
		
	<!-- Spotify	-->
	<div class="sc_row" title="Spotify">
		<img src="<?php echo THEME_URL;?>/core/images/social_spotify.png" class="sc_social_icon" width="24" height="24" />
		<input type="text" name="sc_antistar_theme_options[sc_social_icons][spotify]" value="<?php echo $opts['sc_social_icons']['spotify']; ?>" size="50" />
	</div>

<?php }

/**
* Sanitize and validate form input. Accepts an array, return a sanitized array.
* @since 1.0
*/
function sc_antistar_theme_options_validate($input){
	global $sc_opts;
	
	// get defaults
	$output = $defaults = sc_antistar_get_default_theme_options();
	
	// validate date-format
	if(in_array($input['sc_date_format'], $sc_opts['sc_date_format']))
		$output['sc_date_format'] = $input['sc_date_format'];
		
	// validate crop_img_on_single
	if(in_array($input['sc_crop_img_on_single'], $sc_opts['sc_crop_img_on_single']))
		$output['sc_crop_img_on_single'] = $input['sc_crop_img_on_single'];
		
	// validate welcome title
	$output['sc_welcome_title'] = trim($input['sc_welcome_title']);
	
	// validate welcome message
	$output['sc_welcome_msg'] = trim($input['sc_welcome_msg']);
	
	// validate site logo
	$output['sc_site_logo'] = $input['sc_site_logo'];
	
	// validate highlight category
	$output['sc_highlight_cat'] = $input['sc_highlight_cat'];
	
	// validate blog category
	$output['sc_blog_cat'] = $input['sc_blog_cat'];
	
	// validate image slider options
	$output['sc_slider_show_mobile'] = ($input['sc_slider_show_mobile'] == 1) ? true : false;
	$output['sc_slider_image_h'] = trim($input['sc_slider_image_h']);
	$output['sc_slider_speed'] = trim($input['sc_slider_speed']);
	$output['sc_slider_timeout'] = trim($input['sc_slider_timeout']);
	$output['sc_slider_show_nav'] = ($input['sc_slider_show_nav'] == 1) ? true : false;
	
	// validate site-tagline
	$output['sc_site_tagline'] = trim($input['sc_site_tagline']);
	
	// Validate social-icons
	$output['sc_social_icons'] = $input['sc_social_icons'];
	
	// Validate Copyright info
	$output['sc_copyright_note'] = trim($input['sc_copyright_note']);
	
	// Validate total blog posts
	$output['sc_total_blog_posts'] = $input['sc_total_blog_posts'];
	
	// Validate address
	$output['sc_address'] = trim($input['sc_address']);
	
	// Validate phone
	$output['sc_phone'] = $input['sc_phone'];
	
	// Validate email
	$output['sc_email'] = $input['sc_email'];
	
		
	return apply_filters( 'sc_antistar_theme_options_validate', $output, $input, $defaults );
}

/**
* Render options page
* @since 1.0
*/
function sc_render_option_page(){ ?>
	<style>
		#icon-sc_opt_pg { background:url('<?php echo get_template_directory_uri();?>/core/images/ico_screets_32.png') no-repeat; }
	</style>
	<div class="wrap">
		<?php screen_icon(); ?>
		<h2><?php printf( __('%s Options', 'antistar'), wp_get_theme() ); ?></h2>
		<?php settings_errors(); ?>
		
		<form method="post" action="options.php">
			<?php
				settings_fields( 'sc_antistar_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
		
	</div>
<?php }

/**
* Add options box
* @since 1.0
*/
function sc_add_opts_box() {
    // Slider Image link
	add_meta_box( 
        'screets_opts_101',
        __( 'Link', 'antistar' ),
        'sc_get_opts_box_slider',
        'slider',
		'normal',
		'core'
    );
	
	// Video Embed
	add_meta_box( 
        'screets_opts_embed',
        __( 'Video Embed', 'antistar' ),
        'sc_get_opts_box_video_embed',
        'post',
		'normal',
		'core'
    );
}

/**
* Video Embed (option box)
* @since 1.0
*/
function sc_get_opts_box_video_embed($post){
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'sc_honeypot' ); 
	
	$sc_embed_uri = get_post_meta($post->ID, 'sc_embed_uri', true);
	?>
	
	<p>
		<label for="sc_field_embed_uri"><?php _e('URL', 'antistar'); ?> <span class="description">(<?php _e('i.e.', 'antistar'); ?> http://vimeo.com/45569479)</span>:</label>
		<input type="text" name="sc_embed_uri" id="sc_field_embed_uri" value="<?php echo $sc_embed_uri; ?>" class="form-input-tip" autocomplete="off" style="width:90%"/>
	</p>
	
	<div class="description"><strong><?php _e('Supported sites', 'antistar'); ?></strong>:<br/>YouTube, Vimeo, blip.tv, Flickr, Twitter, Viddler, Hulu, Qik, Revision3, PollDaddy, Wordpress.tv, SmugMug, FunnyorDie... <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank"><?php _e('Full List', 'antistar');?></a></div>
	
<?php
}

/**
* Slider Link (option box)
* @since 1.0
*/
function sc_get_opts_box_slider($post){
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'sc_honeypot' ); 
	
	$sc_slider_uri = get_post_meta($post->ID, 'sc_slider_uri', true);
	$sc_slider_target = get_post_meta($post->ID, 'sc_slider_target', true);
	?>
	
	<p class="description"><?php _e('Enter the destination URL', 'antistar'); ?>:</p>
	
	<input type="text" name="sc_slider_uri" id="sc_field_slider_uri" value="<?php echo $sc_slider_uri; ?>" class="form-input-tip" autocomplete="off" style="width:200px"/>
	
	&nbsp;&nbsp;
	
	<label>
		<input type="checkbox" name="sc_slider_target" id="sc_slider_target" value="1" <?php checked($sc_slider_target); ?> />
		<?php _e('Open link in a new window/tab', 'antistar'); ?>
	</label>
	
	
	<?php
}


/**
* Save post
* @since 1.0
*/
function sc_save_post($post_id=null){
	
	// !important: its good for front-end of bbPress
	if(empty($post_id))
		return false;
		
	// verify if this is an auto save routine. 
	// If it is our form has not been submitted, so we dont want to do anything
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		return;
		
	// verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( @$_POST['sc_honeypot'], plugin_basename( __FILE__ ) ) )
		return;
	
	// Check permissions
	if ( 'page' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) )
			return;
	} else{
		if ( !current_user_can( 'edit_post', $post_id ) )
			return;
	}
	
	/**
	* Update post vars
	*/
	
	// Update embed uri
	add_post_meta($post_id, 'sc_embed_uri', esc_url($_POST['sc_embed_uri']), true) or update_post_meta($post_id, 'sc_embed_uri', esc_url($_POST['sc_embed_uri']));
	
	// Update slider uri
	add_post_meta($post_id, 'sc_slider_uri', esc_url($_POST['sc_slider_uri']), true) or update_post_meta($post_id, 'sc_slider_uri', esc_url($_POST['sc_slider_uri']));
	
	// Update slider uri target
	add_post_meta($post_id, 'sc_slider_target', $_POST['sc_slider_target'], true) or update_post_meta($post_id, 'sc_slider_target', $_POST['sc_slider_target']);
}

?>