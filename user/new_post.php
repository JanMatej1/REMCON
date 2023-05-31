<?php

/////////////////////////////////////////////
// ADDING POST FROM REMCON SERVER ///////////
/////////////////////////////////////////////


// including wp-load.php
$path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
include_once $path . 'wp-load.php';

// starting session
session_start();

// checking if user has permisions to add posts
if( current_user_can('editor') || current_user_can('administrator') ) {

    // checking if is valid post id 
    if (isset($_GET['post_id']) && is_numeric($_GET['post_id']) || isset($_GET['post_url']) && filter_var($_GET['post_url'], FILTER_VALIDATE_URL)) {

        // getting crucial values
        $host = get_option('remcon_server_url');
        $access_key = get_option('remcon_access_key');

        // setuping http request
        $api = curl_init();
        curl_setopt($api, CURLOPT_URL, $host . "/wp-content/plugins/remcon/server/return_post.php");
        curl_setopt($api, CURLOPT_POST, 1);

        if (isset($_GET['post_id'])) {
            $params = [
                'remcon' => 1,
                'access_key' => $access_key,
                'post_id' => $_GET['post_id']
            ];
        } else if (isset($_GET['post_url'])) {
            $params = [
                'remcon' => 1,
                'access_key' => $access_key,
                'post_url' => $_GET['post_url']
            ];
        } else {
            $params = [
                'remcon' => 1,
                'access_key' => $access_key,
                'post_id' => null
            ];
        }
        
        curl_setopt($api, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($api, CURLOPT_RETURNTRANSFER, true);

        // executing request
        $output = curl_exec($api);
        $output = json_decode($output, 1);
        curl_close($api);

        // checking if there is response with no errors
        if (is_array($output) && array_key_exists('error', $output) && is_null($output['error'])) {

            // setuping patern
            if (isset($output['patern']['content']) and !is_null($output['patern']['content'])) {
                $patern = $output['patern']['content'];
                $again = true;
                while ($again) {
                    $patern_element = get_between($patern, '[', ']'); // custom function
                    if ($patern_element != '') {
                        if ($patern_element == 'author') {
                            $patern = str_replace('['.$patern_element.']', $output['author'], $patern);
                        } else {
                            $patern = str_replace('['.$patern_element.']', '<a href="'.$output['original_url'].'">'.$patern_element.'</a>', $patern);
                        }
                    } else {
                        $again = false;
                    }   
                }
                $patern = '<div ="remcon-origin"><p>'.$patern.'</p></div>';

                // prepareing data to create new post
                if (isset($output['patern']['where']) && $output['patern']['where'] == "start") {
                    $postarr = [
                        'post_content' => $patern . $output['content'],
                        'post_title' => $output['title']
                    ];
                } else if (isset($output['patern']['where']) && $output['patern']['where'] == "end") {
                    $postarr = [
                        'post_content' => $output['content'] . $patern,
                        'post_title' => $output['title']
                    ];
                } else {
                    $postarr = [
                        'post_content' => $output['content'] . ' ' . $output['patern']['where'],
                        'post_title' => $output['title']
                    ];
                }
            } else {
                $postarr = [
                    'post_content' => $output['content'],
                    'post_title' => $output['title']
                ];
            }

            // creating new post
            $new_post_return = wp_insert_post($postarr);

            // checking if there were no issues with creating new post
            if (!is_numeric($new_post_return) || $new_post_return == 0) {
                $error = 'Something went wrong: Fail to insert new post';
            }

        } else {

            // prepareing error quote
            if (isset($output['error']) and !is_null($output['error'])) {
                $error = 'Something went wrong: '.$output['error'];
            } else {
                $error = 'Something went wrong: Fail to reach Remcon server';
            }
        }
    } else {
        //prepareing error quote
        $error = 'invalid post id';
    }

    // setting error quote to session
    if (isset($error)) {
        $_SESSION['remcon_new_post']['error'] = $error;
    } else {
        $_SESSION['remcon_new_post']['error'] = null;
        $_SESSION['remcon_new_post']['success'] = true;
    }

    // relocate user
    header('location: '.get_site_url().'/wp-admin/edit.php?page=remcon_last_posts');

} else {
    // echo error quote if user doesn't have permisions
    echo 'You need to be editor or administrator to use this file';
}

// function to get string between two other strings in one string
function get_between($string, $start = "", $end = ""){
    if (strpos($string, $start)) { 
        $startCharCount = strpos($string, $start) + strlen($start);
        $firstSubStr = substr($string, $startCharCount, strlen($string));
        $endCharCount = strpos($firstSubStr, $end);
        if ($endCharCount == 0) {
            $endCharCount = strlen($firstSubStr);
        }
        return substr($firstSubStr, 0, $endCharCount);
    } else {
        return '';
    }
}

?>