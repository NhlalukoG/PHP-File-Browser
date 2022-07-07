<?php
// This is for GUI Apps
if(isset($_GET['dir'])){
    $dir = str_replace("/var/www/html","",dirname("/var/www/html/".$_GET['dir']));
    // Let's perform a change back and get the file system
    echo file_get_contents("http://95.179.181.97/api/browser/getFileSystem.php?dir=$dir");
}

?>