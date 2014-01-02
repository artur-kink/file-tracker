<html>
    <head>
    <title>Computers</title>
    <link rel="Stylesheet" type="text/css" href="style.css"/> 
    </head>
    <body>
        <div class="icon_button"><a href="computer.php"><img src="images/add.png"/>Add Computer</a></div>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Ip</th>
                    <th>Paths</th>
                    <th>Extensions</th>
                    <th>Last Registerred</th>
                    <th>Last Activity</th>
                </tr>
            </thead>
            <tbody>
<?php
require "db.php";

$computers = $mysqli->query("select * from computers;");
if($computers){
    while($row = $computers->fetch_object()){
        echo "<tr>";
        echo "<td><a href='computer.php?id=" . $row->id . "'>" . $row->name . "</a></td>";
        echo "<td>" . $row->ip . "</td>";
        echo "<td>" . $row->paths . "</td>";
        echo "<td>" . $row->extensions . "</td>";
        echo "<td>" . $row->last_register_date . "</td>";
        echo "<td>" . $row->last_activity_date . "</td>";
        echo "<td><div class='icon_button'><a href='delete_computer.php?id=" . $row->id . "'><img src='images/delete.png' alt='Delete'></img></a></div></td>";
        echo "</tr>";
    }
}

?>
            </tbody>
        </table>
    </body>
</html>
