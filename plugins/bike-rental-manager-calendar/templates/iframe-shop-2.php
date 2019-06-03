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

<!--
<?php echo pll_current_language() ?><br>
<?php echo esc_url($shopUrl) ?><br>
<?php echo get_field('shop_namespace') ?><br>
<?php echo get_field('data_center') ?><br>
-->

<!-- OBW RESIZING DEMO -->
<script src='https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.1/iframeResizer.min.js'></script>
<iframe id='brm-online-booking' src='<?php echo esc_url($shopUrl) ?>' style='width: 1px;min-width: 100%;' frameborder='no'  scrolling='no'></iframe>
<script type='text/javascript' >
	iFrameResize({log:true,checkOrigin:['<?php echo esc_url($appUrl) ?>'],
		heightCalculationMethod:'taggedElement'
		}, '#brm-online-booking');
</script>
