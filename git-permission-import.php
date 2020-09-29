<?php

$dir = "../norm/";

$permissionJson = file_get_contents($dir . "git-permission-store.json");
$filesArray = json_decode($permissionJson, true);

$changedArray = array();

$ignoreArray = array();

foreach ($filesArray as $file){
    $filePath = $file['filePath'];
    $fileName = $file['fileName'];

    if ($fileName == ".gitignore") {
        $fp = @fopen($filePath, 'r');
        if($fp){
            $gitignoreArray = explode("\n", fread($fp, filesize($filePath)));
            foreach ($gitignoreArray as $gitFile){
                $gitignoreFilePath = str_replace(".gitignore", $gitFile,$filePath);
                array_push($ignoreArray, $gitignoreFilePath);
            }
        }
    }
}

$cleanFileArray = array();

foreach ($filesArray as $file){
    $filePath = $file['filePath'];
    $lock = 0;
    foreach ($ignoreArray as $ignoreFilePath) {
        if (strpos($filePath, $ignoreFilePath) == false) {
            if ($lock == 0) {
                array_push($cleanFileArray, $file);
            }
            $lock++;
        }
    }
}

    echo count($cleanFileArray);

foreach ($cleanFileArray as $file){
    $filePath = $file['filePath'];
    $trueFilePath = $file['realFilePath'];
    $userName = $file['permName'];
    $userGroup = $file['permGroup'];

    $ownerChanged = changeOwners($filePath, $trueFilePath,  $userName);
    $groupChanged = changeOwnerGroup($filePath, $trueFilePath,  $userGroup);

    $permissionChangedArray = array(
        'ownerChanged' => $ownerChanged,
        'groupChanged' => $groupChanged,
        'filePath' => $filePath,
        'realFilePath' => $trueFilePath
    );

    array_push($changedArray, $permissionChangedArray);

}

$myfile = fopen("git-permission-store-changed.json", "w");
fwrite($myfile, json_encode($changedArray));
fclose($myfile);

function changeOwners($file , $trueFilePath,  $user){
    if((strpos($file, '.git') == false) || (strpos($trueFilePath, '.git') == false)){
        if(($file == $trueFilePath) || (strpos($trueFilePath, '/..')) || (strpos($trueFilePath, '/.'))){
            $chownReturn = chown($file, $user);
        }else{
            $chownReturn = lchown($trueFilePath, $user);
        }
    }else{
        $chownReturn = 'jsmith';
    }
    return $chownReturn;
}

function changeOwnerGroup($file, $trueFilePath, $group){
    if((strpos($file, '.git') == false) || (strpos($trueFilePath, '.git') == false)){
        if(($file == $trueFilePath) || (strpos($trueFilePath, '/..') != false ) || (strpos($trueFilePath, '/.') != false )){
            $chgrpReturn = chgrp($file, $group);
        }else{
            $chgrpReturn = lchgrp($trueFilePath, $group);
        }
    }else{
        $chgrpReturn = 'jsmith';
    }
    return $chgrpReturn;
}