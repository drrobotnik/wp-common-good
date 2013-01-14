<div id="wp-common-good-settings">

<?php wp_nonce_field( 'wp-common-good-settings', '_wp_common_good_nonce', false, true ); ?>

<h2><?php _e( 'Ask Away', 'wp-common-good' ); ?></h2>

<p><?php _e( 'Your question will go directly to us where we will process the issue and respond asap. Afterwards the information will be found here for future reference.', 'wp-common-good' ); ?></p>

<p><input type="text" name="cg-title" id="cg-title" placeholder="Title" value="" /></p>
<?php 
$settings = array(
	'media_buttons' => false,
	'textarea_rows' => 10,
	'size'=>'regular',
	'tinymce' => array(
		'theme_advanced_buttons1' => 'formatselect,|,bold,italic,underline,|,' .
			'bullist,blockquote,|,justifyleft,justifycenter' .
			',justifyright,justifyfull,|,link,unlink,|' .
			',spellchecker,wp_fullscreen,wp_adv'
	)
);

wp_editor( '', 'wp-common-good-editor'); ?>
<p class="submit"><?php submit_button( __( 'Save Changes', 'wp-common-good' ), 'primary', 'wp-common-good-settings-save', false ); ?> <a href="#" id="wp-common-good-settings-cancel"><?php _e( 'Cancel', 'wp-common-good' ); ?></a></p>

<div id="wp-common-good-slurp-error"></div>

</div>

<script>

jQuery(document).ready(function($) {

// bad hack, mce was displaying only one line tall.
$("#contextual-help-link").bind('click', function(event) {
	$('#wp-common-good-editor_ifr').css('height',200);
})


	$('#wp-common-good-settings-save').click(function(event){
		event.preventDefault();
		$.post( ajaxurl, {
					action: 'wp_common_good_settings',
					_ajax_nonce: $('#_wp_common_good_nonce').val(),
					title: $('#cg-title').val(),
					issue: tinyMCE.editors["wp-common-good-editor"].getContent(),
				}, function(result) {
					console.log(result);
					result = $.parseJSON( result );
					if ( result.error ) {
						$('#wp-common-good-issue').text(result.error)
					} else {
						// do stuff
					}
		});

	});
});
</script>