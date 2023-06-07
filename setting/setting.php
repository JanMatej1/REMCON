<?php

// function to Remcon setting
function remcon_setting() {

    // prepareing original data
    $data = [
        'server' => get_option('remcon_server'),
        'access_key' => get_option('remcon_access_key'),
        'server_url' => get_option('remcon_server_url'),
        'patern' => get_option('remcon_patern'),
        'patern_where' => get_option('remcon_patern_where'),
        'num_of_posts' => get_option('remcon_num_of_posts')
    ];

    // HTML
    $html = '<h1>Remcon setting</h1>';

    // checking and eventualy setting static server access key
    $key = get_option('remcon_server_key');
    if ($key == "") {
        $hash = hash('sha256', random_int(0, 1000));
        $return = add_option('remcon_server_key', $hash);
        if (!$return) {
            echo 'error in add_option';
            $key = false;
        } else {
            $key = $hash;
        }
    }
    if ($key != false) {
        $server_key = '<p>Server key: '.$key.'</p>';
    } else {
        var_dump($key); 
        $server_key = null;
    }

    // checking "server"
    if ($data['server'] == 1) {
        $server_checked = 'checked';
    } else {
        $server_checked = "";
    }

    // checking "access keyr"
    if ($data['access_key'] != "" or !is_null($data['access_key'])) {
        $access_key = 'value="'.$data['access_key'].'"';
    } else {
        $access_key = "";
    }

    // checking "server url"
    if ($data['server_url'] != "" or !is_null($data['server_url'])) {
        $server_url = 'value="'.$data['server_url'].'"';
    } else {
        $server_url = "";
    }

    // checking "patern"
    if ($data['patern'] != "" or !is_null($data['patern'])) {
        $patern = 'value="'.$data['patern'].'"';
    } else {
        $patern = "";
    }

    // checking "where"
    if ($data['patern_where'] == "start") {
        $patern_where_start = 'checked';
        $patern_where_end = '';
        $patern_where_none = '';
    } else if ($data['patern_where'] == "end") {
        $patern_where_start = '';
        $patern_where_end = 'checked';
        $patern_where_none = '';
    } else {
        $patern_where_start = '';
        $patern_where_end = '';
        $patern_where_none = 'checked';
    }

    // checking "number of posts"
    if ($data['num_of_posts'] != "" and is_numeric($data['num_of_posts'])) {
        $num_of_posts = 'value="'.$data['num_of_posts'].'"';
    } else {
        $num_of_posts = "";
    }

    // creating HTML form with original values 
    $form = '
        <form action="'.plugin_dir_url(__FILE__).'action.php" method="post">
            <h2>Server setting</h2>
            <input type="hidden" name="server" value="0">
            <input type="checkbox" name="server" id="server" value="1" '.$server_checked.'>
            <label for="server">Server</label>
            <div class="server-setting">
                '.$server_key.'
                <div class="patern">
                    <label for="remcon_patern">Patern: </label>
                    <input type="text" name="patern" '.$patern.' id="remcon_patern">
                    <label for="remcon_patern_start">start </label>
                    <input type="radio" name="patern_where" value="start" id="remcon_patern_start" '.$patern_where_start.'>
                    <label for="remcon_patern_end">end </label>
                    <input type="radio" name="patern_where" value="end" id="remcon_patern_end" '.$patern_where_end.'>
                    <label for="remcon_patern_none">none </label>
                    <input type="radio" name="patern_where" value="none" id="remcon_patern_none" '.$patern_where_none.'>
                </div>
            </div>
            <h2>User setting</h2>
            <label for=""server_url">Server url:</label>
            <input type="url" name="server_url" id="server_url" '.$server_url.'><br>
            <label for=""access_key">Access key:</label>
            <input type="text" name="access_key" id="access_key" '.$access_key.'><br>
            <label for=""num_of_posts">Number of posts:</label>
            <input type="number" name="num_of_posts" id="num_of_posts" '.$num_of_posts.' min="0">
            <input type="submit">
        </form>
    ';
    $html .= $form;

    // echo HTML
    echo $html;
}