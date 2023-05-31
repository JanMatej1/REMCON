<?php

// function to get last 5 posts from Remcon server
function last_posts() {
    
    // geting notifications from session
    if (isset($_SESSION['remcon_new_post']) && !is_null($_SESSION['remcon_new_post'])) {
        if (isset($_SESSION['remcon_new_post']['error']) && (!is_null($_SESSION['remcon_new_post']['error']) || $_SESSION['remcon_new_post']['error'] != "")) {
            echo '<div class="notification_negative"><span class="color"></span><p>'.$_SESSION['remcon_new_post']['error'].'</p></div>';
        } else if ($_SESSION['remcon_new_post']['success']){
            echo '<div class="notification_positive"><span class="color"></span><p>new post was added</p></div>';
        } else {
            echo '<div class="notification"><span class="color"></span><p>Something is wrong</p></div>';
        }
        unset($_SESSION['remcon_new_post']);
    }

    // getting crucial values
    $host = get_option('remcon_server_url');
    $access_key = get_option('remcon_access_key');

    // setuping http request
    $api = curl_init();
    curl_setopt($api, CURLOPT_URL, $host . "/wp-content/plugins/remcon/server/return_last.php");
    curl_setopt($api, CURLOPT_POST, 1);
    $params = [
        'remcon' => 1,
        'access_key' => $access_key,
        'num_of_posts' => 5
    ];
    curl_setopt($api, CURLOPT_POSTFIELDS, http_build_query($params));
    curl_setopt($api, CURLOPT_RETURNTRANSFER, true);

    // executing request
    $output = curl_exec($api);
    $output = json_decode($output, 1);
    curl_close($api);

    // creating html to show up in Remcon posts
    $html = '<h1>Last 5 posts</h1><div class="remcon-posts">';

    // checking if there is valid output
    if (is_array($output) && array_key_exists('error', $output) && is_null($output['error'])) {
        // proccesing posts
        foreach ($output['posts'] as $post) {
            // geting values from current post
            $post_id = $post['post_id'];
            $title = $post['title'];
            $author = $post['author'];
            $preview = $post['preview'];
            $url = $post['original_url'];
            
            // creating html card for post
            $post_html = '<div class="remcon-post">';
            $post_html .= '<a href="'.$url.'"><h2>'.$title.'</h2></a><p>Author: '.$author.'</p><p>'.$preview.'</p>';
            $post_html .= '<a href="'.get_site_url().'/wp-content/plugins/remcon/user/new_post.php?post_id='.$post_id.'">add this post</a>';
            $post_html .= '</div>';
            $html .= $post_html;
        }
    } else {
        // handelling with errors
        if (isset($output['error'])) {
            $html .= 'Something went wrong: '.$output['error'];
        } else {
            $html .= 'Something went wrong: Fail to reach Remcon server';
        } 
    }
    $html .= '</div>'; 

    // echo whole html
    echo $html;
}

?>
