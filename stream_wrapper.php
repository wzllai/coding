<?php
class My_Wrapper
{
	public function __construct()
	{
		echo "begin" . PHP_EOL;
	}
	
	public function url_stat($path, $flag)
	{
		echo "url_stat" . PHP_EOL;
		return stat("file");
	}
	
	public function stream_stat()
	{
		return array();
	}

	public function stream_open($path, $mod, $options, &$open_path)
	{
		$this->fp = fopen("file", $mod);
		return true;
	
	}
	public function stream_read($count)
	{
		return fread($this->fp, $count);
	}

	public function stream_eof()
	{
		return true;
	}
	
	public function __destruct()
	{
		echo "end" . PHP_EOL;
	}
}

stream_wrapper_register("my", "My_Wrapper");
set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__);
$fp = fopen("my://file", "r");
$return = fread($fp, 3);
fclose($fp);
echo $return;
include "my://file";
