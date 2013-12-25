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
            $file_info = explode(";", $file);
            $file_ext = "";
            if(strripos($file_info[0], '.') !== FALSE){
                $file_ext = substr($file_info[0], strripos($file_info[0], '.'));
            }
            $mysqli->query("insert into files(computer, name, extension, size, modified_date)" .
                "values(" . $id . ",'" . $file_info[0] . "', '" . $file_ext . "', " . $file_info[1] .
                ", '" . gmdate("Y-m-d H:i:s", $file_info[2]) ."');");
        }
    }
}else{
    $mysqli->close();
    header("HTTP/1.0 404 Not Found");
    exit();
}

$mysqli->close();

?>
