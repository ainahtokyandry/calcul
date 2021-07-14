<?php

require '../Model/Algo.class.php';

if (isset($_POST['str'])) {
    $data = $_POST['str'];
    $init = new Algo($data);
    $result = $init->showResult();
    
    header('Content-Type: application/json');
    if ($result[0] == 'error') {
        http_response_code(400);
    } else {
        http_response_code(200);
    }
    echo json_encode($result);
}