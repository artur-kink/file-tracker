<?php

$name = $_GET["name"];
$ip = $_GET["ip"];

if($ip && $name){
    require "db.php";
    $mysqli->query("insert into computers(name, ip) values('" . $name . "', '" . $ip . "');");

    header("Location: computers.php");
}

?>
<form method="GET">
    <label for="name">Name: </label>
    <input name="name" type="text"/>
    <label for="ip">ip Address: </label>
    <input name="ip" type="text"/>
    <input type="submit" value="Add"/>
</form>