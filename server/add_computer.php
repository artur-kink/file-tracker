<?php

$name = $_GET["name"];
$ip = $_GET["ip"];
$paths = $_GET["paths"];
$extensions = $_GET["extensions"];

if($ip && $name && $paths && $extensions){
    require "db.php";
    $mysqli->query("insert into computers(name, ip, paths, extensions)" .
        "values('" . $name . "', '" . $ip . "', '" . $paths . "', '" . $extensions . "');");

    header("Location: computers.php");
}

?>
<form method="GET">
    <label for="name">Name: </label>
    <input name="name" type="text"/>
    <label for="ip">ip Address: </label>
    <input name="ip" type="text"/>
    <label for="paths">Paths: </label>
    <input name="paths" value="/" type="text"/>
    <label for="extensions">Extensions: </label>
    <input name="extensions" type="text"/>
    <br/>
    <input type="submit" value="Add"/>
</form>