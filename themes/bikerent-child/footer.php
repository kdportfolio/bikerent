<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package understrap-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$container = get_theme_mod( 'understrap_container_type' );
?>

<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>

<div class="wrapper" id="wrapper-footer">

	<div>
		<div class="row">
			<div class="col-md-12">
				<!-- Footer -->
				<footer id="footer">
					<div class="container">
						<div class="footer-wrap">
							<div class="right-footer-sec social-links">
								<ul>
									<li>
										<a target='_blank' href="<?php echo get_option('instagram_url'); ?>"><i class="fa fa-instagram fa-6" aria-hidden="true"></i></a>
									</li>
									<li>
										<a target='_blank' href="<?php echo get_option('facebook_url'); ?>"><i class="fa fa-facebook" aria-hidden="true"></i></a>
									</li>
									<li>
										<a target='_blank' href="<?php echo get_option('test_twitter_url'); ?>"><i class="fa fa-twitter" aria-hidden="true"></i></a>
									</li>
									<li>
										<a target='_blank' href="<?php echo get_option('linkdin_url'); ?>"><i class="fa fa-linkedin" aria-hidden="true"></i></a>
									</li>
								</ul>
							</div>
							<div class="left-footer-sec footer-menu-links">
								
								<?php
								wp_nav_menu(array(
									'menu' => 'COMPANY-Footer'
								));
								?>
							</div>
						</div>
					</div>
				</footer>
				<!--close Footer -->
			</div><!--col end -->

		</div><!-- row end -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

