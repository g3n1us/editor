(function ( $, window, document, undefined ) {

    var pluginName = "toggleReadonly",
        defaults = {
            propertyName: "value"
        };

    function Plugin( element, options ) {
        this.element = element;

        this.options = $.extend( {}, defaults, options) ;

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Plugin.prototype = {

        init: function() {
			var $targ = $($(this.element).attr('href') || $(this.element).data('target'));
			$targ = $targ.is('input') ? $targ : $targ.find('input');
			console.log($targ[0]);
			if($(this.element).prop('checked')){
				$targ.each(function(){
					if($(this).is(':checkbox'))
						$(this).attr('disabled', 'disabled');
					else
						$(this).attr('readonly', '');
				})
			}
			else{
				$targ.each(function(){
					if($(this).is(':checkbox'))
						$(this).removeAttr('disabled');
					else
						$(this).removeAttr('readonly');
					
				});				
			}
        }

    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            new Plugin( this, options );	        
        });
    };

})( jQuery, window, document );
