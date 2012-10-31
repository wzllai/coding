<?php
/**
 * 单元测试套件，执行以Test.php结尾的文件
 * 运行全部测试 直接执行  phpunit Suite.php
 * 运行指定模块(可多个)测试 执行  phpunit Suite.php 模块名 [模块名 ...]
 */

require_once "PHPUnit/Framework.php"; //测试框架在get_include_path()路径中

class Suite extends PHPUnit_Framework_TestSuite
{
	public function __construct()
	{
		$this->addTestFileToSuit();
	}
	
	/**
	 * 添加测试文件到套件中
	 */
	public function addTestFileToSuit()
	{
		$args = $_SERVER['argv'];
		unset($args[0], $args[1]);
		$path = dirname(__FILE__);
		if (empty($args)) {
			$this->_addTestFile($path);
		} else {
			foreach ($args as $name) {
				$filePath = $path .  "/" . $name;
				$this->_addTestFile($filePath);
			}
		}
	}
	
	private function _addTestFile($path)
	{
		$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
		while ($it->valid()) {
			if (!$it->isDot())
			if (!strcmp(substr($it->key(), -8), "Test.php")) {
				$this->addTestFile($it->key());
			}
			$it->next();
		}		
	}
	
	public static function suite()
	{
		return new self();
	}
}
