<?php

include('../../mainfile.php');
$IM_DIR = '/usr/bin/';
#header("Content-type: image/jpeg");
if ('thumb' == $_GET['type']) {
    $size = '400';
} else {
    if ('thumb75' == $_GET['type']) {
        $size = '75';
    } else {
        $size = '75';
    }
}

$dirn = XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['uploaddir'];

#  echo $dir.'/'.$_GET[filename];

# $command=XOOPS_ROOT_PATH."/image.pl $dirn/$_GET[image_url] $size";

# echo $command;
$command = $IM_DIR . "convert -size $size -resize $size +profile \"*\" $dirn/${_GET['image_url']} /dev/stdout ";
#echo $command;
 echo passthru("$command 2>&1");
