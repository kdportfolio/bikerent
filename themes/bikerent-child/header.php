<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package understrap-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$container = get_theme_mod( 'understrap_container_type' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div class="site" id="page">
	<!-- ******************* The Navbar Area ******************* -->
	<div id="wrapper-navbar" itemscope itemtype="http://schema.org/WebSite">

		<a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'understrap' ); ?></a>
		
		<!--header start -->
		<header id="header" class="header">
			<div class="container">
				<div class="header-wrap">
					<a href="<?php echo site_url(); ?>" class="left-logo">
						<?php
							$image_attributes = wp_get_attachment_image_src( get_option('img_site_logo'), 'full' );
							$logo = $image_attributes[0];
						?>
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/logo.jpg" alt="bike-rent" />
					</a>
					<div class="right-side-button">
						<div class="mobile-menu">
							<span></span>
							<span></span>
							<span></span>
						</div>
						<div class="menu">
							<?php
							wp_nav_menu(array(
								'menu' => 'Main-menu'
							));
							?>
						</div>
					</div>
				</div>
			</div>
		</header>
		<!--header over -->
	</div><!-- #wrapper-navbar end -->
