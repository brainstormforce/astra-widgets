(function($){

	AstraWidgetAddress = {

		/**
		 * Init
		 */
		init: function()
		{
			// this._init_toggle_settings();
			this._bind();
		},
		
		/**
		 * Binds events
		 */
		_bind: function()
		{
			// $( document ).on('widget-updated widget-added', AstraWidgetAddress._init_toggle_settings );
			$( document ).on('change', '.astra-widget-address-fields .astra-widget-field-checkbox', AstraWidgetAddress._init_toggle_settings );
		},

		_init_toggle_settings: function() {
			var parent = $('.astra-widget-address-fields');
			var checked = parent.find( '.astra-widget-field-checkbox input').is(':checked') || false;
			console.log( checked );
			if( checked ) {
				parent.find('.astra-widget-field-icon_color').show();
			} else {
				parent.find('.astra-widget-field-icon_color').hide();
			}
		}
	};

	/**
	 * Initialization
	 */
	$(function(){
		AstraWidgetAddress.init();
	});

})(jQuery);