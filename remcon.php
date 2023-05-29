<?php

/*
Plugin Name: Remocon
Plugin URI: 
Description: Remote content vám dá možnost nastavit plugin buť jako server a nebo jako uživatele, který bude mít možnost přes api klíč zveřejňovat posty, které jsou již zveřejněné na serveru.
Author: Jan Matejicek
Version: 1.1
Author URI: http://matejicek.website/
*/

// including files with functions for add_action
include_once 'setting/setting.php';
include_once 'activate/activate.php';
include_once 'user/last_posts.php';

// add actions
add_action('activate_plugin', 'remcon_activate'); // activate  this plugin
add_action('admin_menu', 'remcon_create_menu_item'); // add Remcon to admin menu
add_action('admin_menu', 'remcon_last_posts'); // add Remcon posts as submenu to Posts
add_action('admin_enqueue_scripts', 'callback_for_setting_up_scripts'); // setuping scripts
add_action( 'init', 'remcon_session' ); // start session


// function to add Remcon to admin menu
function remcon_create_menu_item() {
    add_menu_page(
        'Remcon',
        'remcon',
        'administrator',
        'remcon',
        'remcon_setting',
        'dashicons-dashboard'
    );
}

// function to add Remcon posts as submenu to Posts
function remcon_last_posts() {
    add_submenu_page(
        'edit.php',
        'Remcon posts',
        'Remcon posts',
        'administrator',
        'remcon_last_posts',
        'last_posts',
        2
    );
}

// function to start session
function remcon_session() {
    if ( ! session_id() ) {
        session_start();
    }
}

// function to setup js and css
function callback_for_setting_up_scripts() {
    wp_register_style( 'remcon',  plugin_dir_url( __FILE__ ).'style/remcon.css' );
    wp_enqueue_style( 'remcon' );
}