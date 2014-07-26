<?php
class sqlsafe {
	private $getfilter = "'|(and|or)\\b.+?(>|<|=|in|like)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	private $postfilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	private $cookiefilter = "\\b(and|or)\\b.{1,6}?(=|>|<|\\bin\\b|\\blike\\b)|\\/\\*.+?\\*\\/|<\\s*script\\b|\\bEXEC\\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\\s+(TABLE|DATABASE)";
	/**
	 * 构造函数
	 */
	public function __construct($stu_id,$num) {
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
		fputs($ts,$log."    ".$stu_id."    ".$num."\r\n");
		fclose($ts);
	}
}
	session_start();
	if(!empty($_POST))
	{
		$salf=new sqlsafe($_POST['stu_id'],$_POST['num']);					// php防sql注入攻击,只要在php开头创建一个对象,构造函数会自动调用过滤函数.
		$name=addslashes($_POST['name']);
		$stu_id=addslashes($_POST['stu_id']);
		$num=addslashes($_POST['num']);
		$phone=addslashes($_POST['phone']);
		$pass=addslashes($_POST['pass']);

		$name=htmlspecialchars($name);
		$stu_id=htmlspecialchars($stu_id);
		$num=htmlspecialchars($num);
		$phone=htmlspecialchars($phone);
		$pass=htmlspecialchars($pass);

		$state="wrong";								//返回注册的状态
		if($name=="")
			$message="input name";					//返回注册的信息
		else if ($stu_id=="")
			$message="input stu_id";
		else if ($num=="")
			$message="input num";
		else if ($phone=="")
			$message="input phone";
		else if ($pass=="")
			$message="input pass";
		else
		{
			//这里执行插入数据库的操作，保存session，方便下一个页面调用
			$state="success";
			$message="sign up success";
			echo "$state.$message";
		}
	
	
	}else
	{
		$state="wrong";
		$message="connect error,please try again";
		echo "$state.$message";
	}
?>