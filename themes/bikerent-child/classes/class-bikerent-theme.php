<?php

class WPBikeRent_Theme {
	
	/*
	* construct function
	*/
	public function __construct()
        {
		add_action( 'wp_enqueue_scripts', array($this, 'WPBikeRent_Theme_enqueue_script'));
		add_action( 'admin_enqueue_scripts', array($this, 'WPBikeRent_Theme_uploadmedia'));
		add_action('wp_enqueue_scripts', array($this, 'WPBikeRent_Theme_load_script'));
		add_action( 'add_meta_boxes', array($this, 'WPBikeRent_Theme_homepage_meta_box'));
		add_action( 'add_meta_boxes', array($this, 'WPBikeRent_Theme_search_resultpage_meta_box'));
		add_action('save_post', array($this, 'WPBikeRent_Theme_save_meta_value'));
		add_action( 'admin_menu', array($this, 'WPBikeRent_Theme_menu_page'));
		add_action("admin_menu", array($this, 'WPBikeRent_Theme_menu_item'));
		add_action('admin_init', array($this, 'WPBikeRent_Theme_settings'));	
		add_action( 'admin_init', array($this, 'example_insert_category'));
        }
	
	
	/*
	* Include Parent Theme Style
	*/
	
	function  WPBikeRent_Theme_enqueue_script() {
		wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	}
	
	/*
	* Added Media Upload Section
	* 
	*/
	function WPBikeRent_Theme_uploadmedia() {
		if ( ! did_action( 'wp_enqueue_media' ) ) {
			wp_enqueue_media();
		}
	 
		wp_enqueue_script( 'custom_js', get_stylesheet_directory_uri().'/js/custom.js');
	}
	
	/*
	*
	* Including The Custom Js and Css
	*/
	function WPBikeRent_Theme_load_script(){
		
		//wp_enqueue_script( 'jquery_custom_js', get_stylesheet_directory_uri().'/js/jquery.min.js');
		wp_enqueue_script( 'jquery_loadz_js', 'https://cdn.jsdelivr.net/npm/lozad/dist/lozad.min.js');
		wp_enqueue_script( 'custom_js', get_stylesheet_directory_uri().'/js/custom.js');
		
		wp_register_style( 'styles_css',  get_stylesheet_directory_uri().'/css/styles.css' );
		wp_register_style( 'responsive_css',  get_stylesheet_directory_uri().'/css/responsive.css' );
		
		wp_enqueue_style ( 'styles_css' );
		wp_enqueue_style ( 'responsive_css' );
		
		$map_icon = wp_get_attachment_image_src( get_option('img_map_logo'), 'full' );
		$img_map_logo_hover = wp_get_attachment_image_src( get_option('img_map_logo_hover'), 'full' );
		
		wp_localize_script( 'custom_js', 'my_ajax_objects',
			array( 'map_icon' => $map_icon[0] ,
				'map_icon_hover' => $img_map_logo_hover[0]
			) 
		);
		
	}
	
	/*
	*
	* Add Metabox For Home Page
	*/
	function WPBikeRent_Theme_homepage_meta_box()
	{
		if ( 'template-home.php' == get_post_meta( get_the_ID(), '_wp_page_template', true ) ) {
			add_meta_box( 'movie_review_meta_box', 'Banner Section', array($this, 'WPBikeRent_Theme_display_meta_box'), 'page', 'normal', 'high' );
		}
	}
        
        /*
	*
	* Add Metabox For Search Result Page
	*/
	function WPBikeRent_Theme_search_resultpage_meta_box()
	{
		if ( 'template-search.php' == get_post_meta( get_the_ID(), '_wp_page_template', true ) ) {
			add_meta_box( 'movie_review_meta_box', 'Search page content', array($this, 'WPBikeRent_Theme_display_search_resultpage_meta_box'), 'page', 'normal', 'high' );
		}
	}
	
	/*
	*
	* Display Meta Options In Home Page
	*/
	function WPBikeRent_Theme_display_meta_box(){
		wp_nonce_field(basename(__FILE__),'nonce_name');
		$value=get_post_meta(get_the_ID(),'__banner_title',true);
                
		$meta_key = 'second_featured_img';
		$img_for_first_step = 'img_for_first_step';
		$img_for_second_step = 'img_for_second_step';
		$img_for_third_step = 'img_for_third_step';
		
		$main_section_heading = get_post_meta(get_the_ID(),'__main_section_heading',true);
		$first_step_how_we_work = get_post_meta(get_the_ID(),'__first_step_how_we_work',true);
		$second_step_how_we_work = get_post_meta(get_the_ID(),'__second_step_how_we_work',true);
		$third_step_how_we_work = get_post_meta(get_the_ID(),'__third_step_how_we_work',true);            
		?>
		<table>
			<tr>
				<td><label for="my_meta_box_text">Banner Title</label></td>
				<td><input type="text" name="my_meta_box_text" id="my_meta_box_text" value="<?php echo $value; ?>" /></td>
			</tr>
			
			<tr>
				<td><label for="my_meta_box_text">Banner Image</label></td>
				<td><?php echo $this->WPBikeRent_Theme_image_uploader_field( $meta_key, get_post_meta(get_the_ID(), $meta_key, true) ); ?></td>
			</tr>
			
			<tr>
				<td><label for="main_section_heading">Main section Headeing</label></td>
				<td><input type="text" name="main_section_heading" id="main_section_heading" value="<?php echo $main_section_heading; ?>" /></td>
			</tr>
			
			<tr>
				<td><label for="first_step_how_we_work">Description of first step how we work</label></td>
				<td><textarea name="first_step_how_we_work" id="first_step_how_we_work"><?php echo $first_step_how_we_work; ?></textarea> </td>                       
			</tr>
			
			<tr>
				<td><label for="img_for_first_step">Image for first step</label></td>
				<td><?php echo $this->WPBikeRent_Theme_image_uploader_field( $img_for_first_step, get_post_meta(get_the_ID(), $img_for_first_step, true) ); ?></td>
			</tr>
			
			<tr>
				<td><label for="second_step_how_we_work">Description of second step how we work</label></td>
				<td><textarea name="second_step_how_we_work" id="second_step_how_we_work"><?php echo $second_step_how_we_work; ?></textarea> </td>                       
			</tr>
			
			<tr>
				<td><label for="img_for_second_step">Image for second step</label></td>
				<td><?php echo $this->WPBikeRent_Theme_image_uploader_field( $img_for_second_step, get_post_meta(get_the_ID(), $img_for_second_step, true) ); ?></td>
			</tr>
			
			<tr>
				<td><label for="third_step_how_we_work">Description of third step how we work</label></td>
				<td><textarea name="third_step_how_we_work" id="third_step_how_we_work"><?php echo $third_step_how_we_work; ?></textarea> </td>                       
			</tr>
			
			<tr>
				<td><label for="img_for_third_step">Image for third step</label></td>
				<td><?php echo $this->WPBikeRent_Theme_image_uploader_field( $img_for_third_step, get_post_meta(get_the_ID(), $img_for_third_step, true) ); ?></td>
			</tr>
		</table>
	<?php	
	}
        
        /*
	*
	* Display Meta Options In Home Page
	*/
	function WPBikeRent_Theme_display_search_resultpage_meta_box(){
		wp_nonce_field(basename(__FILE__),'nonce_name');		
		$no_bike_found_title = get_post_meta(get_the_ID(),'__no_bike_found_title',true);            
		$no_bike_found_subtitle = get_post_meta(get_the_ID(),'__no_bike_found_subtitle',true);            
		?>
		<table>
			<tr>
				<td><label for="">No bike found message title</label></td>
				<td><textarea name="no_bike_found_title" id="no_bike_found_title"><?php echo $no_bike_found_title; ?></textarea></td>
			</tr>		
			<tr>
				<td><label for="">No bike found message subtitle</label></td>
				<td><textarea name="no_bike_found_subtitle" id="no_bike_found_subtitle"><?php echo $no_bike_found_subtitle; ?></textarea></td>
			</tr>		
			
		</table>
	<?php	
	}
	
	/*
	*
	* Save Meta Options In Page
	*/
	function WPBikeRent_Theme_save_meta_value(){	
		$meta_key = 'second_featured_img';
		$img_for_first_step = 'img_for_first_step';
		$img_for_second_step = 'img_for_second_step';
		$img_for_third_step = 'img_for_third_step';
		
		
		update_post_meta( get_the_ID(), $meta_key, $_POST[$meta_key] );
		update_post_meta( get_the_ID(), $img_for_first_step, $_POST[$img_for_first_step] );
		update_post_meta( get_the_ID(), $img_for_second_step, $_POST[$img_for_second_step] );
		update_post_meta( get_the_ID(), $img_for_third_step, $_POST[$img_for_third_step] );
		update_post_meta(get_the_ID(),'__banner_title',sanitize_text_field($_POST['my_meta_box_text']));

		update_post_meta(get_the_ID(),'__main_section_heading',sanitize_text_field($_POST['main_section_heading']));
		update_post_meta(get_the_ID(),'__first_step_how_we_work',sanitize_text_field($_POST['first_step_how_we_work']));
		update_post_meta(get_the_ID(),'__second_step_how_we_work',sanitize_text_field($_POST['second_step_how_we_work']));
		update_post_meta(get_the_ID(),'__third_step_how_we_work',sanitize_text_field($_POST['third_step_how_we_work']));
                
                /* Search page fields */
		update_post_meta(get_the_ID(),'__no_bike_found_title',sanitize_text_field($_POST['no_bike_found_title']));
		update_post_meta(get_the_ID(),'__no_bike_found_subtitle',sanitize_text_field($_POST['no_bike_found_subtitle']));
	}
	
	/*
	*
	* Display Saved Media of Meta Box
	*/
	function WPBikeRent_Theme_image_uploader_field( $name, $value = '') {
		$image = ' button">Upload image';
		$image_size = 'full';
		$display = 'none';
	 
		if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {
			$image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;height: 100px;" />';
			$display = 'inline-block';
		} 
	 
		return '
		<div>
			<a href="#" class="misha_upload_image_button' . $image . '</a>
			<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
			<a href="#" class="misha_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a>
		</div>';
	}
	
	/**
	* Theme option creation
	*/
	function WPBikeRent_Theme_menu_page() {   
		add_menu_page( 'Theme options', 'Theme options', 'manage_options', 'theme-option-page', array($this, 'WPBikeRent_Theme_options_callback'),'', 26 );    
	}
	
	/**
	* Get Theme options & Display in Option Page
	*/
	function WPBikeRent_Theme_options_callback() {
		?>
		<div class="wrap">
			<h1>Theme Options Page</h1>
			<form method="post" action="options.php">
				<?php
				settings_fields("theme-options-grp");
				
				do_settings_sections("theme-options");
				submit_button();
				?>
			</form>
		</div>
	<?php
	}
	
	/*
	*
	* Add Theme Option Page Menu
	*/
	function WPBikeRent_Theme_menu_item() {
		add_theme_page("Theme Customization", "Theme Customization", "manage_options", "theme-options", "theme_option_page", null, 99);
	}
	
	/**
	* Display Twitter Input Field in Option Page
	*/
	function WPBikeRent_Theme_twitter_element(){
	?>
		<input type="text" name="test_twitter_url" id="test_twitter_url" value="<?php echo get_option('test_twitter_url'); ?>" />
	<?php
	}
	
	/**
	* Display Instagram Input Field in Option Page
	*/
	function WPBikeRent_Theme_instagram_element(){
	?>
		<input type="text" name="instagram_url" id="instagram_url" value="<?php echo get_option('instagram_url'); ?>" />
	<?php
	}
	
	/**
	* Display Facebook Input Field in Option Page
	*/
	function WPBikeRent_Theme_facebook_element(){
	?>
		<input type="text" name="facebook_url" id="facebook_url" value="<?php echo get_option('facebook_url'); ?>" />
	<?php
	}
	
	/**
	* Display LinkedIn Input Field in Option Page
	*/
	function WPBikeRent_Theme_LinkedIn_element(){
	?>
		<input type="text" name="linkdin_url" id="linkdin_url" value="<?php echo get_option('linkdin_url'); ?>" />
	<?php
	}
	
	/**
	* Display Saved Media Url For Display
	*/
	function WPBikeRent_Theme_logo_display(){    
		$testlogo1 = 'img_site_logo';
		echo $this->WPBikeRent_Theme_image_uploader_field( $testlogo1, get_option('img_site_logo') );
	}
	
	/**
	* Display Saved Media Url For Map icon
	*/
	function WPBikeRent_Theme_map_icon(){    
		$testlogo2 = 'img_map_logo';
		echo $this->WPBikeRent_Theme_image_uploader_field( $testlogo2, get_option('img_map_logo') );
	}
	
	/**
	* Display Saved Media Url For Map icon hover
	*/
	function WPBikeRent_Theme_map_icon_hover(){  
		$testlogo2 = 'img_map_logo_hover';
		echo $this->WPBikeRent_Theme_image_uploader_field( $testlogo2, get_option('img_map_logo_hover') );
	}
	
	/*
	*
	* Registered The Themes Options and Displayed in Theme page
	*/
	function WPBikeRent_Theme_settings() {
		add_option('first_field_option', 1);
		
		add_settings_section('first_section', 'Theme Options Section', '', 'theme-options');
		
		add_settings_field("logo", "Logo", array($this, 'WPBikeRent_Theme_logo_display'), "theme-options", "first_section");
		register_setting( 'theme-options-grp', 'img_site_logo');
		
		add_settings_field("map_icon", "Map Icon", array($this, 'WPBikeRent_Theme_map_icon'), "theme-options", "first_section");
		register_setting( 'theme-options-grp', 'img_map_logo');
		
		add_settings_field("map_icon_hover", "Map Icon Hover", array($this, 'WPBikeRent_Theme_map_icon_hover'), "theme-options", "first_section");
		register_setting( 'theme-options-grp', 'img_map_logo_hover');
		
		add_settings_field('twitter_url', 'Twitter Url', array($this, 'WPBikeRent_Theme_twitter_element'), 'theme-options', 'first_section');
		register_setting( 'theme-options-grp', 'test_twitter_url');
		
		add_settings_field('instagram_url', 'Instagram Url', array($this, 'WPBikeRent_Theme_instagram_element'), 'theme-options', 'first_section');
		register_setting( 'theme-options-grp', 'instagram_url');
		
		add_settings_field('facebook_url', 'Facebook Url', array($this, 'WPBikeRent_Theme_facebook_element'), 'theme-options', 'first_section');
		register_setting( 'theme-options-grp', 'facebook_url');
		
		add_settings_field('linkdin_url', 'LinkedIn Url', array($this, 'WPBikeRent_Theme_LinkedIn_element'), 'theme-options', 'first_section');
		register_setting( 'theme-options-grp', 'linkdin_url');
		
	}
	
	/*
	*
	* Create deafult category for shop
	*/
	function example_insert_category() {
		$default_tems = array( 'mtb','road','hybrid','ebike','kids','tandem' );
		
		foreach($default_tems as $terms){
			if(!term_exists( $terms ,'bike_category')){
				wp_insert_term( $terms ,'bike_category' );
			}
		}
	}
	
}

?>