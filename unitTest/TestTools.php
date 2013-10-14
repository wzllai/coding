<?php

/**
 * a tool of generating unit test case code skeletion for zendFramework
 * 
 * @author yongqiang
 * @time(2013-10-12)
 */
class TestTools {

	public $args;
	private $_basePath;
	const ERROR_LOST_PARAM_NAME 	= 1;
	const ERROR_LOST_PARAM_OTHER 	= 2;
	const ERROR_CODE_EXIT 			= 3;
	const ERROR_UNFUND				= 4;

	public function __construct() {
		$this->_basePath = '';
		$this->init();
		$this->parse();
	}

	public function init() {
		$shortOpt 	= "hn:M:csm";
		$longOpt 	= array("help", "name:", "module:", "controller", "service", "model");
		$options 	= getopt($shortOpt, $longOpt);

		if (isset($options['n']))
			$options['name'] = $options['n'];
		if (isset($options['M']))
			$options['module'] = $options['M'];
		if (isset($options['c']))
			$options['controller'] = $options['c'];
		if (isset($options['s']))
			$options['service'] = $options['s'];	
		if (isset($options['m']))
			$options['model'] = $options['m'];	

		$this->args = $options;
	}

	public function parse() {
		if (isset($this->args['h']) || isset($this->args['help'])) {
			self::help();
		} elseif (!isset($this->args['name'][0])) {
			$this->alert(self::ERROR_LOST_PARAM_NAME);
		} else {
			$this->getOriginalClass();
		}
	}

	public function getOriginalClass() {
		if (isset($this->args['module'][0])) {
			$this->_basePath .= 'modules/' . $this->args['module'] . "/";
		}
		if (isset($this->args['model'])) {
			$this->_basePath .= 'models/';
		} elseif (isset($this->args['service'])) {
			$this->_basePath .='services/';
		} elseif (isset($this->args['controller'])) {
			$this->_basePath .= 'controllers/';
		} 

		$file = APPLICATION_PATH . '/' . $this->_basePath . $this->args['name'] . '.php';
		if (is_file($file)) {
			require_once $file;	
		}	
		if (!class_exists($this->args['name'], false)) {
			$this->alert(self::ERROR_UNFUND);
		}		
	}

	public function generate() {
 		$className 	= $this->args['name'];
 		$date 		= date('Y-m-d H:i:s'); 
 		$reflect 	= new ReflectionClass($className);
 		$methods 	= $reflect->getMethods(ReflectionMethod::IS_PUBLIC);
 		$str = "<?php\nrequire 'Base.php';\n/**\n * this is {$this->args['name']} unitTestCase code\n * generated at {$date} \n *\n */\n class {$className}Test extends Base{\n";
 		foreach ($methods as $method) {
 			if ($method->class == $className && (substr($method->name, 0, 2) != '__')) {
 				$name = ucfirst($method->name);
 				$str .= "\n\tfunction test{$name}() {\n\t\n\t}\n";
 			}
 		}
 		$str .= "}\n";
 		if(!is_dir($this->_basePath)) {
 			mkdir($this->_basePath, 0755, true);
 		}
 		$file = $this->_basePath . $className . "Test.php";
 		if(is_file($file)) { 
 			$this->alert(self::ERROR_CODE_EXIT);
 		} else {
 			file_put_contents($file, $str);
 		}
	}

	public function alert($errorCode) {
		switch ($errorCode) {
			case self::ERROR_LOST_PARAM_NAME :
				echo "the name of class must be hinted...\n"; break;
			case self::ERROR_LOST_PARAM_OTHER :
				echo "one of the controller and service and model must be hinted...\n"; break;
			case self::ERROR_CODE_EXIT :
				echo "the unitTestCase code has been exist...\n"; break;	
			case self::ERROR_UNFUND :
				echo "the class code can  not be found...\n"; break;
			default :
				self::help();
		}
		//$this->help();
		exit;
	}


	public static function help() {
		echo <<<HELP
this tools can generate unitTestCase code skeleton.
Options: 
	-h, --help 
		show this help message.
	-n, --name
		the name of class which will be taken to genenrate unitTestCase skeleton.the name is case insenstive.
	-M, --module
		hint the application module.
	-c, --controller
		hint the controller layer.
	-s, --service 
		hint the service or business layer.
	-m, --model
		hint the model layer.\n

HELP;
		exit(0);
	}

	public static function my_load($class) {
		$path = str_replace('_', '/', $class);
		require_once $path . '.php';
	} 

	public static function main() {
		define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));
		set_include_path(implode(PATH_SEPARATOR, array(
			APPLICATION_PATH . '/../library',
			get_include_path(),
		)));

		spl_autoload_unregister('__autoload');
		spl_autoload_register('TestTools::my_load');
		$tools = new self();
		$tools->generate();
	}
}

TestTools::main();