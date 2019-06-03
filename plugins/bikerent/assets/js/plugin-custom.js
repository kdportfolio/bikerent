/**
 * jQuery for BikeRent Plugin
 *
 *
 * @category   WordPress_Plugin
 * @package    BikeRent
 * @author     Pooja Adani <contact@bikerentalmanager.com>
 * @link       https://bikerentalmanager.com/ *
 */

jQuery( document ).ready( function(){
    /**
     * Below function is used for google auto complete locations 
     */
    var autocomplete;
    autocomplete = new google.maps.places.Autocomplete(
            ( document.getElementById( 'location-search' ) ),
            { types: ['geocode'] }
    );
    
    google.maps.event.addListener( autocomplete, 'place_changed', function(){} );
    var allMarkers = [];

    jQuery( document ).on( 'click', '.slick-next', function(e){
        e.stopPropagation();
    });

    jQuery( document ).on( 'click', '.slick-prev', function(e){
        e.stopPropagation();
    });

    /**
     * On click Search result button 
     * Call search API 
     */
    jQuery( '.search_results' ).on( 'click', function(){
        var location_name = jQuery( '#location-search' ).val();
        if( location_name === '' ){
            jQuery( '#location-search' ).css( 'border', '2px solid #d83d3d' );
            jQuery( '.map-input-error' ).show();
            return false;
        }
        var term = [];
        jQuery.each( jQuery( "input[name='term']:checked" ), function(){
            var data = jQuery.inArray( jQuery( this ).val(), term );
            if ( data >= 0 ){
            } else {
                term.push( jQuery( this ).val() );
            }
        });
        term = term.join( "," );
        var site_url = my_ajax_object.site_url;

        location.href = site_url + "/search/?location=" + location_name + "&term=" + term;
    });


    /** 
     * Below function we are using to get parameters from url 
     *
     */
    var getUrlParameter = function getUrlParameter( sParam ){
        var sPageURL = window.location.search.substring( 1 ),
                sURLVariables = sPageURL.split( '&' ),
                sParameterName,
                i;

        for( i = 0; i < sURLVariables.length; i++ ){
            sParameterName = sURLVariables[i].split( '=' );

            if( sParameterName[0] === sParam ){
                return sParameterName[1] === undefined ? true : decodeURIComponent( sParameterName[1] );
            }
        }
    };

    /** 
     * Below code is use for display results on page 
     *
     */
    if( getUrlParameter( 'location' ) !== '' && getUrlParameter( 'location' ) !== undefined ){
        var location_name = getUrlParameter( 'location' );
        var post_id = jQuery( '#locations-list' ).data( 'post_id' );
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode( { 'address': "'" + location_name + "'" }, function( results, status ){
            var lat = results[0].geometry.location.lat();
            var lng = results[0].geometry.location.lng();
            var term = getUrlParameter( 'term' );
            if( term === '' ){
                term = '';
            }

            var icon2 = my_ajax_objects.map_icon_hover;
            var icon1 = my_ajax_objects.map_icon;

            jQuery.ajax( {
                type: "POST",
                url: my_ajax_object.ajax_url,
                data: { location_name: location_name, term: term, lat: lat, lng: lng, post_id: post_id, action: "call_search_results" },
                success: function( res ){
                    var res = res.split( '###' );
                    jQuery( '.display_location' ).text( location_name );
                    jQuery( '#location-search' ).val( location_name );
                    jQuery( '#locations-list' ).html( res[0] );
                    jQuery( '.display_response_cat' ).html( res[1] );
                    var link_hover = jQuery( '.location-item' );
                    jQuery( '.bike-thumb' ).slick();

                    var map = new google.maps.Map( document.getElementById( "map" ), {
                        center: new google.maps.LatLng( lat, lng ),
                        zoom: 8,
                        mapTypeId: google.maps.MapTypeId.ROADMAP
                    });

                    var no = 0;
                    jQuery( '.location-item' ).each( function(){
                        no++;
                        var lat = jQuery( this ).data( 'lat' );
                        var lng = jQuery( this ).data( 'long' );
                        var shop_name = jQuery( this ).data( 'shop' );

                        latLng = new google.maps.LatLng( lat, lng );

                        /* Creating a marker and putting it on the map */
                        var marker = new google.maps.Marker( {
                            position: latLng,
                            map: map,
                            id: no,
                            title: shop_name,
                            icon: my_ajax_objects.map_icon
                        });
                        allMarkers.push( marker );

                        var infoWindow = new google.maps.InfoWindow();

                        google.maps.event.addListener( marker, "click", function(e){
                            infoWindow.setContent( shop_name );
                            infoWindow.open( map, marker );
                        });

                    });

                    jQuery( document ).on( 'mouseover', '.location-item', function(){
                        hover( jQuery(this).data( 'id' ) );
                    });

                    jQuery( document ).on( 'mouseout', '.location-item', function(){
                        hoverout( jQuery(this).data( 'id' ) );
                    });


                    function hover( id ){
                        for( var i = 0; i < allMarkers.length; i++ ){
                            if( id === allMarkers[i].id ){
                                allMarkers[i].setIcon( icon2 );
                                break;
                            }
                        }
                    }

                    function hoverout( id ){
                        for( var i = 0; i < allMarkers.length; i++ ){
                            if( id === allMarkers[i].id ){
                                allMarkers[i].setIcon( icon1 );
                                break;
                            }
                        }
                    }

                }
            });

        });

    } else if ( getUrlParameter( 'location' ) === '' ){
        jQuery( '.error-section' ).html( 'Sorry!!! We are not found any shop for this location. Please try with another location.' );
    }

    /** 
     * On click shop in search result page 
     * Redirect to the shop page 
     *
     */
    jQuery( document ).on( 'click', '.location-item', function(){
        var post_id = jQuery( this ).data( 'post_id' );

        jQuery.ajax( {
            type: "POST",
            url: my_ajax_object.ajax_url,
            data: { post_id: post_id, action: "redirect_to_shop" },
            success: function( res ){
                window.location.href = res;
            }
        });
    });
});