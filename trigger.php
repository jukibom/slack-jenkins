<?php
    
    $config = require('config.php');
    $auth_token = $config['jenkins_auth_token'];

    $text = $_REQUEST['text'];
    $token = $_REQUEST['jenkins_token'];
    $job_name = $_REQUEST['job_name'];

    if ($job_name == '' || $token == '') {
        echo 'Job or token not specified, use job_name= and jenkins_token= in the URL path.' . "\n";
        return;
    }

    // if 'text' param contains any variables separated by spaces, append to url
    $varArr = explode(" ", $text);

    $params = '?token=' . $token;
    foreach ($varArr as $var) {
        $params .= '&' . $var;
    }

    file_get_contents($config['jenkins_url'] . '/job/' . $job_name . '/buildWithParameters' . $params,0);

?>