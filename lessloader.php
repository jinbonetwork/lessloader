<?php
require_once "less_cache.php";

extract($_GET);

$files = explode(',', $files);
$index = 0;
$baseDir = str_replace($_SERVER['PHP_SELF'], "", __FILE__);
foreach($files as $file):
	$files[$index] = $baseDir ."/". trim($file);
	$index++;
endforeach;

if(isset($id) && $id):
	$id = ".". $id;
else:
	$id = "";
endif;

if(isset($cache) && $cache == "true"):
	$cache_flag = true;
else:
	$cache_flag = false;
endif;

$params = array('items'=>$files, 'id'=>$id, 'cache_flag'=>$cache_flag);
lessloader($params);
?>
