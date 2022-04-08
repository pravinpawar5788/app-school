"use strict";
/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 *
 * ---------------------------------------------------------------------------- */

/**
 * jQuery PrintMe v.1.0
 * 
 * A jquery plugin that prints the given element
 *
 * Copyright 2014, Daniel Arlandis <daniarlandis@gmail.com> www.daniarlandis.es
 * Released under the WTFPL license
 * http://sam.zoy.org/wtfpl/
 *
 * Date: Mon Feb 10 19:23:00 2014
 * Last modified: Fri Oct 21 22:00:00 2016
 */
jQuery.fn.printMe = function(options){

	// Setup options
	var settings = $.extend({
		// Defaults options.
		path: [],
		title: "",
		head: false,
	}, options );
	
	// Set the properties and run the plugin
	return this.each(function(){
		
		// Store the object
		var $this = $(this);

		var w = window.open();
		w.document.write( "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">" );
		w.document.write( "<html>" );
		w.document.write( "<head>" );
		w.document.write( "<meta charset='utf-8'>" );

		// Add the style sheets
		for(i in settings.path){
			w.document.write('<link rel="stylesheet" href="'+settings.path[i]+'">');
		}
		
		// Close the head
		w.document.write('</head><body>');

		// Add a header when the title not is empty
		if (settings.title != "")
			w.document.write( "<h1>" + settings.title + "<\/h1>" );

		// Add a content to print
		w.document.write( $this.html() );
		w.document.write('<script type="text/javascript">function closeme(){window.close();}setTimeout(closeme,50);window.print();</script></body></html>');
		w.document.close();
	});
}

//trigger manual events
function triggerEvent(el, type){
   if ('createEvent' in document) {
        // modern browsers, IE9+
        var e = document.createEvent('HTMLEvents');
        e.initEvent(type, false, true);
        el.dispatchEvent(e);
    } else {
        // IE 8
        var e = document.createEventObject();
        e.eventType = type;
        el.fireEvent('on'+e.eventType, e);
    }
}
function showname () {
      var input = document.getElementById('clientImageFile');
      var span = document.getElementById('selected-file');
      span.innerHTML='File: '+input.files.item(0).name;
//      'input.files.item(0).size;
//      'input.files.item(0).type;
    };
function showname2 () {
      var input = document.getElementById('clientImageFile2');
      var span = document.getElementById('selected-file2');
      span.innerHTML='File: '+input.files.item(0).name;
//      'input.files.item(0).size;
//      'input.files.item(0).type;
    };
var _componentUiDatepicker = function() {
	if (!$().datepicker) {
		console.warn('Warning - jQuery UI components are not loaded.');
		return;
	}

	// Initialize
	$('.datepicker').datepicker({
		showOtherMonths: true,
        showOtherYears: true,
		dateFormat: 'dd-M-yy',
		// min:moment()
	});
};
// Select2 
var _componentSelect2 = function() {
	if (!$().select2) {
		console.warn('Warning - select2.min.js is not loaded.');
		return;
	}

	// Initialize
	$('.form-control-select2').select2({
		minimumResultsForSearch: Infinity
	});
	$('.select').select2({
		minimumResultsForSearch: Infinity
	});
	$('.select-search').select2({
		minimumResultsForSearch: "1",
		//dropdownAutoWidth: true
	});

	// Length menu styling
	$('.dataTables_length select').select2({
		minimumResultsForSearch: Infinity,
		dropdownAutoWidth: true,
		width: 'auto'
	});
};
// CKEditor
var _componentCKEditor = function() {
        if (typeof CKEDITOR == 'undefined') {
            console.warn('Warning - ckeditor.js is not loaded.');
            return;
        }

	    // Apply options
	    CKEDITOR.disableAutoInline = true;
	    CKEDITOR.dtd.$removeEmpty['i'] = false;
	    CKEDITOR.config.startupShowBorders = false;
	    CKEDITOR.config.extraAllowedContent = 'table(*)';
    };
// FAB
var _componentFab = function() {
        if (!$().stick_in_parent) {
            console.warn('Warning - sticky.min.js is not loaded.');
            return;
        }

        // Add bottom spacing if reached bottom,
        // to avoid footer overlapping
        // -------------------------
        
        $(window).on('scroll', function() {
            if($(window).scrollTop() + $(window).height() > $(document).height() - 40) {
                $('.fab-menu-bottom-left, .fab-menu-bottom-right').addClass('reached-bottom');
            }
            else {
                $('.fab-menu-bottom-left, .fab-menu-bottom-right').removeClass('reached-bottom');
            }
        });

        // Initialize sticky button
        $('#fab-menu-affixed-demo-left, #fab-menu-affixed-demo-right').stick_in_parent({
            offset_top: 20
        });
    };
// Wizard
var _componentWizard = function() {
        if (!$().steps) {
            console.warn('Warning - steps.min.js is not loaded.');
            return;
        }

        // Basic wizard setup
        $('.steps-mozbasic').steps({
            headerTag: 'h6',
            bodyTag: 'fieldset',
            transitionEffect: 'fade',
            //saveState: true,
            //autoFocus: true,
            //startIndex: 2,
            //enableAllSteps: true,
            titleTemplate: '<span class="number">#index#</span> #title#',
            labels: {
                previous: '<i class="icon-arrow-left13 mr-2" /> Previous',
                next: 'Next <i class="icon-arrow-right14 ml-2" />',
                finish: 'Submit form <i class="icon-arrow-right14 ml-2" />'
            },
            onFinished: function (event, currentIndex) {
                alert('Form submitted.');
            }
        });
    };
//input checkboxes and radios
var _componentUniform = function() {
        if (!$().uniform) {
            console.warn('Warning - uniform.min.js is not loaded.');
            return;
        }

        // Default initialization
        $('.form-check-input-styled').uniform();


        //
        // Contextual colors
        //

        // Primary
        $('.form-check-input-styled-primary').uniform({
            wrapperClass: 'border-primary-600 text-primary-800'
        });

        // Danger
        $('.form-check-input-styled-danger').uniform({
            wrapperClass: 'border-danger-600 text-danger-800'
        });

        // Success
        $('.form-check-input-styled-success').uniform({
            wrapperClass: 'border-success-600 text-success-800'
        });

        // Warning
        $('.form-check-input-styled-warning').uniform({
            wrapperClass: 'border-warning-600 text-warning-800'
        });

        // Info
        $('.form-check-input-styled-info').uniform({
            wrapperClass: 'border-info-600 text-info-800'
        });

        // Custom color
        $('.form-check-input-styled-custom').uniform({
            wrapperClass: 'border-indigo-600 text-indigo-800'
        });
    };

	
	
///////////////////////////////
///page loaded. now call the functions
$(function(){
	_componentUiDatepicker();
    _componentSelect2();
    //_componentCKEditor();
    _componentFab();
	_componentUniform();
	$.unblockUI();	
	$('.modal').on('shown.bs.modal', function () {$('.select').select2({minimumResultsForSearch: Infinity,placeholder: 'Select an option'});});
	
});

