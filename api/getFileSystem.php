<?php
/* This will essentially run on a shell so let's think about security
 *
 * We must replace shell specific key words
 * ` ; $ $( " ' 
 * 
*/
function getFileSystem($dir){
    while(strpos($dir,"//") !== false)
        $dir = str_replace("//", "/",$dir);
    // Let's ask our program to get the directory list
    $output = shell_exec("cd '".__DIR__."'; ./DirectoryReader.sh '$dir'");

    return $output;
}
?>