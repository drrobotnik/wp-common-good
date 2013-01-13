<div id="wp-common-good-settings">

<?php wp_nonce_field( 'wp-common-good-settings', '_wp_common_good_nonce', false, true ); ?>

<h2><?php _e( 'Ask Away', 'wp-common-good' ); ?></h2>

<p><?php _e( 'Your question will go directly to us where we will process the issue and respond asap. Afterwards the information will be found here for future reference.', 'wp-common-good' ); ?></p>

<p><textarea style="width:100%;">What seems to be the problem?</textarea></p>

<p class="submit"><?php submit_button( __( 'Save Changes', 'wp-common-good' ), 'primary', 'wp-common-good-settings-save', false ); ?> <a href="#" id="wp-common-good-settings-cancel"><?php _e( 'Cancel', 'wp-common-good' ); ?></a></p>

<div id="wp-common-good-slurp-error"></div>

</div>