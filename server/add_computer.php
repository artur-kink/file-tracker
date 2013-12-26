<?php

$name = $_GET["name"];
$ip = $_GET["ip"];

if($ip && $name){
    require "db.php";
    $mysqli->query("insert into computers(name, ip, auth_key) values('" . $name . "', '" . $ip . "', 'temp');");

    header("Location: computers.php");
}

?>
<form method="GET">
    <input name="name" type="text"/>
    <input name="ip" type="text"/>
    <input type="submit" value="Add"/>
</form>