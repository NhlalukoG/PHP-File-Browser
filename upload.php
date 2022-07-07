<?php

$total = count($_FILES['filesToUpload']['name']);
$target_dir = isset($_POST['saveTo']) ? $_POST['saveTo'] : die();
// Loop through each file
for( $i=0 ; $i < $total ; $i++ ) {
    //Get the temp file path
    $tmpFilePath = $_FILES['filesToUpload']['tmp_name'][$i];
    //Make sure we have a file path
    if ($tmpFilePath != ""){
        //Setup our new file path
        $newFilePath = "/var/www/html/".$target_dir."/".$_FILES['filesToUpload']['name'][$i];
        //echo $newFilePath; die();
        //Upload the file into the temp dir
        // Let's fix the paths
        while(strpos($newFilePath,"//") !== false)
            $newFilePath = str_replace("//", "/",$newFilePath);
        if(!move_uploaded_file($tmpFilePath, $newFilePath)) {
            echo "error";
            break;
        }
    }
}
echo "success";
?>