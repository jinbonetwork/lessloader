<?php
function lessloader($params){
	extract($params);	
	/*
	 * lessc loader
	 *
	 * required
	 *   $items = array();
	 *
	 * extrapolable
	 *   $cache_flag = false;
	 *   $lessc_path = '';
	 *   $base_path = '';
	 *   $cache_path = '';
	 *   $output_path = '';
	*/
	if(isset($items)&&!empty($items)):
		$cache_flag = isset($cache_flag)?$cache_flag:false;		

		$base_path = isset($base_path)&&file_exists($base_path)?$base_path:dirname(__FILE__);
		$lessc_path = class_exists('lessc')||isset($lessc_path)&&file_exists($lessc_path)?$lessc_path:dirname(__FILE__).'/lessphp/lessc.inc.php';
		$cache_path = isset($cache_path)&&file_exists($cache_path)?$cache_path:$base_path."/cache/style.cache".$id.".less";
		$output_path = isset($output_path)&&file_exists($output_path)?$output_path:$base_path."/cache/style.cache".$id.".css";
	
		$cache_location = explode('/',$cache_path);
		$cache_file = end($cache_location);
		$cache_mtime = file_exists($cache_path)?filemtime($cache_path):0;

		$output_location = explode('/',$output_path);
		$output_file = end($output_location);
		$output_mtime = file_exists($output_path)?filemtime($output_path):0;

		$log = array();
		$log_print = '';

		$log['master'] = '';
		$log[$cache_file] = $cache_mtime;
		$log[$output_file] = $output_mtime;
		$log['local'] = '';

		foreach($items as $item):
			$item_path = file_exists($item)?$item:$base_path.'/'.$item;
			if(file_exists($item_path)):
				$item_mtime = filemtime($item_path);
				$files[] = $item_path;
				if($cache_mtime<$item_mtime):
					$cache_flag = true;
					$item_check = 'new';
				else:
					$item_check = 'old';
				endif;
				$item_status = $item_mtime.' -> '.$item_check;
			else:
				$item_status = 'not found:';
			endif;
			$item_location = explode('/',$item_path);
			$item_file = end($item_location);
			$log[$item_file] = $item_status;
		endforeach;

		if(!file_exists($cache_path)||!file_exists($output_path)||$cache_flag):
			$log['flag'] = 'true';
			$code = '';
			foreach( $files as $file ):
				$code .= file_get_contents($file).PHP_EOL;
			endforeach;
			file_put_contents($cache_path,$code);
			require_once $lessc_path;
			$object = new lessc;
			$result = $object->compile(file_get_contents($cache_path));
			file_put_contents($output_path,$result);
		else:
			$log['flag'] = 'false';
		endif;

		if(count($log)):
			foreach($log as $key => $value):
				$key = ($value&&$key!='flag'?' *   ':' * ').$key;
				$value = ($value?' : ':'').$value;
				$log_print .= PHP_EOL.$key.$value;
			endforeach;
			$log_print = PHP_EOL.'/*'.$log_print.PHP_EOL.'*/';
		endif;

		header('Content-type: text/css');
		echo file_get_contents($output_path).$log_print;
	endif; // isset($items)
}
?>
