<?php
/**
 * ��Ԫ�����׼���ִ����Test.php��β���ļ�
 * ����ȫ������ ֱ��ִ��  phpunit Suite.php
 * ����ָ��ģ��(�ɶ��)���� ִ��  phpunit Suite.php ģ���� [ģ���� ...]
 */

require_once "PHPUnit/Framework.php";

class Suite extends PHPUnit_Framework_TestSuite
{
	public function __construct()
	{
		$this->addTestFileToSuit();
	}
	
	/**
	 * ��Ӳ����ļ����׼���
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
