<?php 
/* 
Template Name: Shop Template 
*/ 

/* Include Header */
get_header();
?>
<div class="load_shop_html">
<?php echo do_shortcode( '[onlinebooking]' );?>
</div>
<?php
/* Include Footer */
get_footer();
?>