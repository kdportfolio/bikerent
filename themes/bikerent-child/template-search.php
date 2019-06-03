<?php
/*
 * Template Name: Search Template 
 *
 * @package understrap-child
 */

/* Include Header */
get_header();
?>

<div class="bike-search-result">
    <div class="container location-map-container">

        <div class="location-leftcol">
            <div class="listing-infoblock">

                <div class="search-box">

                    <div class="label-selection">
                        <div class="bike-src-wrapper">
                            <div class="search banner">
                                <div class="banner-wrap">
                                    <div class="banner-details">
                                        <form class="" role="filter" id="filter">
                                            <div class="form-field">
                                                <input type="search" id="location-search" name="search" placeholder="<?php pll_e('Where') ?>" autocomplete="off">
                                                <span class="map-input-error" style="display:none"><?php pll_e('Please select location') ?></span>
                                            </div>

                                            <div class="form-field">
                                                <div id="dd" class="drop-down-wrap"><?php pll_e('All types') ?>
                                                    <?php
                                                    $taxonomy = 'bike_category';
                                                    $terms = get_terms(['taxonomy' => $taxonomy, 'hide_empty' => false,]);
                                                    
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
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>								
                <div id="filters">

                    <div class="filter-row">
                        <div class="label-col">
                        </div>
                        <div class="selected-filter">
                            <ul class="dropdown display_response_cat" >
                            </ul>
                        </div>
                    </div> 
                </div>

            </div>              

            <div class="location-listing bike-rent-list">
                <div class="bike-gallery" id="locations-list" data-post_id="<?php echo get_the_ID(); ?>">

                </div>
            </div>
            <span class="error-section"></span>
        </div>

        <div class="shop-locatin-on-map">
            <div id="map" class="location-map">
                <span>
                    <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d2965.0824050173574!2d-93.63905729999999!3d41.998507000000004!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sWebFilings%2C+University+Boulevard%2C+Ames%2C+IA!5e0!3m2!1sen!2sus!4v1390839289319" width="100%" height="200" frameborder="0" style="border:0"></iframe>
                </span>
            </div>
        </div>

        <div class="mobile-filter-holder">
            <div class="mobile-filter-holder-inner">
                <div class="mobile-filter-holder-content">
                    <span class="list-map">
                        <a class="show-map" href="#0">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/map.jpg" alt="map-icon"/>
                            <i class="fa fa-times"></i>
                        </a>
                    </span>
                </div>
            </div>
        </div>


    </div>
</div>
<?php
/* Include Footer */
get_footer();
?>