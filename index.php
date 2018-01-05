<?php

header('Content-type:text');
define("TOKEN", "weixin");

$wechatObj = new wechatCallbackapiTest();
if (isset($_GET['echostr'])) {
    $wechatObj->valid();
}else{
    $wechatObj->responseMsg();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            header('content-type:text');
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    
     public function responseMsg(){  
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];  
        if (!empty($postStr)){  
            libxml_disable_entity_loader(true);  
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);  
            $fromUsername = $postObj->FromUserName;  
            $toUsername = $postObj->ToUserName;  
            $keyword = trim($postObj->Content);  
            $event = $postObj->Event;              
            $time = time();  
            $textTpl = "<xml>  
                      	<ToUserName><![CDATA[%s]]></ToUserName>  
                        <FromUserName><![CDATA[%s]]></FromUserName>  
                        <CreateTime>%s</CreateTime>  
                        <MsgType><![CDATA[%s]]></MsgType>  
                        <Content><![CDATA[%s]]></Content>  
                        <FuncFlag>0</FuncFlag>  
                        </xml>";                                
            switch($postObj->MsgType){  
                case 'event':  
                    if($event == 'subscribe'){  
                        $contentStr = "感谢您的关注，NCG敢死队 助您进步！";  
                        $msgType = 'text';  
                        $textTpl = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  
                        echo $textTpl;  
                    }  
                    break;  
                case 'text':                         
                    if($keyword=="图文"){  
                        $tuwenTpl = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[news]]></MsgType>
                                    <ArticleCount>3</ArticleCount>
                                    <Articles>
                                        <item>
                                            <Title><![CDATA[成龙]]></Title>
                                            <Description><![CDATA[社会我龙哥,人狠话不多]]></Description>
                                			<PicUrl><![CDATA[http://b92.photo.store.qq.com/psb?/V10e9euW0yikCC/OlZkVkSzV1H3hszP3OHJJIF5GTrOkXo61lhRp7om3J0!/b/YZXE4DYnGQAAYspU4jZBGQAA&bo=oQDhAAAAAAABA2Q!&rf=viewer_4]]></PicUrl>
                                            <Url><![CDATA[http://www.baidu.com]]></Url>
                                        </item>
                                        <item>
                                            <Title><![CDATA[渣滓强]]></Title>
                                            <Description><![CDATA[社会败类 - 渣滓强]]></Description>
                                            <PicUrl><![CDATA[http://m.qpic.cn/psb?/V10e9euW0yikCC/px0zvBy1zBau4hOMK6jGgF1WlmPqh2Fer18BZ5NUkvc!/b/dPMAAAAAAAAA&bo=DwEcAQAAAAARFzM!&rf=viewer_4]]></PicUrl>
                                            <Url><![CDATA[http://www.baidu.com]]></Url>
                                        </item>
                                        <item>
                                            <Title><![CDATA[渣滓伟]]></Title>
                                            <Description><![CDATA[社会败类 - 渣滓伟]]></Description>
                                    		<PicUrl><![CDATA[http://m.qpic.cn/psb?/V10e9euW0yikCC/H3IyHIjLPE.GWtiUsgFx0XaE2Urc4oWrHFiLZXUNUkM!/b/dF4BAAAAAAAA&bo=SAEAAgAAAAADJ0k!&rf=viewer_4]]></PicUrl>
                                            <Url><![CDATA[http://www.baidu.com]]></Url>
                                        </item>
                                    </Articles>
                                    <FuncFlag>0</FuncFlag>
                                </xml>"; 
                        $resultStr= sprintf($tuwenTpl, $fromUsername, $toUsername, $time) ;                                    
                        echo $resultStr;                                  
                    }
                    else if ('音乐' == $keyword)
                    {
                        $newsTpl = "<xml>  
                            <ToUserName><![CDATA[%s]]></ToUserName>  
                            <FromUserName><![CDATA[%s]]></FromUserName>  
                            <CreateTime>%s</CreateTime>  
                            <MsgType><![CDATA[%s]]></MsgType>  
                            <Music>
                                <Title><![CDATA[一剪梅(陈百强)]]></Title>
                                <Description><![CDATA[这不是袁华同学吗？]]></Description>
                                <MusicUrl><![CDATA[http://96.f.1ting.com/5a4ee8b3/2d70300dfaeadd872da99adec820bf65/zzzzzmp3/2008JJune/11/11_t_zhang/10.mp3]]></MusicUrl>
                                <HQMusicUrl><![CDATA[http://96.f.1ting.com/5a4ee8b3/2d70300dfaeadd872da99adec820bf65/zzzzzmp3/2008JJune/11/11_t_zhang/10.mp3]]></HQMusicUrl>
                            </Music>
                            </xml>";
                        $msgType = 'music';
                        $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType);  

                        echo $resultStr;
                    }
                    else if ('获取信息' == $keyword)
                    {
                        // snsapi_base
                        // snsapi_userinfo
                        $contentStr = '<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx185abaa737dc9fb8&redirect_uri=http://1.n0noper.applinzi.com/test01.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect">点击这里</a>';
                        $msgType = 'text';  
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  
                        echo $resultStr;
                    }
                    $contentStr = "您发送的是text消息: " . $postObj->Content . "\n可回复【图文】、【音乐】试试";  
                    //如果用户输入的信息，都没有匹配成功，就返回这一句  
                    $msgType = 'text';  
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  
                    echo $resultStr;  
                    break;  
                    
                case 'image':	// 图片消息
                    $newsTpl = "<xml>  
                            <ToUserName><![CDATA[%s]]></ToUserName>  
                            <FromUserName><![CDATA[%s]]></FromUserName>  
                            <CreateTime>%s</CreateTime>  
                            <MsgType><![CDATA[%s]]></MsgType>  
                            <Image>  
                            <MediaId><![CDATA[%s]]></MediaId>  
                            </Image>  
                            <FuncFlag>0</FuncFlag>  
                            </xml>"; 
                    $msgType = 'image';       
                    //$contentStr = "czrbMIZ1sTDJoThMpZjGxl9PR4zxdME3flusXde3shZm8aswkRg40jhzdBwlvNbP";
                    $contentStr = $postObj->MediaId;
                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  

                    echo $resultStr;      
                    
                    break;
                    
                case 'voice':	// 语音消息
                    $newsTpl = "<xml>  
                            <ToUserName><![CDATA[%s]]></ToUserName>  
                            <FromUserName><![CDATA[%s]]></FromUserName>  
                            <CreateTime>%s</CreateTime>  
                            <MsgType><![CDATA[%s]]></MsgType>  
                            <Voice>  
                            <MediaId><![CDATA[%s]]></MediaId>  
                            </Voice> 
                            </xml>";
                    $msgType = 'voice';
                    //$contentStr = "czrbMIZ1sTDJoThMpZjGxl9PR4zxdME3flusXde3shZm8aswkRg40jhzdBwlvNbP";
                    $contentStr = $postObj->MediaId;
                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  

                    echo $resultStr; 
                    break;
                    
                case 'video': 	// 视频消息                   
                    $newsTpl = "<xml>  
                            <ToUserName><![CDATA[%s]]></ToUserName>  
                            <FromUserName><![CDATA[%s]]></FromUserName>  
                            <CreateTime>%s</CreateTime>  
                            <MsgType><![CDATA[%s]]></MsgType>  
                            <Video>  
                            <MediaId><![CDATA[%s]]></MediaId>
                            <Title><![CDATA[title]]></Title>
                            <Description><![CDATA[description]]></Description>
                            </Video> 
                            </xml>";
                    $msgType = 'video';
                    $contentStr = $postObj->MediaId;
                    $title = "这里是标题";
                    $description = "这里是描述信息";
                    $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  

                    echo $resultStr;                     
                    
                    break;
                    
                case 'location':	// 位置消息
                	$contentStr = "您的坐标:\n纬度: " . $postObj->Location_X . 
                        "\n经度: " . $postObj->Location_Y . 
                        "\n地图缩放大小: " . $postObj->Scale . 
                        "\n地理位置信息: " . $postObj->Label;
                    $msgType = 'text';  
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  
                    echo $resultStr; 
                        
                    break;
                    
                case 'link':
              		$contentStr = "您发送的链接内容如下:\n标题: " . $postObj->Title . 
                        "\n图文消息描述: " . $postObj->Description . 
                        "\n点击跳转地址: " . $postObj->Url;
                    $msgType = 'text';  
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);  
                    echo $resultStr;
              
              		break;
                    
                default:  
                    break;  
            }                         
        }else {  
            echo "你好！欢迎进入 NCG敢死队 微信公众号";  
            exit;  
        }  
    }  
}
?>
