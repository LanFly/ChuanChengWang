<?php
	session_start();
	error_reporting('null');
	$error = "";
	$msg = "";
	$fileElementName = 'fileToUpload';
	if(!empty($_FILES[$fileElementName]['error']))
	{
		switch($_FILES[$fileElementName]['error'])
		{

			case '1':
				$error = '警告：上传的文件太大';
				break;
			case '2':
				$error = '警告：上传的文件太大';
				break;
			case '3':
				$error = '警告：上传文件时中断';
				break;
			case '4':
				$error = '警告：没有选择上传文件';
				break;

			case '6':
				$error = '警告：文件夹不存在';
				break;
			case '7':
				$error = '警告：写入磁盘失败';
				break;
			case '8':
				$error = '警告：文件上传终止';
				break;
			case '999':
			default:
				$error = '警告：未知的错误';
		}
	}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
	{
		$error = '警告：没有选择上传文件';
	}else 
	{
			$w=unlink(".".$_SESSION['path']);
			$msg .= " File Name: " . $_FILES['fileToUpload']['name'] . ", ";
			$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name']);
			$arr_exp=explode(".",$_FILES['fileToUpload']['name']);						//用“.”把文件名分割成数组
			$length=count($arr_exp);													//计算数组的长度
			$exp=$arr_exp[$length-1];													//取数组的最后一个元素，即是文件的后缀名
			$micro=explode(".",microtime('msec'));										//用“.”把秒和毫妙分隔开
			move_uploaded_file($_FILES['fileToUpload']['tmp_name'],"../images/tou/".$micro[0].$micro[1].".".$exp);//使用秒和毫秒做文件名
			$_SESSION['path']="./images/tou/".$micro[0].$micro[1].".".$exp;					//把图片的路径放在session里，注册的时候存进数据库
			//for security reason, we force to remove all uploaded file
			@unlink($_FILES['fileToUpload']);
	}		
	echo "{";																			//返回json格式的文件信息
	echo				"error: '" . $error . "',\n";
	echo				"msg: '" . $msg . "',\n";
	echo 				"path: '" . $_SESSION['path'] . "',\n";
	echo "}";
?>