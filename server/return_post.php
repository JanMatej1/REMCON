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

                // validateing post id
                if (isset($_POST['post_id']) and is_numeric($_POST['post_id'])) {

                    // setting WP Query to get post by id
                    $post_id = $_POST['post_id'];
                    $query = new WP_Query(['p' => $post_id]);

                    // handeling the post
                    if ($query->have_posts()) {
                        while ($query->have_posts()) {

                            // getting needed data
                            $query->the_post();
                            $title = get_the_title();
                            $url = get_the_permalink();
                            $page_content = get_the_content();
                            $author = get_the_author();
                            $patern = get_option('remcon_patern');
                            $patern_where = get_option('remcon_patern_where');

                            // setting data to return array
                            $return = [
                                'title' => $title,
                                'content' => $page_content,
                                'original_url' => $url,
                                'author' => $author,
                                'patern' => [
                                    "content" => $patern,
                                    "where" => $patern_where
                                ],
                                'error' => null
                            ];

                        }

                    } else {

                        // prepareing error message for: no post
                        $error = 'There is no post with this id';
                    }
                
                // if there is invalid post id, checking post url
                } else if (isset($_POST['post_url']) and is_numeric(url_to_postid($_POST['post_url']))) {
                
                    // setting WP Query to get post by id
                    $post_id = url_to_postid($_POST['post_url']);
                    $query = new WP_Query(['p' => $post_id]);

                    // handeling the post
                    if ($query->have_posts()) {
                        while ($query->have_posts()) {

                            // getting needed data
                            $query->the_post();
                            $title = get_the_title();
                            $url = get_the_permalink();
                            $page_content = get_the_content();
                            $author = get_the_author();
                            $patern = get_option('remcon_patern');
                            $patern_where = get_option('remcon_patern_where');

                            // setting data to return array
                            $return = [
                                'title' => $title,
                                'content' => $page_content,
                                'original_url' => $url,
                                'author' => $author,
                                'patern' => [
                                    "content" => $patern,
                                    "where" => $patern_where
                                ],
                                'error' => null
                            ];

                        }

                    } else {

                        // prepareing error message for: no post
                        $error = 'There is no post with this id';
                    }

                }else {

                    // prepareing error message for: invalid post id
                    $error = 'Invalid post id/post url';
                }
            } else {

                // prepareing error message for: wrong access key
                $error = 'Invalid access key';
            }
        } else {

            // prepareing error message for: no REMCON server
            $error = 'Host is not REMCON server';
        }

        // setting errors to return
        if (isset($error)) {
         $return['error'] = $error;
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