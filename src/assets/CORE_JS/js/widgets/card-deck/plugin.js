CKEDITOR.plugins.add( 'card-deck', {
	requires: 'widget',
	icons: 'card-deck',
	init: function( editor ) {
		editor.widgets.add( 'card-deck', {
			allowedContent: ck_everything,
			requiredContent: 'div(card-deck-widget)',
			editables: {
				deck: {
					selector: '.card-deck',
					allowedContent: ck_everything,
				}
			},
			template:
				'<div class="card-deck-widget"><div class="card-deck" style="padding-top: 2rem"></div></div>',

			button: 'Create a card-deck',
			upcast: function( element ) {
				return element.name == 'div' && element.hasClass( 'card-deck-widget' );
			}
		});
	}
});
