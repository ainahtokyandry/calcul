<?php

$readmeFile = fopen('../README.md', 'r');
$lineCount = 0;
$file = [];

while (!feof($readmeFile)) {
    $ligne = fgets($readmeFile);
    array_push($file, [$ligne]);
}
echo json_encode($file);

/*$result = json_encode($file);
header('Content-Type: application/json');
http_response_code(200);
echo $result;*/

fclose($readmeFile);