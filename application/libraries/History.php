<?php
class History{
	
	private $file = "config/history_data.txt";
	private $delimeter = ">";
	
	public function __construct(){
		
	}
	
	public function clear(){
		write_file($this->file, "", "w+");
	}
	
	public function set($name, $data = array(), $clear = false){
		$mode = "a+";
		if ($clear){
			$mode = "w+";
		}
		
		$write = "#".$name.PHP_EOL;
		if ($data != null){
			foreach ($data as $w){
				$write .= $this->delimeter.PHP_EOL;
				foreach ($w as $key => $value){
					$write .= $key.":".$value.PHP_EOL;
				}
			}
		}
		write_file($this->file, $write, $mode);
	}
	
	public function get($name){
		$file = read_file($this->file);
		$lines = explode(PHP_EOL, $file);
		$end = false;
		$data = array();
		$j = -1;
		$i = 0;
		while ($i < sizeof($lines) && $end == false){
			if ($lines[$i] == "#".$name){
				$i++;
				while ($i < sizeof($lines) && strpos($lines[$i], "#") !== 0){
					if ($lines[$i] == $this->delimeter){
						$j++;
						$data[$j] = array();
					}
					else if ($lines[$i] != ""){
						$exp = explode(":", $lines[$i]);
						$data[$j][$exp[0]] = $exp[1];
					}
					$i++;
				}
				$end = true;
			}
			$i++;
		}
		return $data;
	}
	
}