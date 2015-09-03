<?php
    
    $url = getenv('JENKINS_URL');
    $auth_token = getenv('JENKINS_AUTH_TOKEN');
    $auth_user  = getenv('JENKINS_AUTH_BASIC_USERNAME');
    $auth_pass  = getenv('JENKINS_AUTH_BASIC_PASSWORD');

    $text = $_REQUEST['text'];
    $job_token = $_REQUEST['jenkins_token'];
    $job_name = $_REQUEST['job_name'];

    if ($job_name == '' || $job_token == '') {
        echo 'Job or token not specified, use job_name= and jenkins_token= in the URL path.' . "\n";
        return;
    }

    echo 'Recieved build request for ' . $job_name . "\n";

    // if 'text' param contains any variables separated by spaces, append to url
    $varArr = explode(" ", $text);

    $params = '?token=' . $job_token;
    foreach ($varArr as $var) {
        echo $var . "\n";
        $params .= '&' . $var;
    }

    $headers = array();
    $headers['method'] = 'GET';

    // if basic auth, insert into url
    if ($auth_user && $auth_pass) {
        $headers['header'] = 'Authorization: Basic ' . base64_encode("$auth_user:$auth_pass") . "\r\n";
        echo 'Using HTTP Auth user: ' . $auth_user . "\n";
    }

    
    if ($auth_token) {
        echo 'Using Auth Token' . "\n";
        // build auth header
        $headers['header'] .= 'Authorization: Bearer ' . $auth_token . "\r\n";
    }

    $options = array('http' => $headers);
    $context = stream_context_create($options);
    $response = file_get_contents($url . '/job/' . $job_name . '/buildWithParameters' . $params, false, $context);
    echo 'Build requested.' . "\n";
?>