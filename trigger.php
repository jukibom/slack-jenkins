<?php
    
    $config = require('config.php');
    $text = $_REQUEST['text'];
    $token = $_REQUEST['jenkins_token'];
    $job_name = $_REQUEST['job_name'];

    // if 'text' param contains any variables separated by spaces, append to url
    $varArr = explode(" ", $text);

    $params = "";
    foreach ($varArr as $var) {
        $params .= "&" . $var;
    }

    file_get_contents($config['jenkins_url'] . '/buildByToken/buildWithParameters' . $params,0);

?>