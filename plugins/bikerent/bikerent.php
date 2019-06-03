<?php

/**

* Plugin Name:       Bike Rent

* Version:           1.0.3

* Author:            Pooja Adani

*/



namespace BikeRent\Plugin;



/* 

* If this file is called directly or plugin is already defined, abort. 

*/

if (!defined('WPINC')) {

	die;

}



/* 

* Include constant variable files 

*/

include_once( 'constant.php' );



/* 

* Include class Bikerent 

*/

include_once( 'classes/class-bikerent.php' );

include_once( 'classes/class-rest-API.php' );



/* 

Declare Classes

*/

use BikeRent\Plugin\classes\WPBikeRent;

use BikeRent\Plugin\classes\WPBikeRent_API;



new WPBikeRent();

new WPBikeRent_API();