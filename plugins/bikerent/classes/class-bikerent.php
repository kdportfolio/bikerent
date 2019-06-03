<?php
/**
 * Class for BikeRent Plugin
 *
 *
 * @category   WordPress_Plugin
 * @package    BikeRent
 * @author     Pooja Adani <contact@bikerentalmanager.com>
 * @link       https://bikerentalmanager.com/ *
 */

namespace BikeRent\Plugin\classes;

class WPBikeRent {
    
    public $current_language;
	
    /**
     * Initialize necessary actions
     */
    public function __construct(){
        add_action( 'wp_enqueue_scripts', array( $this, 'WPBikeRent_enqueue_script' ) );
        add_action( 'init', array( $this, 'WPBikeRent_shop_creation_cpt' ) );
        add_action( 'init', array( $this, 'WPBikeRent_tips_creation_cpt' ) );
        add_action( 'admin_head', array( $this, 'WPBikeRent_backend_css' ) );
        add_action( 'wp_ajax_nopriv_redirect_to_shop', array( $this, 'WPBikeRent_redirect_shop' ) );
        add_action( 'wp_ajax_redirect_to_shop', array( $this, 'WPBikeRent_redirect_shop' ) );
        add_action( 'wp_ajax_nopriv_call_search_results', array( $this, 'WPBikeRent_get_search_results' ) );
        add_action( 'wp_ajax_call_search_results', array( $this, 'WPBikeRent_get_search_results' ) );
        
        /*
         * Define language
         */
        $curr_lang = explode ( "-", get_bloginfo( 'language' ) );
        $this->current_language = ( isset( $curr_lang[0] ) ) ? $curr_lang[0] : 'en';
    }

    /**
    * Callback for including the style and script
    * Including the scripts and css of plugins
    *
    */
    public function WPBikeRent_enqueue_script() {       
        wp_register_style( 'slick_css',  plugin_dir_url( dirname(__FILE__) ) . 'assets/css/slick.css' );
        wp_register_style( 'slick_theme_css',  plugin_dir_url( dirname(__FILE__) ) . 'assets/css/slick-theme.css' );
        wp_register_style( 'responsive_css',  get_stylesheet_directory_uri() . '/css/responsive.css' );

        wp_enqueue_style ( 'slick_css' );
        wp_enqueue_style ( 'slick_theme_css' );

        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'my_slick_min', plugin_dir_url( dirname(__FILE__) ) . 'assets/js/slick.min.js?' );
        wp_enqueue_script( 'my_custom_script', plugin_dir_url( dirname(__FILE__) ) . 'assets/js/plugin-custom.js?time=' . time() );
        
        $current_language = explode ( "-", get_bloginfo( 'language' ) );		
            wp_enqueue_script( 'google_map_script', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCyRH54v1wvHHj404QlugjEz-jBkUaGIk8&language='.$current_language[0].'&libraries=places' );

        wp_localize_script( 'my_custom_script', 'my_ajax_object',
                        array(
                                'ajax_url' => admin_url( 'admin-ajax.php' ),
                                'site_url' => site_url() 
                            ) );
    }


    /**
     * Callback for Custom Post Type Shop
     * 
     * @throws \Exception
     */
    function WPBikeRent_shop_creation_cpt() {

            /* Set up shops labels */
            $labels = array(
                    'name'                  => 'Shops',
                    'singular_name'         => 'Shop',
                    'add_new'               => 'Add Shop',
                    'add_new_item'          => 'Add Shop',
                    'edit_item'             => 'Edit Shop',
                    'new_item'              => 'New Shop',
                    'all_items'             => 'All Shop',
                    'view_item'             => 'View Shop',
                    'search_items'          => 'Search Shop',
                    'not_found'             => 'No Shop Found',
                    'not_found_in_trash'    => 'No Shop found in Trash',
                    'parent_item_colon'     => '',
                    'menu_name'             => 'Shop',
            );

            /*
             * Register shop post type
             */
            $args = array(
                    'labels'            => $labels,
                    'public'            => true,
                    'has_archive'       => false,
                    'show_ui'           => true,
                    'capability_type'   => 'post',
                    'hierarchical'      => false,
                    'rewrite'           => array('slug' => 'shop'),
                    'query_var'         => true,
                    'show_in_rest'      => true,
                    'menu_icon'         => 'dashicons-randomize',
                    'supports'          => array(
                            'title',
                            'editor',
                            'excerpt',
                            'trackbacks',
                            'custom-fields',
                            'comments',
                            'revisions',
                            'thumbnail',
                            'author',
                            'page-attributes'
                    )
            );
            
            $register = register_post_type( 'shop', $args );
            
            /*
            * Error handler if post type not reegistered
            */
            if ( is_wp_error( $register ) ){
               throw new \Exception( 'Oops, something went wrong!!!' );
            }

            /*
             * Register category taxonomy
             */
            $bike_category = register_taxonomy( 'bike_category', 'shop',
                    array(
                            'hierarchical'  => true,
                            'label'         => 'Bike Category',
                            'query_var'     => true,
                            'rewrite'       => array( 'slug' => 'bike-category' )
                        ) );
            
            /*
            * Error handler if taxonomy does not created
            */
            if ( is_wp_error( $bike_category ) ){
               throw new \Exception( 'Oops, something went wrong!!!' );
            }
            
            /*
             * Register region taxonomy
             */
            $region_category = register_taxonomy('shop_region_category', 'shop',
                    array( 'hierarchical' => true,
                            'label'     => 'Region',
                            'query_var' => true,
                            'rewrite'   => array( 'slug' => 'region-category' )
                        ) );
            
            /*
            * Error handler if taxonomy does not created
            */
            if ( is_wp_error( $region_category ) ){
               throw new \Exception( 'Oops, something went wrong!!!' );
            }		

    }
    
    /**
     * Callback for Custom Post Type Guides
     * 
     * @throws \Exception
     */
    function WPBikeRent_tips_creation_cpt() {

            /* Set up guide labels */
            $labels = array(
                    'name'                  => 'Guides',
                    'singular_name'         => 'Guide',
                    'add_new'               => 'Add Guide',
                    'add_new_item'          => 'Add Guide',
                    'edit_item'             => 'Edit Guide',
                    'new_item'              => 'New Guide',
                    'all_items'             => 'All Guide',
                    'view_item'             => 'View Guide',
                    'search_items'          => 'Search Guide',
                    'not_found'             =>  'No Guide Found',
                    'not_found_in_trash'    => 'No Guide found in Trash',
                    'parent_item_colon'     => '',
                    'menu_name'             => 'Guide',
            );

            /* Register guide post type */
            $args = array(
                    'labels'            => $labels,
                    'public'            => true,
                    'has_archive'       => false,
                    'show_ui'           => true,
                    'capability_type'   => 'post',
                    'hierarchical'      => false,
                    'rewrite'           => array('slug' => 'guides'),
                    'query_var'         => true,
                    'menu_icon'         => 'dashicons-randomize',
                    'supports'          => array(
                            'title',
                            'editor',
                            'excerpt',
                            'trackbacks',
                            'custom-fields',
                            'comments',
                            'revisions',
                            'thumbnail',
                            'author',
                            'page-attributes'
                    )
            );
            
            /*
             * Register guide post type
             */
            $register_tips = register_post_type( 'guides', $args );
            
            /*
            * Error handler if guide post type does not created
            */
            if ( is_wp_error( $register_tips ) ){
                throw new \Exception( 'Oops, something went wrong!!!' );
            }

    }

    /**
     * Callback for get shop url when select shop.
     * Return the url of the selected shop
     *
     * @return string the shop url
     */
    function WPBikeRent_redirect_shop(){
            if( isset( $_POST['action'] ) && $_POST['action'] == 'redirect_to_shop' ){
                    $post_id = ( isset( $_POST['post_id'] ) && $_POST['post_id'] > 0 ) ? $_POST['post_id'] : 0;
                    echo $url = get_permalink( $post_id );
                    die();
            }
    }

    /**
    * Callback for search results page
    * Return the HTML of results return from API.
    *
    *
    * @return the HTML of results
    */
    function WPBikeRent_get_search_results(){
        
            if( !isset($_POST['location_name'])){
                throw new \Exception('Oops, something went wrong!!!'); 
            }
            
            $location_name = ( isset( $_POST['location_name'] ) ? $_POST['location_name'] : '' );
            $term = ( isset( $_POST['term'] ) ? $_POST['term'] : '' );
            $lat = ( isset( $_POST['lat'] ) ? $_POST['lat'] : '' );
            $lng = ( isset( $_POST['lng'] ) ? $_POST['lng'] : '' );
            $shop_img = '';

            $current_language = explode ( "-", get_bloginfo( 'language' ) );
            $remote_url = API_URL . "?term=" . $term . "&lat=" . $lat . "&lang=" . $current_language[0] . "&long=" . $lng . "&radius=250000";
            
            $response = wp_remote_get( $remote_url );
            
            $i = 0;
            
            if ( is_array( $response ) ) {
                
                    $body = json_decode( $response['body'] );

                    if( empty( $body->results ) ){
                            ?>
                            <div class="error-section">
                                <section id="page-not-found-section" class="page-not-found-section">
                                    <div class="container">
                                        <div class="page-not-font-content">
                                            <?php 
                                                $post_id = ( isset( $_POST['post_id'] ) && $_POST['post_id'] > 0 ) ? $_POST['post_id'] : 0;
                                            ?>
                                            <h1><?php echo get_post_meta( $post_id, '__no_bike_found_title', true ); ?></h1>
                                            <p><?php echo get_post_meta( $post_id, '__no_bike_found_subtitle', true ); ?></p>
                                        </div>
                                        <div class="page-not-found-wrap">
                                            <div class="paan-top">
                                                <div class="paan-top-wrap">
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/pan-top.png" alt="pan-top"/>
                                                </div>
                                            </div>
                                            <div class="paan-left">
                                                <span class="paan-left-wrap">
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/pan-left.png" alt="pan-left"/>
                                                </span>
                                            </div>
                                            <div class="cycle">
                                                <div class="cycle-img-wrap">
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/cycle.png" alt="cycle"/>
                                                </div>
                                            </div>
                                            <div class="grass">
                                                <div class="grass-img-wrap">
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/grass.png" alt="grass"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </div>
                            <?php
                    } else {
                            foreach( $body->results as $res ){
                                    $i++;
                                    ?>
                                    <div class="location-col location-item"
                                         data-id="<?php echo $i; ?>" 
                                         data-booking_url="<?php echo $res->booking_url; ?>" 
                                         data-post_id="<?php echo $res->post_id; ?>" 
                                         data-shop="<?php echo $res->shop_name; ?>" 
                                         data-shopname="<?php echo $res->shop_ns; ?>" 
                                         data-lat="<?php echo $res->latitude; ?>" 
                                         data-long="<?php echo $res->longitude; ?>" 
                                         data-key="0" >
                                            <div class="bike-thumb">
                                                    <?php
                                                    if( isset( $res->category_images ) ){
                                                            foreach( $res->category_images as $shop_img ){
                                                                ?>
                                                                <a href="#" >
                                                                    <img alt="bike-image" title="bike image" class="img-responsive bike-image" src="<?php echo $shop_img; ?>">
                                                                </a>
                                                                <?php
                                                            }
                                                    }
                        /* Below code is for review section, which we have to use in future */
                        /*
                                                    ?>
                                                    <span class="rent-review">
                                                                    <span class="rating" title="customer rating">
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star"></i>
                                                                                    <i class="fa fa-star-half star-half"></i>
                                                                    </span>
                                                                    <span title="customer reviews"><?php echo $res->customer_score; ?> reviews</span>
                                                    </span>
                                                    <?php
                                                    */
                        ?>
                                            </div>

                                            <div class="rent-info">
                                                    <span title="Bike Rent Price" class="bike-price">3 <span>€</span></span>

                                                    <div class="rent-distance">
                                                            <span class="rent-shop-name">
                                                                    <a title="<?php echo $res->shop_name; ?>" href="#"><?php echo $res->shop_name; ?></a>
                                                            </span>
                                                            <span class="distance">
                                                                    <i class="fa fa-map-marker"></i><?php echo $res->distance; ?> km
                                                            </span>
                                                    </div>
                                            </div>
                                    </div>
                                    <?php
                            }
                            ?>
                            ###
                            <?php
                            $term_all = explode( ',', $term );
                            foreach( $body->all_categories as $category ){
                                    ?>
                                    <li class="check-box">
                                            <input type="checkbox" <?php if( in_array( $category, $term_all ) ){ echo "checked"; } ?> id="ell-<?php echo $category; ?>" name="term" value="<?php echo $category; ?>">
                                            <label for="ell-<?php echo $category; ?>"><?php echo $category; ?></label>
                                    </li>
                                    <?php
                            }
                    }
            }

            die();
    }

    function WPBikeRent_backend_css(){
        ?>
        <style>
        .wrap .misha_upload_image_button img {
                height: 50px !important;
                margin-bottom: 20px;
        }
        </style>
        <?php
    }
	
}
