<?php
//include_once "wxBizMsgCrypt.php";
//include_once "WXBizMsgCrypt.php";
include_once(dirname(__FILE__)."/callback/wxBizMsgCrypt.php");
// 假设企业号在公众平台上设置的参数如下
$encodingAesKey = "";
$token = "";
$corpid = "";
$appid = "";
//公众号服务器数据
$sReqMsgSig = $sVerifyMsgSig = $_GET['msg_signature'];
$sReqTimeStamp = $sVerifyTimeStamp = $_GET['timestamp'];
$sReqNonce = $sVerifyNonce = $_GET['nonce'];
$sReqData = file_put_contents("text.txt","start\n\n",FILE_APPEND);
$sVerifyEchoStr = $_GET['echostr'];
$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpid);

file_put_contents("text.txt", 'msg_signature:'.$sReqMsgSig."\n",FILE_APPEND);
file_put_contents("text.txt", 'timestamp:'.$sReqTimeStamp."\n",FILE_APPEND);
file_put_contents("text.txt", 'nonce:'.$sReqNonce."\n",FILE_APPEND);
file_put_contents("text.txt", 'verfyEchoStr:'.$sVerifyEchoStr."\n",FILE_APPEND);

if($sVerifyEchoStr) {
    $sEchoStr = "";
    $errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
    if ($errCode == 0) {
        file_put_contents("text.txt", "test111\n",FILE_APPEND);
        print($sEchoStr);
    } else {
        echo 'aaa';
        // print('ErrorCode:'.$errCode . "\n\n");
    }
    exit;
}

/*
 ------------使用示例二：对用户回复的消息解密---------------
 用户回复消息或者点击事件响应时，企业会收到回调消息，此消息是经过公众平台加密之后的密文以post形式发送给企业，密文格式请参考官方文档
 假设企业收到公众平台的回调消息如下：
 POST /cgi-bin/wxpush? msg_signature=477715d11cdb4164915debcba66cb864d751f3e6&timestamp=1409659813&nonce=1372623149 HTTP/1.1
 Host: qy.weixin.qq.com
 Content-Length: 613
 <xml>
 <ToUserName><![CDATA[wx5823bf96d3bd56c7]]></ToUserName><Encrypt><![CDATA[RypEvHKD8QQKFhvQ6QleEB4J58tiPdvo+rtK1I9qca6aM/wvqnLSV5zEPeusUiX5L5X/0lWfrf0QADHHhGd3QczcdCUpj911L3vg3W/sYYvuJTs3TUUkSUXxaccAS0qhxchrRYt66wiSpGLYL42aM6A8dTT+6k4aSknmPj48kzJs8qLjvd4Xgpue06DOdnLxAUHzM6+kDZ+HMZfJYuR+LtwGc2hgf5gsijff0ekUNXZiqATP7PF5mZxZ3Izoun1s4zG4LUMnvw2r+KqCKIw+3IQH03v+BCA9nMELNqbSf6tiWSrXJB3LAVGUcallcrw8V2t9EL4EhzJWrQUax5wLVMNS0+rUPA3k22Ncx4XXZS9o0MBH27Bo6BpNelZpS+/uh9KsNlY6bHCmJU9p8g7m3fVKn28H3KDYA5Pl/T8Z1ptDAVe0lXdQ2YoyyH2uyPIGHBZZIs2pDBS8R07+qN+E7Q==]]></Encrypt>
 <AgentID><![CDATA[218]]></AgentID>
 </xml>
 
 企业收到post请求之后应该
 1.解析出url上的参数，包括消息体签名(msg_signature)，时间戳(timestamp)以及随机数字串(nonce)
 2.验证消息体签名的正确性。
 3.将post请求的数据进行xml解析，并将<Encrypt>标签的内容进行解密，解密出来的明文即是用户回复消息的明文，明文格式请参考官方文档
 第2，3步可以用公众平台提供的库函数DecryptMsg来实现。
 */

$a = $GLOBALS["HTTP_RAW_POST_DATA"];

file_put_contents("text.txt", "\n***".$_POST["msg_signature"]."\n***",FILE_APPEND);


// $sReqMsgSig = HttpUtils.ParseUrl("msg_signature");
//$sReqMsgSig = "477715d11cdb4164915debcba66cb864d751f3e6";
// $sReqTimeStamp = HttpUtils.ParseUrl("timestamp");
//$sReqTimeStamp = "1409659813";
// $sReqNonce = HttpUtils.ParseUrl("nonce");
//$sReqNonce = "1372623149";
// post请求的密文数据
// $sReqData = HttpUtils.PostData();
$sReqData = $GLOBALS["HTTP_RAW_POST_DATA"];
$sMsg = "";  // 解析之后的明文
$errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
if ($errCode == 0) {
    // 解密成功，sMsg即为xml格式的明文
    // TODO: 对明文的处理
    // For example:
    $xml = new DOMDocument();
    $xml->loadXML($sMsg);
    $reqToUserName = $xml->getElementsByTagName('ToUserName')->item(0)->nodeValue;
    file_put_contents("text.txt", 'reqToUserName:'.$reqToUserName."\n",FILE_APPEND);
    $reqFromUserName = $xml->getElementsByTagName('FromUserName')->item(0)->nodeValue;
    file_put_contents("text.txt", 'reqToUserName:'.$reqFromUserName."\n",FILE_APPEND);
    $reqCreateTime = $xml->getElementsByTagName('CreateTime')->item(0)->nodeValue;
    $reqMsgType = $xml->getElementsByTagName('MsgType')->item(0)->nodeValue;
    $reqContent = $xml->getElementsByTagName('Content')->item(0)->nodeValue;
    $reqMsgId = $xml->getElementsByTagName('MsgId')->item(0)->nodeValue;
    $reqAgentID = $xml->getElementsByTagName('AgentID')->item(0)->nodeValue;
    
    switch($reqContent){
        case "马云":
            $mycontent="您好，马云！我知道您创建了阿里巴巴！";
            break;
        case "马化腾":
            $mycontent="您好，马化腾！我知道创建了企鹅帝国！";
            break;
        case "史玉柱":
            $mycontent="您好，史玉柱！我知道您创建了巨人网络！";
            break;
        default :
            $mycontent="你是谁啊？！一边凉快去！";
            break;
    }
    
    $sRespData =
    "<xml>
    <ToUserName><![CDATA[".$reqFromUserName."]]></ToUserName>
    <FromUserName><![CDATA[".$corpId."]]></FromUserName>
    <CreateTime>".sReqTimeStamp."</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[".$mycontent."]]></Content>
    </xml>";
    $sEncryptMsg = ""; //xml格式的密文
    $errCode = $wxcpt->EncryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
    
    if ($errCode == 0) {
        //file_put_contents('smg_response.txt', $sEncryptMsg); //debug:查看smg
        print($sEncryptMsg);
        
        // $content = $xml->getElementsByTagName('Content')->item(0)->nodeValue;
        // print("content: " . $content . "\n\n");
        //file_put_contents("text.txt", "\n@@@".$content."\n@@@",FILE_APPEND);
        
        // ...
        // ...
    } else {
        print("ERR: " . $errCode . "\n\n");
        //exit(-1);
    }
}






/*
 //decrypt
 $sMsg = "";  //解析之后的明文
 $sReqData = "<xml><ToUserName><![CDATA[wx7900a2974c7efa7f]]></ToUserName><Encrypt><![CDATA[RypEvHKD8QQKFhvQ6QleEB4J58tiPdvo+rtK1I9qca6aM/wvqnLSV5zEPeusUiX5L5X/0lWfrf0QADHHhGd3QczcdCUpj911L3vg3W/sYYvuJTs3TUUkSUXxaccAS0qhxchrRYt66wiSpGLYL42aM6A8dTT+6k4aSknmPj48kzJs8qLjvd4Xgpue06DOdnLxAUHzM6+kDZ+HMZfJYuR+LtwGc2hgf5gsijff0ekUNXZiqATP7PF5mZxZ3Izoun1s4zG4LUMnvw2r+KqCKIw+3IQH03v+BCA9nMELNqbSf6tiWSrXJB3LAVGUcallcrw8V2t9EL4EhzJWrQUax5wLVMNS0+rUPA3k22Ncx4XXZS9o0MBH27Bo6BpNelZpS+/uh9KsNlY6bHCmJU9p8g7m3fVKn28H3KDYA5Pl/T8Z1ptDAVe0lXdQ2YoyyH2uyPIGHBZZIs2pDBS8R07+qN+E7Q==]]></Encrypt><AgentID><![CDATA[218]]></AgentID></xml>";
 
 $errCode = $wxcpt->DecryptMsg($sReqMsgSig, $sReqTimeStamp, $sReqNonce, $sReqData, $sMsg);
 if ($errCode == 0) {
 $xml = new DOMDocument();
 $xml->loadXML($sMsg);
 $reqToUserName = $xml->getElementsByTagName('ToUserName')->item(0)->nodeValue;
 $reqFromUserName = $xml->getElementsByTagName('FromUserName')->item(0)->nodeValue;
 $reqCreateTime = $xml->getElementsByTagName('CreateTime')->item(0)->nodeValue;
 $reqMsgType = $xml->getElementsByTagName('MsgType')->item(0)->nodeValue;
 $reqContent = $xml->getElementsByTagName('Content')->item(0)->nodeValue;
 $reqMsgId = $xml->getElementsByTagName('MsgId')->item(0)->nodeValue;
 $reqAgentID = $xml->getElementsByTagName('AgentID')->item(0)->nodeValue;
 
 switch($reqContent){
 case "马云":
 $mycontent="您好，马云！我知道您创建了阿里巴巴！";
 break;
 case "马化腾":
 $mycontent="您好，马化腾！我知道创建了企鹅帝国！";
 break;
 case "史玉柱":
 $mycontent="您好，史玉柱！我知道您创建了巨人网络！";
 break;
 default :
 $mycontent="你是谁啊？！一边凉快去！";
 break;
 }
 
 $sRespData =
 "<xml>
 <ToUserName><![CDATA[".$reqFromUserName."]]></ToUserName>
 <FromUserName><![CDATA[".$corpId."]]></FromUserName>
 <CreateTime>".sReqTimeStamp."</CreateTime>
 <MsgType><![CDATA[text]]></MsgType>
 <Content><![CDATA[".$mycontent."]]></Content>
 </xml>";
 $sEncryptMsg = ""; //xml格式的密文
 $errCode = $wxcpt->EncryptMsg($sRespData, $sReqTimeStamp, $sReqNonce, $sEncryptMsg);
 
 if ($errCode == 0) {
 //file_put_contents('smg_response.txt', $sEncryptMsg); //debug:查看smg
 print($sEncryptMsg);
 } else {
 print('111' . $errCode . "\n\n");
 }
 } else {
 print('222' . $errCode . "\n\n");
 }
 */
?>