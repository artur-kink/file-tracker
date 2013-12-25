<?php
$name = $_GET["name"];
require "db.php";
if($name && strlen($name) < 25 && strlen($name) > 0){
    $result = $mysqli->query("call register_computer('" . $name . "','" . $_SERVER["REMOTE_ADDR"] . "', '" . $_SERVER["REMOTE_HOST"] . "')");
    if($result){
        $response = $result->fetch_object();
        echo $response->id;
        echo "\n";
        echo $response->auth_key;
        $mysqli->next_result();
        mysqli_free_result($result);
    }else{
        header("HTTP/1.0 404 Not Found");
    }
}else{
    header("HTTP/1.0 404 Not Found");
}
?>
