<?php

function loadClass($className) {
    global $imports;
    if (isset($imports[$className])) {
        include_once($imports[$className]);
    }
}

$imports = array();
function import($import) {
    global $imports;
    
    if (!defined("SITE_PATH")) die('<!-- SITE_PATH is not defined -->');

    // seperate import into a package and a class
    $lastDot = strrpos($import, '.');
    $class = $lastDot ? substr($import, $lastDot + 1) : $import;
    $package = substr($import, 0, $lastDot);

    // if this import has already happened, return true
    if (isset($imports[$class]) || isset($imports[$package.'.*'])) return true;

    // create a folder path out of the package name
    $folder = ($package ? str_replace('.', '/', $package) : '');

    if ($folder == 'Zappy')
        $folder = SITE_PATH.'../../'.$folder;
    else
        $folder = SITE_PATH.$folder;

    $file = "$folder/$class.php";


    // make sure the folder exists
    if (!file_exists($folder)) {
        $back = debug_backtrace();
        error_log($package.' not found, looked in '.$folder.' ('.$back.')');
        die;
    } elseif ($class != '*' && !file_exists($file)) {
        $back = debug_backtrace();
        return false;
        // error_log($import.' not found, was looking for '.$class.' in '.$file);
    }

    if ($class != '*') {
        // add the class and it's file location to the imports array
        $imports[$class] = $file;
    } else {
        // add all the classes from this package and their file location to the imports array
        // first log the fact that this whole package was alread imported
        $imports["$package.*"] = 1;
        $dir = opendir($folder);
        while (($file = readdir($dir)) !== false) {
            if (strrpos($file, '.php')) {
                $class = str_replace('.php', '', $file);
                // put it in the import array!
                $imports[$class] = "$folder/$file";
            }
        }
    }
}

spl_autoload_register('loadClass');
?>
