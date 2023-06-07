<?php

/////////////////////////////////////////////
// SETUPING DATA FROM SETTING FORM //////////
/////////////////////////////////////////////

    // include wp-load.php
    $path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
    include_once $path . 'wp-load.php';

    // checking if user has permisions to add posts
    if (current_user_can( 'manage_options' )) {

        // prepareing original data
        $data = [
            'server' => get_option('remcon_server'),
            'access_key' => get_option('remcon_access_key'),
            'server_url' => get_option('remcon_server_url'),
            'patern' => get_option('remcon_patern'),
            'patern_where' => get_option('remcon_patern_where'),
            'num_of_posts' => get_option('remcon_num_of_posts')
        ];

        /////////////////////////////
        // Validateing data from form

        // validateing "server"
        if (isset($_POST['server']) and !is_null($_POST['server'])) {
            if ($data['server'] != $_POST['server']) {
                if ($data['server'] == "") {
                    if (!add_option('remcon_server', $_POST['server'])) {
                        update_option('remcon_server', $_POST['server']);
                    }
                } else {
                    update_option('remcon_server', $_POST['server']);
                }
            }
        } 
        // validateing "access key"
        if (isset($_POST['access_key']) and !is_null($_POST['access_key'])) {
            if ($data['access_key'] != $_POST['access_key']) {
                if ($data['access_key'] == "") {
                    if (!add_option('remcon_access_key', $_POST['access_key'])) {
                        update_option('remcon_access_key', $_POST['access_key']);
                    }
                } else {
                    update_option('remcon_access_key', $_POST['access_key']);
                }
            }
        } 
        // validateing "server url"
        if (isset($_POST['server_url']) and !is_null($_POST['server_url'])) {
            if ($data['server_url'] != $_POST['server_url']) {
                if ($data['server_url'] == "") {
                    if (!add_option('remcon_server_url', $_POST['server_url'])) {
                        update_option('remcon_server_url', $_POST['server_url']);
                    }
                } else {
                    update_option('remcon_server_url', $_POST['server_url']);
                }
            }
        } 
        // validateing "patern"
        if (isset($_POST['patern']) and !is_null($_POST['patern'])) {
            if ($data['patern'] != $_POST['patern']) {
                if ($data['patern'] == "") {
                    if (!add_option('remcon_patern', $_POST['patern'])) {
                        update_option('remcon_patern', $_POST['patern']);
                    }
                } else {
                    update_option('remcon_patern', $_POST['patern']);
                }
            }
        } 
        // validateing "patern where"
        if (isset($_POST['patern_where']) and !is_null($_POST['patern_where'])) {
            if ($data['patern_where'] != $_POST['patern_where']) {
                if ($data['patern_where'] == "") {
                    if (!add_option('remcon_patern_where', $_POST['patern_where'])) {
                        update_option('remcon_patern_where', $_POST['patern_where']);
                    }
                } else {
                    update_option('remcon_patern_where', $_POST['patern_where']);
                }
            }
        }
        // validateing "number of posts"
        if (isset($_POST['num_of_posts']) and !is_null($_POST['num_of_posts']) and is_numeric($_POST['num_of_posts']) and $_POST['num_of_posts'] > 0) {
            if ($data['num_of_posts'] != $_POST['num_of_posts']) {
                if ($data['num_of_posts'] == "") {
                    if (!add_option('remcon_num_of_posts', (int)$_POST['num_of_posts'])) {
                        update_option('remcon_num_of_posts', (int)$_POST['num_of_posts']);
                    }
                } else {
                    update_option('remcon_num_of_posts', (int)$_POST['num_of_posts']);
                }
            }
        }

        // prepare redirection to admin page
        $redirect = get_admin_url().'admin.php?page=remcon';
    } else {
        // prepare redirection to origin
        $redirect = get_the_permalink();
    }

    // redirect
    header('location: '.$redirect);