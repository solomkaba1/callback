<?php 
/*
 * Plugin Name: Плагин обратной связи
 */
add_action( 'plugins_loaded', 'my_plugin_init' );

function my_plugin_init() {

	require_once 'admin/admin-functions.php';
    require_once 'include/custom-functions.php';

    register_activation_hook( __FILE__, 'callback_install' ); 

    add_action( 'admin_enqueue_scripts', 'style_metabox_property' );

    function style_metabox_property() {

        wp_enqueue_style( 'callback', plugin_dir_url( __FILE__ ) .'css/callback.css' );
        
    }

}
