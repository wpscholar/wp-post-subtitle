<?php

if ( function_exists( 'add_action' ) ) {
	add_action( 'after_setup_theme', '\wpscholar\WordPress\PostSubtitle::initialize' );
}