<?php
$id = $_GET["id"];
if($id && is_numeric($id)){
    require "db.php";
    $mysqli->query("delete from computers where id = " . $id);
    $mysqli->close();
}
header("Location: computers.php");
exit();
?>
