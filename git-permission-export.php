<?php

$dir = "../norm/";

$filesArray = array();

$it = new RecursiveDirectoryIterator($dir);
foreach(new RecursiveIteratorIterator($it) as $file)
{
    $fileNameAndPath = $file->getRealPath();
    $filePermisionName = getOwners($fileNameAndPath);
    $filePermisionGroup = getOwnerGroup($fileNameAndPath);

    $fileArray = array(
        'filePath' => $fileNameAndPath,
        'permName' => $filePermisionName,
        'permGroup' => $filePermisionGroup

    );

    array_push($filesArray, $fileArray);
}


//print_r($filesArray);

$myfile = fopen("git-permission-store.json", "w");
fwrite($myfile, json_encode($filesArray));
fclose($myfile);


function getOwners($file){
    $ownerID = fileowner($file);
    $owner = posix_getpwuid($ownerID);
    return $owner['name'];
}

function getOwnerGroup($file){
    $ownerGroupID = filegroup($file);
    $ownerGroup = posix_getgrgid($ownerGroupID);
    return $ownerGroup['name'];
}