<?php
$name = $_GET["name"];
require "db.php";
if($name && strlen($name) < 25 && strlen($name) > 0){
    //Generate auth key
    $auth_source = "";
    for($i = 0; $i < 25; $i++){
        $auth_source = $auth_source . chr(rand(0, 127));
    }

    $auth_key = hash('sha256', $auth_source);

    $result = $mysqli->query("call register_computer('" . $name . "','" . $_SERVER["REMOTE_ADDR"] .
        "', '" . $_SERVER["REMOTE_HOST"] . "', '" . $auth_key . "')");
    if($result){
        $response = $result->fetch_object();
        echo $response->id;
        echo "\n";
        echo $response->auth_key;
        $mysqli->next_result();
        $mysqli->next_result();
        mysqli_free_result($result);
    }else{
        header("HTTP/1.0 404 Not Found");
    }
}else{
    header("HTTP/1.0 404 Not Found");
}
?>
