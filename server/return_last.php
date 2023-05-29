<?php

/////////////////////////////////////////////
// RETURNING LAST n POSTS ///////////////////
/////////////////////////////////////////////


    // include wp-load.php
    $path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
    include_once $path . 'wp-load.php';


    // checking if request is REMCON
    if ((isset($_POST['remcon']) and $_POST['remcon'] == 1)) {

        // setuping return array
        $return = [];

        // checking if you are remcon server
        if (get_option('remcon_server') == 1) {

            // chcecking if request sent right access key
            if (isset($_POST['access_key']) and $_POST['access_key'] == get_option('remcon_server_key')) {

                // validateing number of posts 
                if (isset($_POST['num_of_posts']) and is_numeric($_POST['num_of_posts'])) {

                    // setting WP Query to get last n posts
                    $num_of_posts = $_POST['num_of_posts'];
                    $query = new WP_Query([
                        'post_type' => 'post',
                        'posts_per_page' => $num_of_posts,
                        'orderby' => 'meta_value_num',
                        'order' => 'DESC'
                    ]);

                    // setuping array where will be saved all posts
                    $posts = [];

                    // processing post by post 
                    if ($query->have_posts()) {
                        while ($query->have_posts()) {

                            // getting needed data
                            $query->the_post();
                            $title = get_the_title();
                            $url = get_the_permalink();
                            $page_preview = wp_trim_words(get_the_content(), 15);
                            $author = get_the_author();
                            $post_id = get_the_ID();

                            // setting them as array
                            $array = [
                                'post_id' => $post_id,
                                'title' => $title,
                                'preview' => $page_preview,
                                'original_url' => $url,
                                'author' => $author,
                            ];

                            // saving array of data into array of posts
                            $posts[] = $array;
                        }

                        // setting array of posts as returning data
                        $return['posts'] = $posts;
                    } else {

                        // prepareing error message for: no posts
                        $error = 'There is no posts yet';
                    }
                } else {

                    // prepareing error message for: Invalid num of posts
                    $error = 'Invalid number of posts';
                }
            } else {

                // prepareing error message for: wrong access key
                $error = 'Wrong access key';
            }
        } else {

            // prepareing error message for: no REMCON server
            $error = 'Host is not REMCON server';
        }

        // setting errors to return
        if (isset($error)) {
            $return['error'] = $error;
        } else {
            $return['error'] = null;
        }

        // responsing return
        echo json_encode($return);
    } else {

        // echo message if request wouldn't be REMCON
        echo 'I don\'t know what are you looking for here!';
    }

    // DIE!!!!
    die();
?>