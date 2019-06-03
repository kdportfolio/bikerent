<?php

/**

 * Understrap Child functions and definitions file

 *

 * @package understrap-child

 */



/**

* Begins execution of the Bike Rent Child Theme.

*/

function initialize_bikerent_theme() {

	global $bike_rent;



	/* Declare Class of Permalink Manager */

	include_once( 'classes/class-bikerent-theme.php' );

	new WPBikeRent_Theme();


  /* Need to put this in its own function and ensure Polylang plugin is installed */
	pll_register_string( 'select-location', 'Where', 'bike.rent' );
	pll_register_string( 'select-location-error', 'Please select location', 'bike.rent' );
	pll_register_string( 'select-types', 'All types', 'bike.rent' );
	pll_register_string( 'button-search', 'SEARCH', 'bike.rent' );	
	pll_register_string( 'title-banner', 'title-banner', 'bike.rent');
	pll_register_string( 'title-how-we-work', 'title-how-we-work', 'bike.rent');
	pll_register_string( 'text-how-we-work-1', 'text-how-we-work-1', 'bike.rent', true );
	pll_register_string( 'text-how-we-work-2', 'text-how-we-work-2', 'bike.rent', true );
	pll_register_string( 'text-how-we-work-3', 'text-how-we-work-3', 'bike.rent', true );
}


initialize_bikerent_theme();



?>
