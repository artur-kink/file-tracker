<html>
    <head>
    <title>Computers</title>
    </head>
    <body>
        <a href="add_computer.php">Add Computer</a>
        <table>
            <thead><tr><th>Name</th><th>Ip</th><th>Last Registerred</th><th>Last Activity</th></tr></thead>
            <tbody>
<?php
require "db.php";

$computers = $mysqli->query("select * from computers;");
if($computers){
    while($row = $computers->fetch_object()){
        echo "<tr>";
        echo "<td>" . $row->name . "</td>";
        echo "<td>" . $row->ip . "</td>";
        echo "<td>" . $row->last_register_date . "</td>";
        echo "<td>" . $row->last_activity_date . "</td>";
        echo "<td><a href='delete_computer.php?id=" . $row->id . "'>Delete</a></td>";
        echo "</tr>";
    }
}

?>
            </tbody>
        </table>
    </body>
</html>