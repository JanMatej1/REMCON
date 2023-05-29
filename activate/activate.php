<?php

// function to setup REMCON plugin
function remcon_activate() {

    // geting option: REMCON access key
    $key = get_option('remcon_server_key');

    // checking if there is any REMCON access key
    if ($key == "") {

        // creating random access key
        $hash = hash('sha256', random_int(0, 1000));

        // setting generated access key as option 
        $return = add_option('remcon_server_key', $hash);
        
        if (!$return) {

            // error message if there will be some failiure while setting option
            echo 'error in add_option';
        }
    }
}