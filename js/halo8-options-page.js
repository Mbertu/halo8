(function ($) {
	"use strict";
	$(function () {
		var enabled = $('input[type=checkbox]#enabled').is(':checked');
		var current_type = $('input[type=radio].type:checked').val();
        var position = $('select#position').val();

		$('input[type=checkbox]#enabled').change(function(){
			enabled = $(this).is(':checked');
			if(enabled){
				$('.halo8_input_container input').prop( "disabled", false );
				$('.halo8_input_container select').prop( "disabled", false );
				radioTypeChange(current_type);
                $('select#position').trigger('change');
			}else{
				$('.halo8_input_container input').prop( "disabled", true );
				$('.halo8_input_container select').prop( "disabled", true );
				$(this).prop( "disabled", false );
			}
		});

        $('select#position').change(function(){
            position = $(this).val();
            if(enabled && position == 'slideshow'){
                $('.container input').prop( "disabled", false );
            }else{
                $('.container input').prop( "disabled", true );
            }
        });

		$('input[type=radio].type').change(function(){
			var value = $(this).val();
			radioTypeChange(value);
			current_type = value;
		});
	});

	var radioTypeChange = function(value) {
		if(value == 'type_transition'){
			$('#animation_duration').prop( "disabled", true );
			$('#animation').prop( "disabled", true );
			$('#transition_duration').prop( "disabled", false );
			$('#transition').prop( "disabled", false );
		}else{
			$('#transition_duration').prop( "disabled", true );
			$('#transition').prop( "disabled", true );
			$('#animation_duration').prop( "disabled", false );
			$('#animation').prop( "disabled", false );
		}
	}
}(jQuery));
