<html>
    <head>
    <title>File List</title>
    </head>
    <body>
        <table>
            <thead><tr><th>Computer</th><th>File</th><th>Size</th><th>Modified Date</th></tr></thead>
            <tbody>
<?php
require "db.php";

$files = $mysqli->query("select * from detailed_files;");
if($files){
    while($row = $files->fetch_object()){
        echo "<tr>";
        echo "<td>" . $row->computer_name . "</td>";
        echo "<td>" . $row->path . "/" . $row->name . "</td>";
        echo "<td>" . $row->size . "</td>";
        echo "<td>" . $row->modified_date . "</td>";
        echo "</tr>";
    }
}

?>
            </tbody>
        </table>
    </body>
</html>