jQuery( document ).ready(function($) {
	"use strict";
	
	/**
	 * Dropdown Select2 Custom Control
	*/

	$('.customize-control-dropdown-select2').each(function(){
		$('.customize-control-select2').select2({
			allowClear: true
		});
	});

	$(".customize-control-select2").on("change", function() {
		var select2Val = $(this).val();
		$(this).parent().find('.customize-control-dropdown-select2').val(select2Val).trigger('change');
	});

    $('.google-fonts-list').each(function (i, obj) {
		if (!$(obj).hasClass('select2-hidden-accessible')) {
			$(obj).select2();
		}
    });
    
    $('.google-fonts-list').on('change', function() {
		var elementRegularWeight = $(this).parent().parent().find('.google-fonts-regularweight-style');
		var selectedFont = $(this).val();
		var customizerControlName = $(this).attr('control-name');
		
		// Clear Weight/Style dropdowns
		elementRegularWeight.empty();
		// Make sure Italic & Bold dropdowns are enabled
		
		// Get the Google Fonts control object
		var bodyfontcontrol = _wpCustomizeSettings.controls[customizerControlName];

		// Find the index of the selected font
		var indexes = $.map(bodyfontcontrol.cafontslist, function(obj, index) {
			if(obj.family === selectedFont) {
				return index;
			}
		});
		var index = indexes[0];

		// For the selected Google font show the available weight/style variants
		$.each(bodyfontcontrol.cafontslist[index].variants, function(val, text) {
			elementRegularWeight.append(
				$('<option></option>').val(text).html(text)
			);
			
		});

		
		// Update the font category based on the selected font
		$(this).parent().parent().find('.google-fonts-category').val(bodyfontcontrol.cafontslist[index].category);

		getAllSelects($(this).parent().parent());
    });
    
    $('.google_fonts_select_control select').on('change', function() {
		getAllSelects($(this).parent().parent());
	});

	function getAllSelects($element) {
		var selectedFont = {
			font: $element.find('.google-fonts-list').val(),
			regularweight: $element.find('.google-fonts-regularweight-style').val(),
			category: $element.find('.google-fonts-category').val()
		};

		// Important! Make sure to trigger change event so Customizer knows it has to save the field
		$element.find('.customize-control-google-font-selection').val(JSON.stringify(selectedFont)).trigger('change');
	}
    
});