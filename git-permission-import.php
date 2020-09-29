<?php

$dir = "../norm/";

$permissionJson = file_get_contents($dir . "git-permission-store.json");
$filesArray = json_decode($permissionJson, true);

$changedArray = array();

foreach ($filesArray as $file){
    $filePath = $file['filePath'];
    $trueFilePath = $file['realFilePath'];
    $userName = $file['permName'];
    $userGroup = $file['permGroup'];

    $ownerChanged = changeOwners($filePath, $trueFilePath,  $userName);
    $groupChanged = changeOwnerGroup($filePath, $trueFilePath,  $userGroup);

    $permissionChangedArray = array(
        'ownerChanged' => $ownerChanged,
        'groupChanged' => $groupChanged
    );

    array_push($changedArray, $permissionChangedArray);

}

$myfile = fopen("git-permission-store-changed.json", "w");
fwrite($myfile, json_encode($changedArray));
fclose($myfile);

function changeOwners($file , $trueFilePath,  $user){
    $chownReturn = chown($file, $user);
    if ($chownReturn == null){
        $chownReturn = lchown($trueFilePath, $user);
    }
    return $chownReturn;
}

function changeOwnerGroup($file, $trueFilePath, $group){
    $chgrpReturn = chgrp($file, $group);
    if ($chgrpReturn == null){
        $chgrpReturn = lchgrp($trueFilePath, $group);
    }
    return $chgrpReturn;
}