<?php
/**
 * 涉及到队列的操作
 * 通过常量定义的类型获取具体的key配置
 * 常量以postscan开头的对应发给审核队列
 * 以sns开头对应发到个人中心队列
 * @author 黄永强<huangyongqiang@myhexin.com>
 *
 */
class Ucenter_Queue
{

	/**
	 * 观点发送到审核队列
	 * @var int
	 */
	const POSTSCAN_POST 		= 1;
	
	/**
	 * 评论发送到审核队列
	 * @var int
	 */
	const POSTSCAN_COMMENT 		= 2;

	/**
	 * 观点发送到sns队列
	 * @var int
	 */
	const SNS_POST_ADD		= 3;
	
	/**
	 * 评论发送到sns队列
	 * @var int
	 */	
	const SNS_COMMENT_ADD		= 4;
	
	/**
	 * 观点审核反馈
	 * @var int
	 */
	const SNS_POST_FEEDBACK		= 5;
	
	/**
	 * 评论审核反馈
	 * @var int
	 */	
	const SNS_COMMENT_FEEDBACK	= 6;
	
	/**
	 * 删除动态
	 * @var int
	 */
	const SNS_POST_DELETE 		= 7;
	

	private static $_postscanHanlde = null;
	private static $_snsHanlde 	 	= null;
	private static $_instance 		= null;

	
	private function __construct()
	{

	}
	
	private function _clone()
	{
		
	}
	
	/**
	 * 获取队操作列单例
	 */
	public static function getInstance($reset = false)
	{
		if (is_null(self::$_instance) || $reset) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	/**
	 * 获取队列的key值
	 * @param $type
	 */
	public function getKey($type)
	{
		if ($type === self::SNS_COMMENT_ADD)
			return Ucenter_Config::get("queue.sns.comment.key");
			
		if ($type === self::SNS_POST_FEEDBACK)
			return Ucenter_Config::get('postscan.feedback.post.appid');	
			
		if ($type === self::SNS_COMMENT_FEEDBACK)
			return Ucenter_Config::get("postscan.feedback.comment.appid");	
			
		if ($type === self::SNS_POST_DELETE)
			return Ucenter_Config::get('httpsqs.key.delpost');
			
		return null;	
	}
	
	/**
	 * 获取postsca队列操作
	 */
	private function _getPostcanHandle()
	{
		if (self::$_postscanHanlde == null) {
			$host 		= Ucenter_Config::get('postscan.sqs.host');
			$port 		= Ucenter_Config::get('postscan.sqs.port');		
			self::$_postscanHanlde	= Public_HttpSqs_Sdk::getInstance($host, $port);			
		}
		return self::$_postscanHanlde;
	}
	
	/**
	 * 获取sns队列操作
	 */
	private function _getSnsHandle()
	{
		if (self::$_snsHanlde == null) {
			$host 	= Ucenter_Config::get('httpsqs.host');
			$port 	= Ucenter_Config::get('httpsqs.port');
			self::$_snsHanlde	= Public_HttpSqs_Sdk::getInstance($host, $port);			
		}
		return self::$_snsHanlde;
	}	
	

	
	/**
	 * 往审核队列发送数据
	 * @param array $data
	 */
	public function toPostscan($data, $type)
	{
		if ($type === self::POSTSCAN_POST) {
			$config = Ucenter_Config::get("postscan.post")->toArray();
		} elseif ($type === self::POSTSCAN_COMMENT) {
			$config = Ucenter_Config::get("postscan.comment")->toArray();
		} else {
			return null;
		}
		$key		= Ucenter_Config::get("postscan.appkey");		
		$data = array_merge($data, $config);
		$data['appkey'] = $key;		
		$data  = serialize($data);	
		return $this->_getPostcanHandle()->put($key, "utf-8", $data);
	}
	
	/**
	 * 往sns队列发送数据
	 * @param array $data
	 */	
	public function toSns($data, $type)
	{
		if (null != ($key = $this->getKey($type))) {
			if (is_array($data))
				$data = serialize($data);
			return $this->_getSnsHandle()->put($key, "utf-8", $data);
		}
		return null;			
	}
	
	/**
	 * 从sns队列获取数据
	 * @param string $key
	 */
	public function getFromSns($type)
	{
		if (null != ($key = $this->getKey($type)))
			return $this->_getSnsHandle()->get($key);	
		return null;		
	}
}
