jQuery(document).ready(function () {
    /*const observer = lozad(); // lazy loads elements with default selector as '.lozad'
    observer.observe();*/

    jQuery(".scroll-down").click(function () {
        jQuery('html, body').animate({
            scrollTop: jQuery("#bike-rent-works").offset().top - 70
        }, 2000);
    });

    function DropDown(el) {
        this.dd = el;
        this.opts = this.dd.find('ul.dropdown > li');
        this.val = [];
        this.index = [];
        this.initEvents();
    }
    DropDown.prototype = {
        initEvents: function () {
            var obj = this;

            obj.dd.on('click', function (event) {
                jQuery(this).toggleClass('active');
                event.stopPropagation();
            });

            obj.opts.children('label').on('click', function (event) {
                var opt = jQuery(this).parent(),
                        chbox = opt.children('input'),
                        val = chbox.val(),
                        idx = opt.index();

                (jQuery.inArray(val, obj.val) !== -1) ? obj.val.splice(jQuery.inArray(val, obj.val), 1) : obj.val.push(val);
                (jQuery.inArray(idx, obj.index) !== -1) ? obj.index.splice(jQuery.inArray(idx, obj.index), 1) : obj.index.push(idx);
            });
        },
        getValue: function () {
            return this.val;
        },
        getIndex: function () {
            return this.index;
        }

    }

    jQuery(function () {

        var dd = new DropDown(jQuery('#dd'));

        jQuery(document).click(function () {
            // all dropdowns
            jQuery('.wrapper-dropdown-4').removeClass('active');
        });

    });


    // menu toggle	 	
    jQuery('.mobile-menu').click(function () {
       jQuery('body').toggleClass('show-menu');
    });
    jQuery('.close-button').click(function () {
        jQuery('body').removeClass('show-menu');
    });
});
 

/* jQuery(function($){ */
jQuery(document).ready(function(){
	/*
	 * Select/Upload image(s) event
	 */
	jQuery(document).on('click', '.misha_upload_image_button', function(e){
		e.preventDefault();
 
    		var button = jQuery(this),
    		    custom_uploader = wp.media({
			title: 'Insert image',
			library : {
				// uncomment the next line if you want to attach image to the current post
				// uploadedTo : wp.media.view.settings.post.id, 
				type : 'image'
			},
			button: {
				text: 'Use this image' // button label text
			},
			multiple: false // for multiple image selection set to true
		}).on('select', function() { // it also has "open" and "close" events 
			var attachment = custom_uploader.state().get('selection').first().toJSON();
			jQuery(button).removeClass('button').html('<img class="true_pre_image" src="' + attachment.url + '" style="max-width:95%;display:block;" />').next().val(attachment.id).next().show();
			/* if you sen multiple to true, here is some code for getting the image IDs
			var attachments = frame.state().get('selection'),
			    attachment_ids = new Array(),
			    i = 0;
			attachments.each(function(attachment) {
 				attachment_ids[i] = attachment['id'];
				console.log( attachment );
				i++;
			});
			*/
		})
		.open();
	});
 
	/*
	 * Remove image event
	 */
	jQuery('body').on('click', '.misha_remove_image_button', function(){
		jQuery(this).hide().prev().val('').prev().addClass('button').html('Upload image');
		return false;
	});
	
	jQuery('.show-map').click(function(){
        jQuery(this).toggleClass('close');
        jQuery(".shop-locatin-on-map").toggleClass('show');
    });
 
});
 