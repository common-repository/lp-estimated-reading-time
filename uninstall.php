<?php

// Script accessed directly - abort!
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

$options = array (
	'lp_ert_words_per_minute',
	'lp_ert_enabled',
	'lp_ert_show_in_homepage',
	'lp_ert_show_in_archive',
	'lp_ert_css_class',
	'lp_ert_before_text',
	'lp_ert_after_text'
);

foreach ( $options as $option ) {
	delete_option( $option );
}