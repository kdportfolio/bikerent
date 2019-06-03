<?php
/**
 * WordPress Plugin for the calendar of Bike Rental Manager
 *
 * PHP version 5.6
 *
 * @category   WordPress_Plugin
 * @package    BikeRentalManager
 * @subpackage Calendar
 * @author     Bike Rental Manager <contact@bikerentalmanager.com>
 * @license    GPLv2 https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link       https://bikerentalmanager.com/
 *
 * Plugin Name: Bike Rental Manager Calendar
 * Description: Putting your Online Booking on your Website. Use the shortcode [bikerentalcalendar id="yourshopId" zone="US or EU"]. Example [bikerentalcalendar id="johnsbike" zone="US"] More info <a href="https://bikerentalmanager.freshdesk.com/support/solutions/articles/42000026285-getting-started-putting-your-online-booking-on-your-website">here</a>.
 * Version: 1.0.0
 * Author: Bike Rental Manager
 * Author URI: https://bikerentalmanager.com/
 * Licence GPLv2 or later
 * Text Domain: bike-rental-manager-calendar
 */

namespace BikeRentalManager\Calendar;

defined('ABSPATH') or die('No script kiddies please!');

define(__NAMESPACE__ . '\PLUGIN_PATH', plugin_dir_path(__FILE__));

add_shortcode('bikerentalcalendar', __NAMESPACE__ . '\bikerm_shortcode_callback');
add_shortcode('onlinebooking', __NAMESPACE__ . '\bikerm_shortcode_callback2');

/**
 * Callback for the shortcode bikerentalcalendar
 * Return the iframe for the bike rental calendar website
 *
 * @param array $attributes for the shortcode
 *
 * @return string the iframe
 */
function bikerm_shortcode_callback($attributes)
{
    $bikeRentalCalendar = shortcode_atts(
        array(
            'id'   => 'yourshopname',
            'zone' => 'US',
        ),
        $attributes
    );

    $shopName = $bikeRentalCalendar['id'];
    $zone     = $bikeRentalCalendar['zone'];
    $shopUrl  = bikerm_create_shop_url($zone, $shopName);

    ob_start();
    include PLUGIN_PATH . '/templates/iframe-shop.php';

    return ob_get_clean();
}

/**
 * Callback for the shortcode bikerentalcalendar
 * Return the iframe for the bike rental calendar website
 *
 * @param array $attributes for the shortcode
 *
 * @return string the iframe
 */
function bikerm_shortcode_callback2($attributes)
{
    $bikeRentalCalendar = shortcode_atts(
        array(
            'id'   => 'yourshopname',
            'zone' => 'US',
        ),
        $attributes
    );

    //$shopName = $bikeRentalCalendar['id'];
    //$zone     = $bikeRentalCalendar['zone'];

    $shopName = get_field('shop_namespace');
    $zone     = strtolower(get_field('data_center'));

    $shopUrl  = bikerm_create_shop_url2($zone, $shopName, pll_current_language());
    $appUrl  = bikerm_create_app_url($zone);

    ob_start();
    include PLUGIN_PATH . '/templates/iframe-shop-2.php';

    return ob_get_clean();
}

/**
 * Create a shop url
 *
 * @param string $zone     Geographic zone, can be EU (for rest of the world) or US
 * @param string $shopName Name of the shop to use as a parameter
 *
 * @return string URL of the Shop.
 */
 function bikerm_create_shop_url($zone, $shopName)
 {
     if (strtolower($zone) === strtolower('EU')) {
         $shopURL = "https://app.bikerentalmanager.com/book.html?shop={$shopName}";
     } else {
         $shopURL = "https://us.bikerentalmanager.com/book.html?shop={$shopName}";
     }

     return $shopURL;
 }

 function bikerm_create_shop_url2($zone, $shopName, $lang)
  {
      if (strtolower($zone) === strtolower('APP')) {
          $shopURL = "https://app.bikerentalmanager.com/book.html?shop={$shopName}&referrer=bike.rent&lang={$lang}";
      } else {
          $shopURL = "https://us.bikerentalmanager.com/book.html?shop={$shopName}&referrer=bike.rent&lang={$lang}";
      }

      return $shopURL;
  }

 function bikerm_create_app_url($zone)
 {
     if (strtolower($zone) === strtolower('APP')) {
         $shopURL = "https://app.bikerentalmanager.com";
     } else {
         $shopURL = "https://us.bikerentalmanager.com";
     }

     return $shopURL;
 }
