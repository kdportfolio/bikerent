<?php
/*
 * Template Name: Home Template
 *
 * @package understrap-child
 */

/* Include Header */
get_header();

$banner_value = get_post_meta(get_the_ID(), 'second_featured_img', true);
$image_attributes = wp_get_attachment_image_src($banner_value, 'full');
?>
<!--Content-->
<section id="banner" class="banner" style="background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/images/banner-img.jpg)">
    <div class="banner-wrap">
        <div class="banner-details">
            <div class="banner-content">
                <h1><?php pll_e('title-banner') ?></h1>
            </div>
            <div class="banner-form-wrap">
                <span><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/marker.png" alt="marker"/></span>
                <div class="banner-form">
                    <form>
                        <div class="form-field">
                            <input type="search" id="location-search" name="search" placeholder="<?php pll_e('Where') ?>">
                            <span class="map-input-error" style="display:none"><?php pll_e('Please select location') ?></span>
                        </div>
                        <div class="form-field">
                            <div id="dd" class="drop-down-wrap"><?php pll_e('All types') ?>
                                <?php
                                $taxonomy = 'bike_category';
                                $terms = get_terms([
                                    'taxonomy' => $taxonomy,
                                    'hide_empty' => false,
                                ]);
                                if ($terms && !is_wp_error($terms)) :
                                    $term_all = explode(',', $_GET['term']);
                                    ?>
                                    <ul class="dropdown" >
                                        <?php
                                        foreach ($terms as $term) {
                                            if (isset($term->name)) {
                                                $term_id = str_replace(" ", "-", $term->name);
                                            }
                                            /*
                                             * Below code is remove lang slugs from terms
                                             */
                                            if( pll_current_language() == 'en'){
                                                $term_slug = str_replace("-en","",$term->slug);
                                            }else if( pll_current_language() == 'fr' ){
                                                $term_slug = str_replace("-fr","",$term->slug);
                                            }
                                            
                                            ?>
                                            <li>
                                                <input type="checkbox" <?php if (in_array($term->name, $term_all)) {
                                        echo "checked";
                                    } ?> id="el-<?php echo $term_id; ?>" name="term" value="<?php echo $term_slug; ?>">
                                                <label for="el-<?php echo $term_id; ?>"><?php echo $term->name; ?></label>
                                            </li>
                                    <?php } ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-field search-btn">
                            <a class="search_results" href="javascript:void(0);" ><?php pll_e('SEARCH') ?></a>
                            
                            <!--<input type="submit" name="search-btn" value="SEARCH">-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <a href="#0" class="scroll-down">
            <i class="fa fa-caret-down" aria-hidden="true"></i>
        </a>
    </div>
</section>
<!--Content-->
<?php
/* Include Footer */
get_footer();
?>