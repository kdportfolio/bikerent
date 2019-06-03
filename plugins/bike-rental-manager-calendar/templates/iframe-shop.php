<?php
/**
 * Template for the iframe
 * 
 * PHP version 5.6
 * 
 * @category   WordPress_Plugin
 * @package    BikeRentalManager
 * @subpackage Calendar
 * @author     Bike Rental Manager <contact@bikerentalmanager.com>
 * @license    GPLv2 https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @link       https://bikerentalmanager.com/ * 
 */

namespace BikeRentalManager\Calendar\Templates;
?>

<iframe
    src='<?php echo esc_url($shopUrl) ?>'
    width='100%'
    height='1000'
    frameborder='0'>
</iframe>
