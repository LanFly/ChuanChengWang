<?php
class sqlsafe {
	private $getfilter = "'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	private $postfilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	private $cookiefilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	/**
	 * 构造函数
	 */
	public function __construct() {
		foreach($_GET as $key=>$value){$this->stopattack($key,$value,$this->getfilter);}
		foreach($_POST as $key=>$value){$this->stopattack($key,$value,$this->postfilter);}
		foreach($_COOKIE as $key=>$value){$this->stopattack($key,$value,$this->cookiefilter);}
	}
	/**
	 * 参数检查并写日志
	 */
	public function stopattack($StrFiltKey, $StrFiltValue, $ArrFiltReq){
		if(is_array($StrFiltValue))$StrFiltValue = implode($StrFiltValue);
		if (preg_match("/".$ArrFiltReq."/is",$StrFiltValue) == 1){   
			$this->writeslog($_SERVER["REMOTE_ADDR"]."    ".strftime("%Y-%m-%d %H:%M:%S")."    ".$_SERVER["PHP_SELF"]."    ".$_SERVER["REQUEST_METHOD"]."    ".$StrFiltKey."    ".$StrFiltValue);
			// showmsg('您提交的参数非法,系统已记录您的本次操作！','',0,1);
		}
	}
	/**
	 * SQL注入日志
	 */
	public function writeslog($log){
		// echo $log;
		$log_path = './log/sql_log.txt';
		$ts = fopen($log_path,"a+");
		fputs($ts,$log."\r\n");
		fclose($ts);
	}
}
	session_start();
	if(!empty($_POST))
	{

		$salf=new sqlsafe();					// php防sql注入攻击,只要在php开头创建一个对象,构造函数会自动调用过滤函数.

		$nick="";
		$qq="";
		$sex="";
		$phone="";
		$college="";
		$intro="";

		$nick=addslashes($_POST['nick']);
		$qq=addslashes($_POST['qq']);
		$sex=addslashes($_POST['sex']);
		$phone=addslashes($_POST['phone']);
		$college=addslashes($_POST['college']);
		$intro=addslashes($_POST['intro']);

		$nick=htmlspecialchars($nick);
		$qq=htmlspecialchars($qq);
		$sex=htmlspecialchars($sex);
		$phone=htmlspecialchars($phone);
		$college=htmlspecialchars($college);
		$intro=htmlspecialchars($intro);

		$state="wrong";								//返回注册的状态
		if(is_numeric($qq) || $qq=="")
		{
			//这里执行插入数据库的操作，保存session，方便下一个页面调用
			$state="success";
			$message="信息更改成功";
			echo "$state.$message";
		}else
		{
			$message="你输入的QQ号无效";
			echo $state.".".$message;
		}
	
	
	}else
	{
		$state="wrong";
		$message="connect error,please try again";
		echo "$state.$message";
	}
?>