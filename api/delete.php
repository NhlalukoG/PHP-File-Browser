<?php

$file = $_GET['file'];
$dir = dirname($file);
$proper = "/var/www/html/".$dir."/".basename($file);

if(file_exists($proper) && is_writable($proper) && unlink($proper)){
    echo file_get_contents("http://95.179.181.97/api/browser/getFileSystem.php?dir=$dir");
}

?>