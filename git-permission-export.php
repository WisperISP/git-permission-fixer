<?php

$dir = "../norm/";

$filesArray = array();

$it = new RecursiveDirectoryIterator($dir);
foreach(new RecursiveIteratorIterator($it) as $file)
{
    $fileNameAndPath = $file->getRealPath();
    $filePermisionName = getOwners($fileNameAndPath);
    $filePermisionGroup = getOwnerGroup($fileNameAndPath);
    $filePermisions = fileperms($fileNameAndPath);
    $fileName = $file->getFilename();

    $trueFilePath = $file->getPathname();

    $fileArray = array(
        'filePath' => $fileNameAndPath,
        'fileName' => $fileName,
        'permName' => $filePermisionName,
        'permGroup' => $filePermisionGroup,
        'filePerms' => $filePermisions,
        'realFilePath' => str_replace("../","/usr/local/", $trueFilePath)

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
    if($owner['name'] == null){
        $owner['name'] = $ownerID;
    }
    return $owner['name'];
}

function getOwnerGroup($file){
    $ownerGroupID = filegroup($file);
    $ownerGroup = posix_getgrgid($ownerGroupID);
    return $ownerGroup['name'];
}