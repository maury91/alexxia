<?php
class TASK {
	private static $tasks=null;

	private static function load(){
		if (self::$tasks==null) {
			$tasks=array();
			//Open dir
			if ($handle = opendir(__base_path.'_data/tasks/')) {
				//List directory
				while ($file = readdir($handle)) {
					//Check if is a directory
					if (!is_dir(__base_path.'_data/tasks/'.$file)) {
						//Open the task
						if (FUNCTIONS::fext($file)=='last')
							$tasks[substr($file,0,strrpos($file,'.'))] = json_decode(file_get_contents(__base_path.'_data/tasks/'.$file),true);
					}
				}
			}
			//Close directory
			closedir($handle);
			self::$tasks = $tasks;
		}
	}

	public static function create($id,$file,$month='*',$d='*',$h='*',$m='0',$s='0',$params=array()) {
		//Task loading
		self::load();
		//Update task list
		self::$tasks[$id] = array('time'=>0,'params'=>$params,'file'=>$file);
		//Make files
		file_put_contents(__base_path.'_data/tasks/'.$id.'.data', json_encode(array('M'=>$month,'d'=>$d,'h'=>$h,'m'=>$m,'s'=>$s)));
		file_put_contents(__base_path.'_data/tasks/'.$id.'.last', json_encode(self::$tasks[$id]));
		//Update task execution		
		self::update($id);
	}

	public static function del($id) {
		@unlink(__base_path.'_data/tasks/'.$id.'.data');
		@unlink(__base_path.'_data/tasks/'.$id.'.last');
	}

	public static function exists($id) {
		return file_exists(__base_path.'_data/tasks/'.$id.'.last');
	}

	private static function process_date($format,$cur,$max) {
		if ($format=='*')
			return $cur;
		if (intval($format)==$format)
			return intval($format);		
		if (substr($format,0,2)=='*/') {
			$x = ($max/intval(substr($format,2)));
			return floor((floor($cur/$x)+1)*$x);
		}
	}

	private static function update($fname) {
		//Open info about the task
		$task_info = json_decode(file_get_contents(__base_path.'_data/tasks/'.$fname.'.data'),true);
		//Calcola in che momento cadrà il prossimo task
		$cur = getdate();
		$old = $cur['seconds'];
		$cur['seconds'] = self::process_date($task_info['s'],$cur['seconds']+1,60);
		if ($cur['seconds']<$old)
			$cur['minutes']++;
		$old = $cur['minutes'];
		$cur['minutes'] = self::process_date($task_info['m'],$cur['minutes'],60);
		if ($cur['minutes']<$old)
			$cur['hours']++;
		$old = $cur['hours'];
		$cur['hours'] = self::process_date($task_info['h'],$cur['hours'],24);
		if ($cur['hours']<$old)
			$cur['mday']++;
		$old = $cur['mday'];
		$cur['mday'] = self::process_date($task_info['d'],$cur['mday'],30);
		if ($cur['mday']<$old)
			$cur['mon']++;
		$old = $cur['mon'];
		$cur['mon'] = self::process_date($task_info['M'],$cur['mon'],12);
		if ($cur['mon']<$old)
			$cur['year']++;
		self::$tasks[$fname]['time'] = mktime($cur['hours'],$cur['minutes'],$cur['seconds'],$cur['mon'],$cur['mday']);
		file_put_contents(__base_path.'_data/tasks/'.$fname.'.last', json_encode(self::$tasks[$fname]));
	}

	public static function exec() {
		//Task loading
		self::load();
		//Task execution
		foreach (self::$tasks as $k => $v) {
			echo date(DATE_RFC822)."<br/>";
			echo date(DATE_RFC822,$v['time']);
			if (time()>=$v['time']) {
				//Reorganizzate the task
				self::update($k);
				//Execute task
				$params = $v['params'];
				include(__base_path.$v['file']);
			}
		}
	}
}


?>