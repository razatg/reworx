<?php
$myFile = "tmp/log.txt";
$fh = fopen($myFile, 'a+') or die("can't open file");

if ($fh){
    $headers = apache_request_headers();
    $postdata = file_get_contents("php://input");

    foreach ($headers as $header => $value) {
    //fwrite($fh, print_r("$header: $value \n", true));
}

fwrite($fh, print_r("$postdata \n", true));
fclose($fh);
}

echo "ok";
?>
