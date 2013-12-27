<html>
    <head>
    <title>File List</title>
    
    <script type="text/javascript" src="scripts/jquery.1.4.2.min.js"></script>
    <script type="text/javascript" src="scripts/jquery.dataTables.min.js"></script>
    <link rel="Stylesheet" type="text/css" href="dataTables.css" />

    <script type="text/javascript">
        $("document").ready(function () {

            $("#files_table").dataTable({
                "sPaginationType": "full_numbers",
                "iDisplayLength": 20,
                "aLengthMenu": [[20, 50, -1], [20, 50, "All"]]
            });
        });
    </script>

    </head>
    <body>
        <table id="files_table" style="width: 100%;">
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