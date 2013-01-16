<?php 

class Ucenter_User_Base
{

	
	/**
	 * 权限字段
	 * @var $_privacy unknown_type
	 */
	protected $_privacy			= 0;
	
	......
	......
	
	/**
	 * 用16进制表示各种权限对应的值，类似php的报错级别那样
	 * 在前端页面渲染的时候各种权限的值也用这些数字(10进制数字)
	 * 所有的权限保存在privacy字段中
	 * 
	 * 设置权限的时候通过$mask掩码确保同一类型(比如生日)的权限只设置一个有效值
	 * 获取权限的时候由严格到宽松的权限获取，最后一个return的是默认的权限设置
	 */
	const ACCESS_BIRTH_ALL 		= 0x01;
	const ACCESS_BIRTH_DAY 		= 0x02;
	const ACCESS_BIRTH_SELF 	= 0x04;
	
	const ACCESS_QQ_ALL 		= 0x08;
	const ACCESS_QQ_FOLLOW 		= 0x10;
	const ACCESS_QQ_SELF		= 0x20;
	
	const ACCESS_BROKER_ALL 	= 0x40;
	const ACCESS_BROKER_FOLLOW 	= 0x80;
	const ACCESS_BROKER_SELF	= 0x100;
	
	const ACCESS_INVEST_ALL 	= 0x200;
	const ACCESS_INVEST_FOLLOW 	= 0x400;
	const ACCESS_INVEST_SELF	= 0x800;
	
	const ACCESS_EDUCATE_ALL 	= 0x1000;
	const ACCESS_EDUCATE_FOLLOW = 0x2000;
	const ACCESS_EDUCATE_SELF	= 0x4000;
	
	const ACCESS_WORK_ALL 		= 0x8000;
	const ACCESS_WORK_FOLLOW 	= 0x10000;
	const ACCESS_WORK_SELF		= 0x20000;	
	
	public function setBirthAccess($val)
	{
		$mask = 7;
		$this->_privacy = ~ $mask & $this->_privacy | $val;
	}
	
	public function getBirthAccess()
	{
		if ($this->_privacy & self::ACCESS_BIRTH_SELF)
			return self::ACCESS_BIRTH_SELF;
		if ($this->_privacy & self::ACCESS_BIRTH_DAY)
			return self::ACCESS_BIRTH_DAY;
		return self::ACCESS_BIRTH_ALL;
	}
	
	public function setQQAccess($val)
	{
		$mask = 7 << 3;
		$this->_privacy = ~ $mask & $this->_privacy | $val;
	}	
	
	public function getQQAccess()
	{

		if ($this->_privacy & self::ACCESS_QQ_SELF)
			return self::ACCESS_QQ_SELF;
		if ($this->_privacy & self::ACCESS_QQ_FOLLOW)
			return self::ACCESS_QQ_FOLLOW;
		return self::ACCESS_QQ_ALL;		
	}
	
	public function setBrokerAccess($val)
	{
		$mask = 7 << 6;
		$this->_privacy = ~ $mask & $this->_privacy | $val;
	}	
	
	public function getBrokerAccess()
	{
		if ($this->_privacy & self::ACCESS_BROKER_SELF)
			return self::ACCESS_BROKER_SELF;
		if ($this->_privacy & self::ACCESS_BROKER_FOLLOW)
			return self::ACCESS_BROKER_FOLLOW;
		return self::ACCESS_BROKER_ALL;		
	}	
	
	public function setInvestAccess($val)
	{
		$mask = 7 << 9;
		$this->_privacy = ~ $mask & $this->_privacy | $val;
	}		
	
	public function getInvestAccess()
	{
		if ($this->_privacy & self::ACCESS_INVEST_SELF)
			return self::ACCESS_INVEST_SELF;
		if ($this->_privacy & self::ACCESS_INVEST_FOLLOW)
			return self::ACCESS_INVEST_FOLLOW;
		return self::ACCESS_INVEST_ALL;		
	}	
	
	public function setEducateAccess($val)
	{
		$mask = 7 << 12;
		$this->_privacy = ~ $mask & $this->_privacy | $val;
	}
	
	public function getEducateAccess()
	{
		if ($this->_privacy & self::ACCESS_EDUCATE_SELF)
			return self::ACCESS_EDUCATE_SELF;
		if ($this->_privacy & self::ACCESS_EDUCATE_FOLLOW)
			return self::ACCESS_EDUCATE_FOLLOW;
		return self::ACCESS_EDUCATE_ALL;		
	}	

	public function setWorkAccess($val)
	{
		$mask = 7 << 15;
		$this->_privacy = ~ $mask & $this->_privacy | $val;
	}
		
	public function getWorkAccess()
	{
		if ($this->_privacy & self::ACCESS_WORK_SELF)
			return self::ACCESS_WORK_SELF;
		if ($this->_privacy & self::ACCESS_WORK_FOLLOW)
			return self::ACCESS_WORK_FOLLOW;
		return self::ACCESS_WORK_ALL;		
	}	
}

