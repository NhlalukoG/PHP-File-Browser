<?php

$file = $_GET['file'];
$dir = dirname($file);
$proper = "/var/www/html/".$dir."/".basename($file);

if(file_exists($proper) && is_writable($proper) && unlink($proper)){
    echo "success";
}else{
    echo "error";
}

?>