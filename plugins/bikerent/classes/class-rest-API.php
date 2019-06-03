<?php
/**
 * Class for BikeRent REST API
 *
 *
 * @category   WordPress_Plugin
 * @package    BikeRent
 * @author     Pooja Adani <contact@bikerentalmanager.com>
 * @link       https://bikerentalmanager.com/ *
 */
namespace BikeRent\Plugin\classes;

class WPBikeRent_API {
    
        /*
        * Initialize necessary actions
        */
        public function __construct(){
            add_action( 'rest_api_init', array( $this, 'WPBikeRent_add_shop_API' ) );
            add_action( 'rest_api_init', array( $this, 'WPBikeRent_new_add_shop_API' ) );
            add_action( 'rest_api_init', array( $this, 'WPBikeRent_get_shop_API' ) );
            add_action( 'rest_api_init', array( $this, 'WPBikeRent_update_shop_API' ) );
            add_action( 'rest_api_init', array( $this, 'WPBikeRent_delete_shop_API' ) );
            add_action( 'rest_api_init', array( $this, 'WPBikeRent_deactivate_shop_API' ) );

            // Correct API to create a shop
            add_action( 'rest_api_init', array( $this, 'WPBikeRent_create_shop_API' ) );

            // Uncomment this to enforce security
            // add_action( 'rest_api_init', array( $this, 'rest_only_for_authorized_users'), 99);		
        }
        
        /**
         * Check if user is logged in
         * 
         * @param type $wp_rest_server
         */
        function rest_only_for_authorized_users( $wp_rest_server ){
            if ( !is_user_logged_in() ) {
                wp_die( 'User must be logged in' );
            }
        }

        /**
	* Added the post for shop CPT using REST API
	*/
	function WPBikeRent_add_shop_API() {
		register_rest_route(
			'bikerent/v1',
			'/shop/add',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'WPBikeRent_add_shop_callback' ),
			)
		);
	}

	/*
	* Added the post for shop CPT using REST API ( New Function for create shop )
	*/
	function WPBikeRent_new_add_shop_API() {
		register_rest_route(
			'bikerent/v1',
			'/shop/new_add',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'WPBikeRent_new_add_shop_callback' ),
			)
		);
	}
	/*
	* Added the post for shop CPT using REST API ( New Function for create shop )
	*/
	function WPBikeRent_create_shop_API() {
		register_rest_route(
			'bikerent/v1',
			'/shop',
			array(
				'methods' => 'POST',
				'callback' => array( $this, 'WPBikeRent_create_shop_callback' ),
			)
		);
	}

	/*
	* Get the post for shop CPT using REST API
	*
	*/
	function WPBikeRent_get_shop_API() {
		register_rest_route(
			'bikerent/v1',
			'/shop/get',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'WPBikeRent_get_shop_callback' ),
			)
		);
	}

	/*
	* Update the post for shop CPT using REST API
	*/
	function WPBikeRent_update_shop_API() {
		register_rest_route(
			'bikerent/v1',
			'/shop/update',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'WPBikeRent_update_shop_callback' ),
			)
		);
	}

	/*
	* Delete the post for shop CPT using REST API
	*/
	function WPBikeRent_delete_shop_API() {
		register_rest_route(
			'bikerent/v1',
			'/shop/delete',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'WPBikeRent_delete_shop_callback' ),
			)
		);
	}

	/*
	* Deactivate the post for shop CPT using REST API
	*/
	function WPBikeRent_deactivate_shop_API() {
		register_rest_route(
			'bikerent/v1',
			'/shop/deactivate',
			array(
				'methods' => 'GET',
				'callback' => array( $this, 'WPBikeRent_deactivate_shop_callback' ),
			)
		);
	}

        /**
         * Callback function Added the post for shop CPT
         * 
         * @param type $data - Get the parameters from API
         * @return \WP_REST_Response
         */
	function WPBikeRent_add_shop_callback( $data ) {

		/* Collect parameters from the URL */
		$title          = $data->get_param( 'title' );
                $img            = $data->get_param( 'image' );
		$lang           = $data->get_param( 'lang' );

		/* Explode the multiple string from parameters. */
		$shop_cat       = explode( ',', $data->get_param( 'shop_cat' ) );
		$shop_region    = explode( ',', $data->get_param( 'shop_region' ) );

		/* Creating array of translation pages and terms */
		$pagetranslations   = array();
		$termtranslations   = array();
		$regiontranslations = array();

		/* Get All Languages */
		$languages = pll_the_languages( array( 'raw'=>1 ) );

		foreach( $languages as $post_lang ){
			$res = wp_insert_post(
					array(
						'post_title'    => $title,
						'post_type'     => 'shop',
						'post_status'   => 'publish',
						)
					);

			pll_set_post_language( $res, $post_lang['slug'] );

			$pagetranslations[$post_lang['slug']] = $res;

			foreach( $shop_cat as $cat ){
				foreach( $languages as $post_lang ){
					$term_return = wp_set_object_terms( $res, $cat, 'shop_category', true );
					pll_set_term_language( $term_return[0], $post_lang['slug'] );
					$termtranslations[$post_lang['slug']] = $term_return[0];
				}
				pll_save_term_translations( $termtranslations );

			}

			foreach( $shop_region as $region ){
				foreach( $languages as $post_lang ){
					$reg_term_return = wp_set_object_terms( $res, $region, 'shop_region_category', true );
					pll_set_term_language( $reg_term_return[0], $post_lang['slug'] );
					$regiontranslations[$post_lang['slug']] = $reg_term_return[0];
				}
				pll_save_term_translations( $regiontranslations );

			}

		}

		pll_save_post_translations( $pagetranslations );

		return new \WP_REST_Response( $res, 200 );

	}

        /**
         * Callback function Added the post for shop CPT ( New Callback Function for add shop )
         * 
         * @param type $data - Get the params from API
         * @return \WP_REST_Response
         */
	function WPBikeRent_new_add_shop_callback( $data ) {

		/* Collect the params from the URL */
		$title              = $data->get_param( 'title' );
                $content            = $data->get_param( 'content' );
		$shop_name          = $data->get_param( 'shop_name' );
		$contact_email      = $data->get_param( 'contact_email' );
		$contact_phone      = $data->get_param( 'contact_phone' );
		$shop_namespace     = $data->get_param( 'shop_namespace' );
		$data_center        = $data->get_param( 'data_center' );
		$latitude           = $data->get_param( 'latitude' );
		$longitude          = $data->get_param( 'longitude' );
		$opening_times      = $data->get_param( 'opening_times' );
		$currency_code      = $data->get_param( 'currency_code' );
		$country            = $data->get_param( 'country' );
		$country_code       = $data->get_param( 'country_code' );
		$region             = $data->get_param( 'region' );
		$sub_region         = $data->get_param( 'sub_region' );
		$address            = $data->get_param( 'address' );
		$shop_description   = $data->get_param( 'shop_description' );

		/* Creating array of translation pages and terms */
		$pagetranslations   = array();
		$response           = array();

		/* Create Array of post meta params */
		$post_meta_data = array(
			'shop_name'         => $shop_name,
			'contact_email'     => $contact_email,
			'contact_phone'     => $contact_phone,
			'shop_namespace'    => $shop_namespace,
			'data_center'       => $data_center,
			'latitude'          => $latitude,
			'longitude'         => $longitude,
			'opening_times'     => $opening_times,
			'currency_code'     => $currency_code,
			'country'           => $country,
			'country_code'      => $country_code,
			'region'            => $region,
			'sub_region'        => $sub_region,
			'address'           => $address,
			'shop_description'  => $shop_description
		);

		/* Get All Languages */
		$languages = pll_the_languages( array( 'raw'=>1 ) );

		foreach( $languages as $post_lang ){
			$permalink_manager_uris = get_option( 'permalink-manager-uris' );
			$res = wp_insert_post(
                                array(
                                        'post_title' => $title,
                                        'post_type' => 'shop',
                                        /*'post_status'=> 'publish',*/
                                        'post_content' => $content,
                                        )
                                );

			pll_set_post_language( $res, $post_lang['slug'] );

			$pagetranslations[$post_lang['slug']] = $res;

			foreach( $post_meta_data as $meta_key => $meta_value ){
				add_post_meta( $res, $meta_key, $meta_value, true );
			}

			$postslugs = str_replace( " ", "-", $title );
			$new_uri = $country . '/' . $region . '/' . $sub_region . '/' . $postslugs;

			$permalink_manager_uris[$res] = $new_uri;
			update_option( 'permalink-manager-uris', $permalink_manager_uris );

			$response[$post_lang['slug']] = array( 'post_id' => $res, 'permalink' => $new_uri);

		}
                
                if( isset( $response ) ){
                    $custom_fields_arr = array(
                            'shop_name'         => $shop_name,
                            'contact_email'     => $contact_email,
                            'contact_phone'     => $contact_phone,
                            'shop_namespace'    => $shop_namespace,
                            'data_center'       => $data_center,
                            'latitude'          => $latitude,
                            'longitude'         => $longitude,
                            'opening_times'     => $opening_times,
                            'currency_code'     => $currency_code,
                            'country'           => $country,
                            'country_code'      => $country_code,
                            'region'            => $region,
                            'sub_region'        => $sub_region,
                            'address'           => $address,
                            'shop_description'  => $shop_description
                    );
                    
                    $success_res = array(
                            'title'             => $title,
                            'content'           => $content,
                            'custom_fields'     => $custom_fields_arr,
                            'language_config'   => $response
                    );
                }
                
		pll_save_post_translations( $pagetranslations );
                return new \WP_REST_Response( $success_res, 200 );
	}

        /**
         * Callback function Added the post for shop CPT ( New Callback Function for create shop )
         * 
         * @param type $request - Get the params from API
         * @return \WP_REST_Response
         */
	function WPBikeRent_create_shop_callback( $request ) {
		
                $data                   = $request->get_json_params();

		/* Collect the params from the URL */
		$title                  = $data['title'];
                $content                = $data['content'];
		$custom_fields          = $data['custom_fields'];
		$shop_name              = $custom_fields['shop_name'];
		$contact_email          = $custom_fields['contact_email'];
		$contact_phone          = $custom_fields['contact_phone'];
		$shop_namespace         = $custom_fields['shop_namespace'];
		$data_center            = $custom_fields['data_center'];
		$latitude               = $custom_fields['latitude'];
		$longitude              = $custom_fields['longitude'];
		$opening_times          = $custom_fields['opening_times'];
		$currency_code          = $custom_fields['currency_code'];
		$country                = $custom_fields['country'];
		$country_code           = $custom_fields['country_code'];
		$region                 = $custom_fields['region'];
		$sub_region             = $custom_fields['sub_region'];
		$address                = $custom_fields['address'];
		$shop_description       = $custom_fields['shop_description'];

		$language_config        = $data['language_config'];

		/* Creating array of translation pages and terms */
		$pagetranslations   = array();
		$response           = array();

		/* Create Array of post meta params */
		$post_meta_data = array(
			'shop_name'         => $shop_name,
			'contact_email'     => $contact_email,
			'contact_phone'     => $contact_phone,
			'shop_namespace'    => $shop_namespace,
			'data_center'       => $data_center,
			'latitude'          => $latitude,
			'longitude'         => $longitude,
			'opening_times'     => $opening_times,
			'currency_code'     => $currency_code,
			'country'           => $country,
			'country_code'      => $country_code,
			'region'            => $region,
			'sub_region'        => $sub_region,
			'address'           => $address,
			'shop_description'  => $shop_description
		);

		/* Get All Languages */
		$languages = pll_the_languages( array( 'raw' => 1 ) );

		foreach( $languages as $post_lang ){
			$permalink_manager_uris = get_option('permalink-manager-uris');
			$new_uri = $language_config[$post_lang['slug']]['permalink'];

                        /* Only proceed if the language permalink has been supplied */
			if( !isset( $new_uri ) ){
				continue;
			}

			$res = wp_insert_post(
                                array(
                                        'post_title'    => $title,
                                        'post_type'     => 'shop',
                                        /*'post_status' => 'publish',*/
                                        'post_content'  => $content,
                                        )
                                );

			pll_set_post_language( $res, $post_lang['slug'] );

			$pagetranslations[$post_lang['slug']] = $res;

			foreach( $post_meta_data as $meta_key => $meta_value ){
				add_post_meta( $res, $meta_key, $meta_value, true );
			}

                        // Using supplied permalink from JSON payload
			// $postslugs = str_replace(" ","-",$title);
			// $new_uri = $country.'/'.$region.'/'.$sub_region.'/'.$postslugs;

			$permalink_manager_uris[$res] = $new_uri;
			update_option( 'permalink-manager-uris', $permalink_manager_uris );

			$response[$post_lang['slug']] = array( 'post_id' => $res, 'permalink' => $new_uri );

		}

		if(isset($response)){
                        $custom_fields_arr = array(
                                'shop_name'         => $shop_name,
                                'contact_email'     => $contact_email,
                                'contact_phone'     => $contact_phone,
                                'shop_namespace'    => $shop_namespace,
                                'data_center'       => $data_center,
                                'latitude'          => $latitude,
                                'longitude'         => $longitude,
                                'opening_times'     => $opening_times,
                                'currency_code'     => $currency_code,
                                'country'           => $country,
                                'country_code'      => $country_code,
                                'region'            => $region,
                                'sub_region'        => $sub_region,
                                'address'           => $address,
                                'shop_description'  => $shop_description
                        );
                        $success_res = array(
                                'title'             => $title,
                                'content'           => $content,
                                'custom_fields'     => $custom_fields_arr,
                                'language_config'   => $response
                        );
		}

		pll_save_post_translations( $pagetranslations );

		return new \WP_REST_Response( $success_res, 200 );
	}

        /**
         * Callback function get the post for shop CPT
         * 
         * @param type $data - Get the params from API
         * @return \BikeRent\Plugin\classes\WP_REST_Response
         */
	function WPBikeRent_get_shop_callback( $data ) {
		/* Collect the params from the URL */
		$ID = $data->get_param( 'id' );
		$res = get_post( $ID );

		return new WP_REST_Response( $res, 200 );
	}

        /**
         * Callback function Update the post for shop CPT
         * 
         * @param type $data - Get the params from API
         * @return \BikeRent\Plugin\classes\WP_REST_Response
         */
	function WPBikeRent_update_shop_callback( $data ) {

		/* Collect the params from the URL */
		$ID             = $data->get_param( 'id' );
		$title          = $data->get_param( 'title' );
                $lang           = $data->get_param( 'lang' );

		$my_post = array(
			'ID'            => $ID,
			'post_title'    => $title,
			'post_type'     => 'shop'
		);

		$res = wp_update_post( $my_post );

		/* Adding shop category to post */
		$shop_cat = explode( ',', $data->get_param( 'shop_cat' ) );
		foreach( $shop_cat as $cat ){
			$term_return = wp_set_object_terms( $ID, $cat, 'shop_category', true );
			pll_set_term_language( $term_return[0], $lang );
		}

		/* Adding region to post */
		$shop_region = explode( ',', $data->get_param( 'shop_region' ) );
		foreach( $shop_region as $region ){
			$reg_term_return = wp_set_object_terms( $ID, $region, 'shop_region_category', true );
			pll_set_term_language( $reg_term_return[0], $lang );
		}

		return new WP_REST_Response( $res, 200 );
	}

        /**
         * Callback function Delete the post for shop CPT
         * 
         * @param type $data - Get the params from API
         * @return \BikeRent\Plugin\classes\WP_REST_Response
         */
	function WPBikeRent_delete_shop_callback( $data ) {

		/* Collect the params from the URL */
		$ID = $data->get_param( 'id' );
		$res = wp_delete_post( $ID );

		return new WP_REST_Response( $res, 200 );
	}

        /**
         * Callback function Deactivate the post for shop CPT
         * 
         * @param type $data - Get the params from API
         * @return \BikeRent\Plugin\classes\WP_REST_Response
         */
	function WPBikeRent_deactivate_shop_callback( $data ) {

		/* Collect the params from the URL */
		$ID = $data->get_param( 'id' );

		$my_post = array(
			'ID'            => $ID,
			'post_status'   => 'draft'
		);

		$res = wp_update_post( $my_post );

		return new WP_REST_Response( $res, 200 );

	}
}

?>
