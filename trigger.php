<?php
    
    $config = require('config.php');
    $url = $config['jenkins_url'];
    $auth_token = $config['jenkins_auth_token'];
    $auth_user  = $config['jenkins_basic_auth_username'];
    $auth_pass  = $config['jenkins_basic_auth_password'];

    $text = $_REQUEST['text'];
    $job_token = $_REQUEST['jenkins_token'];
    $job_name = $_REQUEST['job_name'];

    if ($job_name == '' || $job_token == '') {
        echo 'Job or token not specified, use job_name= and jenkins_token= in the URL path.' . "\n";
        return;
    }

    echo 'Recieved build request for ' . $job_name + "\n";

    // if 'text' param contains any variables separated by spaces, append to url
    $varArr = explode(" ", $text);

    $params = '?token=' . $job_token;
    foreach ($varArr as $var) {
        echo $var . "\n";
        $params .= '&' . $var;
    }

    if ($auth_user && $auth_pass) {
        $url = $auth_user . ":" . $auth_pass . "@" . $url;
    }

    $options = array();
    if ($auth_token) {
        echo 'Using Auth Token' . "\n";
        // build auth header
        $options = array('http' => 
            array(
                'method' => 'GET',
                'header' => 'Authorization: Bearer ' . $auth_token
            )
        );
    }

    $context = stream_context_create($options);
    $response = file_get_contents($url . '/job/' . $job_name . '/buildWithParameters' . $params, false, $context);
    echo $response + "\n";
?>