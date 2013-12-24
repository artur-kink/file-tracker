<?php

$id = $_POST["id"];
$authkey = $_POST["auth_key"];
$body = $_POST["body"];
if(!$id || !$authkey || !$body){
    header("HTTP/1.0 404 Not Found");
    exit();
}
$files = explode("\n", $body);

require "db.php";
$result = $mysqli->query("call check_authentication(" . $id . ",'" . $authkey . "');");
if($result){
    $mysqli->next_result();
    mysqli_free_result($result);

    foreach($files as $file){
        if(strlen($file) > 0){
            echo $file . "\n";
            $mysqli->query("insert into files(computer, name) values(" . $id . ",'" . $file . "');");
            echo "CALL failed: (" . $mysqli->errno . ") " . $mysqli->error;
        }
    }
}else{
    $mysqli->close();
    header("HTTP/1.0 404 Not Found");
    exit();
}

$mysqli->close();

?>
