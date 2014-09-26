<?php
require_once "less_cache.php";
require_once "config.php";

extract($_GET);

$files = explode(',', $files);
$index = 0;
foreach($files as $file):
	$files[$index] = BASE_DIR ."/". trim($file);
	$index++;
endforeach;

if($id):
	 $id = ".". $id;
endif;

if(isset($cache) && $cache == "true"):
	$cache_flag = true;
else:
	$cache_flag = false;
endif;

$params = array('items'=>$files, 'id'=>$id, 'cache_flag'=>$cache_flag);
lessloader($params);
?>
