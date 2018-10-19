(function($){

	AstraWidgetListIcons = {

		/**
		 * Init
		 */
		init: function()
		{
			this._init_toggle_settings();
			this._bind();
		},
		
		/**
		 * Binds events
		 */
		_bind: function()
		{
			$( document ).on('widget-updated widget-added', AstraWidgets._init_toggle_settings );
			$( document ).on('change', '.astra-widget-list-icons-fields .astra-widget-field-imageoricon', AstraWidgetListIcons._toggle_settings );
		},

		_init_toggle_settings: function() {
			$( '.astra-widget-list-icons-fields' ).each(function(index, el) {
				var parent = $( el );
				var image = parent.find( '.astra-widget-field-imageoricon' ).find('option:selected').val() || '';

				if( image === 'image' ) {
					parent.find('.astra-field-image-wrapper').show();
					parent.find('.astra-widget-icon-selector').hide();
				} else {
					parent.find('.astra-widget-icon-selector').show();
					parent.find('.astra-field-image-wrapper').hide();
				}
			});
		},

		_toggle_settings: function() {
			var image = $( this ).find('option:selected').val() || '';
			var parent = $( this ).closest('.astra-widget-list-icons-fields');

			if( image === 'image' ) {
				parent.find('.astra-field-image-wrapper').show();
				parent.find('.astra-widget-icon-selector').hide();
			} else {
				parent.find('.astra-widget-icon-selector').show();
				parent.find('.astra-field-image-wrapper').hide();
			}
		}

	};

	/**
	 * Initialization
	 */
	$(function(){
		AstraWidgetListIcons.init();
	});

})(jQuery);