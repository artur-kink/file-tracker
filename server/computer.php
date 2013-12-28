<?php

$id = $_GET["id"];
$name = $_GET["name"];
$ip = $_GET["ip"];
$paths = $_GET["paths"];
$extensions = $_GET["extensions"];

if(!$id && $ip && $name && $paths && $extensions){
    require "db.php";
    $mysqli->query("insert into computers(name, ip, paths, extensions)" .
        "values('" . $name . "', '" . $ip . "', '" . $paths . "', '" . $extensions . "');");

    header("Location: computers.php");
    exit();
}else if($id && $ip && $name && $paths && $extensions){
    require "db.php";
    $mysqli->query("update computers set name = '" . $name . "', ip = '" . $ip .
        "', paths = '" . $paths . "', extensions = '" . $extensions . "' where id = " . $id);
    header("Location: computers.php");
    exit();
}else if($id){
    require "db.php";
    $result = $mysqli->query("select * from computers where id = " . $id);
    if($result){
        $computer = $result->fetch_object();
        $name = $computer->name;
        $ip = $computer->ip;
        $paths = $computer->paths;
        $extensions = $computer->extensions;
    }else{
        header("Location: computers.php");
        exit();
    }
}

?>
<form method="GET">
    <label for="name">Name: </label>
    <input name="name" type="text" value="<?php echo $name; ?>"/>
    <label for="ip">ip Address: </label>
    <input name="ip" type="text" value="<?php echo $ip; ?>"/>
    <label for="paths">Paths: </label>
    <input name="paths" type="text" value="<?php echo $paths; ?>"/>
    <label for="extensions">Extensions: </label>
    <input name="extensions" type="text" value="<?php echo $extensions; ?>"/>
    <br/>
<?php
if($id){
    echo "<input type='hidden' name='id' value='" . $id . "'/>";
    echo "<input type='submit' value='Save'/>";
}else{
    echo "<input type='submit' value='Add'/>";
}
?>
</form>