<?php
/**
 * Undocumented function
 *
 * @param [type] $string
 * @param string $operation
 * @param string $key
 * @param integer $expiry
 * @author 一花 <487735913@qq.com>
 * @copyright Undocumented function [type]  string
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	$ckey_length = 4;
	$key = md5($key ? $key : '');//ENCRYPT_KEY
	$keya = md5(substr($key, 0, 16));
	$keyb = md5(substr($key, 16, 16));
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}
	if($operation == 'DECODE') {
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}

function daddslashes($string, $force = 0, $strip = FALSE) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(!MAGIC_QUOTES_GPC || $force) {
		if(is_array($string)) {
			foreach($string as $key => $val) {
				$string[$key] = daddslashes($val, $force, $strip);
			}
		} else {
			$string = addslashes($strip ? stripslashes($string) : $string);
		}
	}
	return $string;
}
/**
 * Undocumented function
 *
 * @param string $msg
 * @param boolean $die
 * @author 一花 <487735913@qq.com>
 * @copyright Undocumented function string  boolean
 */
function sysmsg($msg = '未知的异常',$die = true) {
    ?>  
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>站点提示信息</title>
        <style type="text/css">
html{background:#eee;text-align: center;}body{background:#fff;color:#333;font-family:"微软雅黑","Microsoft YaHei",sans-serif;margin:2em auto;padding:1em 2em;max-width:700px;-webkit-box-shadow:10px 10px 10px rgba(0,0,0,.13);box-shadow:10px 10px 10px rgba(0,0,0,.13);opacity:.8}h1{border-bottom:1px solid #dadada;clear:both;color:#666;font:24px "微软雅黑","Microsoft YaHei",,sans-serif;margin:30px 0 0 0;padding:0;padding-bottom:7px}#error-page{margin-top:50px}h3{text-align:center}#error-page p{font-size:9px;line-height:1.5;margin:25px 0 20px}#error-page code{font-family:Consolas,Monaco,monospace}ul li{margin-bottom:10px;font-size:9px}a{color:#21759B;text-decoration:none;margin-top:-10px}a:hover{color:#D54E21}.button{background:#f7f7f7;border:1px solid #ccc;color:#555;display:inline-block;text-decoration:none;font-size:9px;line-height:26px;height:28px;margin:0;padding:0 10px 1px;cursor:pointer;-webkit-border-radius:3px;-webkit-appearance:none;border-radius:3px;white-space:nowrap;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);box-shadow:inset 0 1px 0 #fff,0 1px 0 rgba(0,0,0,.08);vertical-align:top}.button.button-large{height:29px;line-height:28px;padding:0 12px}.button:focus,.button:hover{background:#fafafa;border-color:#999;color:#222}.button:focus{-webkit-box-shadow:1px 1px 1px rgba(0,0,0,.2);box-shadow:1px 1px 1px rgba(0,0,0,.2)}.button:active{background:#eee;border-color:#999;color:#333;-webkit-box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5);box-shadow:inset 0 2px 5px -3px rgba(0,0,0,.5)}table{table-layout:auto;border:1px solid #333;empty-cells:show;border-collapse:collapse}th{padding:4px;border:1px solid #333;overflow:hidden;color:#333;background:#eee}td{padding:4px;border:1px solid #333;overflow:hidden;color:#333}
        </style>
    </head>
    <body id="error-page">
        <?php echo '<h3>站点提示信息</h3>';
        echo $msg; ?>
    </body>
    </html>
    <?php
    if ($die == true) {
        exit;
    }
}
/**
 * Undocumented function
 *
 * @param [type] $length
 * @param [type] $qianzhui
 * @param integer $numeric
 * @author 一花 <487735913@qq.com>
 * @copyright Undocumented function [type]  [type]
 */
function random($length,$qianzhui=null,$numeric = 0) {
	$seed = base_convert(md5(microtime().$_SERVER['DOCUMENT_ROOT']), 16, $numeric ? 10 : 35);
	$seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed{mt_rand(0, $max)};
	}
	return $qianzhui!=null?$qianzhui.$hash:$hash;
}

function queryuserall($adminpassword,$adminport,$proxyaddress){
    $url = "http://".$proxyaddress.":".$adminport."/account";
    parse_url($url);
    //print_r();// 解析 URL，返回其组成部分
    $data = array();
    $query_str = http_build_query($data);// http_build_query()函数的作用是使用给出的关联（或下标）数组生成一个经过 URL-encode 的请求字符串
    $info = parse_url($url);
    $fp = fsockopen($proxyaddress,$adminport,$errno,$errstr,1000);
	if(!$fp) 
	{
        	// echo "$errstr ($errno)<br>\n";
            return false;
	} 
   else{
    $auth = "Authorization: Basic ".base64_encode("admin:".$adminpassword);
    $head = "GET " . $info['path']  . $query_str . " HTTP/1.0\r\n";
    $head .= "Host: " . $info['host'] . "\r\n".$auth."\r\n"."\r\n";
    $write = fputs($fp,$head);
    $line="";
    while(!feof($fp)){
        $line.= fread($fp,4096);
       // echo str_replace(array("<",">","/"),array("&lt;","&gt;",""), $line);
    }
    fclose($fp);
   }
  //echo $line; 
     //取出div标籤且id为PostContent的内容，并储存至阵列match
     preg_match_all('/<input .* name="username" .* value="(.*?)"/ui', $line, $match);
     preg_match_all('/<input .* name="password" .* value="(.*?)"/ui', $line, $match2);
     preg_match_all('/<input .* name="enable" .*/', $line, $match3);
     preg_match_all('/<input .* name="usepassword" .*/', $line, $match4);
     preg_match_all('/<input .* name="disabledate" .* value="(.*?)"/ui', $line, $match5);
     preg_match_all('/<input .* name="disabletime" .* value="(.*?)"/ui', $line, $match6);
     preg_match_all('/<input .* name="autodisable" .*/', $line, $match7);
     $ccp=array();
     $time=date("Y-m-d H:i:s");
     foreach($match[1] as $key => $use){
		// print(str_replace(array("<",">","/"),array(""),$match3[0][$key]));
		//=='input type="checkbox" name="enable" value="1" checked'?$match3[0][$key]=0:$match3[0][$key]=1
		strripos(str_replace(array("<",">","/"),array(""),$match3[0][$key]),"checked")!="46"?$match3[0][$key]=0:$match3[0][$key]=1;
         //str_replace(array("<",">","/"),array(""),$match3[0][$key])=='input type="checkbox" name="enable" value="1" checked'?$match3[0][$key]=0:$match3[0][$key]=1;
         strripos(str_replace(array("<",">","/"),array(""),$match4[0][$key]),"checked")!="51"?$match4[0][$key]=0:$match4[0][$key]=1;
         strripos(str_replace(array("<",">","/"),array(""),$match7[0][$key]),"checked")!="51"?$match7[0][$key]=0:$match7[0][$key]=1;
		 if($match[1][$key]==""){
			continue;
		 }
		 $ccp[$key]=array(
			 "id"=>$key,
             "user"=> $match[1][$key],
             "pwd"=> $match2[1][$key],
             "state"=> $match3[0][$key],
             "pwdstate"=> $match4[0][$key],
             "disabletime"=> $match5[1][$key]." ".$match6[1][$key],
             "expire"=>strtotime($time)>strtotime($match5[1][$key]." ".$match6[1][$key])?1:0,
         );
     }
     return $ccp;
}

function userquery($column,$ccp){
	//    ="admin";
		$result = array_filter($ccp, function ($where) use ($column) {
		   return $where['user'] == $column;
	   });
		// print_r($result);//打印全部数组
	  // $col=array_column($result,'disabletime');//expire
	 //  $col2=array_column($result,'expire');
	//	return $col2[0]==1?'<h5 style="color: red;display: inline;">到期时间：'.$col[0].'</h5>':($col[0]!=""?'<h5 style="color: #1E9FFF;display: inline;">到期时间：'.$col[0].'</h5>':'<h5 style="color: red;display: inline;">账号不存在</h5>');
	   return $result;
}

function WriteLog($operation,$msg,$operationer,$DB){
	$arr = array(
		'operation'  => addslashes(str_replace(array("<",">","/"),array("&lt;","&gt;",""),$operation)),
		'msg' => addslashes(str_replace(array("<",">","/"),array("&lt;","&gt;",""),$msg)),
		'operationer'     => addslashes(str_replace(array("<",">","/"),array("&lt;","&gt;",""),$operationer)),
		'ip'  => addslashes(str_replace(array("<",">","/"),array("&lt;","&gt;",""),x_real_ip()))
	);
	$exec=$DB->insert('log',$arr);
}

function UserUpdate($adminpassword,$adminport,$proxyaddress,$user,$password,$day,$userenable){
	$ser=query($adminpassword,$adminport,$proxyaddress);
	$date=userquery($user,$ser);
	if($date['disabletime']==""){
		return "-1";
	}else{
		$username = $user;
        $connection = '-1';
        $bandwidth = '-1';
        $cdate=date("Y-m-d H:i:s");
        $enddate=$date['expire']==0?date('Y-m-d H:i:s',strtotime($date['disabletime'].$day." day")):date('Y-m-d H:i:s',strtotime($cdate.$day." day"));
        // $enddate=date('Y-m-d H:i:s',strtotime("$date + ".$day." day"));
        $end_date = explode(" ", $enddate);
        $disabledate = $end_date[0];
        $disabletime = $end_date[1];
        
		$fp = fsockopen($proxyaddress, $adminport, $errno, $errstr, 1000);
        if (!$fp) {
            return ["code" => "无法连接到CCProxy", "icon" => "5"];
        } else {
            $url_ = "/account";
            $url = "edit=1"."&";
            $url = $url."autodisable=1"."&";
            $url = $userenable==""? "" : $url."enable=1"."&";
            $url = $url."usepassword=1"."&";
            $url = $url."enablesocks=1"."&";
            $url = $url."enablewww=0"."&";
            $url = $url."enabletelnet=0"."&";
            $url = $url."enabledial=0"."&";
            $url = $url."enableftp=0"."&";
            $url = $url."enableothers=0"."&";
            $url = $url."enablemail=0"."&";
            $url = $url."username=".$username."&";
            $url = $password==""?"":$url."password=".$password."&";
            $url = $url."connection=".$connection."&";
            $url = $url."bandwidth=".$bandwidth."&";
            $url = $url."disabledate=".$disabledate."&";
            $url = $url."disabletime=".$disabletime."&";
            $url = $url."userid=".$username;
            $len = "Content-Length: ".strlen($url);
            $auth = "Authorization: Basic " . base64_encode("admin" . ":" . $adminpassword);
            $msg = "POST ".$url_." HTTP/1.0\r\nHost: ".$proxyaddress."\r\n".$auth."\r\n".$len."\r\n"."\r\n".$url;
            fputs($fp, $msg);
            //echo $msg;
            while (!feof($fp)) {
                $s = fgets($fp, 4096);
                //echo $s;
            }
            fclose($fp);
			return "1";
		}

	}

}
/**
 * @description: 匹配是否有中文
 * @param {*} $str
 * @return {*}
 * @use: 
 */
function CheckStrChinese($str){
    $isMatched = preg_match_all('/^[A-Za-z0-9]+$/', $str);
    if ($isMatched) {
        return true;
    }
    return false;
}
/**
 * @description: 匹配密码密码可以包含数字、字母、下划线，并且要同时含有数字和字母，且长度要在8-16位之间!
 * @param {*} $str
 * @return {*}
 * @use: 
 */
function CheckStrPwd($str){
    $isMatched = preg_match_all('/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z_]{5,16}$/', $str);
    if ($isMatched) {
        return true;
    }
    return false;
}


?>