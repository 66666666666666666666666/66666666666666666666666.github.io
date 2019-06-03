<?php
function sysmsg($msg = '未知的异常', $die = true)
{
    ?>
    <!DOCTYPE html>
    <html xmlns="http://www.w3.org/1999/xhtml" lang="zh-CN">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>站点提示信息</title>
        <style type="text/css">
            html {
                background: #eee
            }

            body {
                background: #fff;
                color: #333;
                font-family: "微软雅黑", "Microsoft YaHei", sans-serif;
                margin: 2em auto;
                padding: 1em 2em;
                max-width: 700px;
                -webkit-box-shadow: 10px 10px 10px rgba(0, 0, 0, .13);
                box-shadow: 10px 10px 10px rgba(0, 0, 0, .13);
                opacity: .8
            }

            h1 {
                border-bottom: 1px solid #dadada;
                clear: both;
                color: #666;
                font: 24px "微软雅黑", "Microsoft YaHei",, sans-serif;
                margin: 30px 0 0 0;
                padding: 0;
                padding-bottom: 7px
            }

            #error-page {
                margin-top: 50px
            }

            h3 {
                text-align: center
            }

            #error-page p {
                font-size: 9px;
                line-height: 1.5;
                margin: 25px 0 20px
            }

            #error-page code {
                font-family: Consolas, Monaco, monospace
            }

            ul li {
                margin-bottom: 10px;
                font-size: 9px
            }

            a {
                color: #21759B;
                text-decoration: none;
                margin-top: -10px
            }

            a:hover {
                color: #D54E21
            }

            .button {
                background: #f7f7f7;
                border: 1px solid #ccc;
                color: #555;
                display: inline-block;
                text-decoration: none;
                font-size: 9px;
                line-height: 26px;
                height: 28px;
                margin: 0;
                padding: 0 10px 1px;
                cursor: pointer;
                -webkit-border-radius: 3px;
                -webkit-appearance: none;
                border-radius: 3px;
                white-space: nowrap;
                -webkit-box-sizing: border-box;
                -moz-box-sizing: border-box;
                box-sizing: border-box;
                -webkit-box-shadow: inset 0 1px 0 #fff, 0 1px 0 rgba(0, 0, 0, .08);
                box-shadow: inset 0 1px 0 #fff, 0 1px 0 rgba(0, 0, 0, .08);
                vertical-align: top
            }

            .button.button-large {
                height: 29px;
                line-height: 28px;
                padding: 0 12px
            }

            .button:focus, .button:hover {
                background: #fafafa;
                border-color: #999;
                color: #222
            }

            .button:focus {
                -webkit-box-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
                box-shadow: 1px 1px 1px rgba(0, 0, 0, .2)
            }

            .button:active {
                background: #eee;
                border-color: #999;
                color: #333;
                -webkit-box-shadow: inset 0 2px 5px -3px rgba(0, 0, 0, .5);
                box-shadow: inset 0 2px 5px -3px rgba(0, 0, 0, .5)
            }

            table {
                table-layout: auto;
                border: 1px solid #333;
                empty-cells: show;
                border-collapse: collapse
            }

            th {
                padding: 4px;
                border: 1px solid #333;
                overflow: hidden;
                color: #333;
                background: #eee
            }

            td {
                padding: 4px;
                border: 1px solid #333;
                overflow: hidden;
                color: #333
            }
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

function get_curl($url, $post = 0, $referer = 0, $cookie = 0, $header = 0, $ua = 0, $nobaody = 0, $split = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    $httpheader[] = "Accept:*/*";
    $httpheader[] = "Accept-Encoding:gzip,deflate,sdch";
    $httpheader[] = "Accept-Language:zh-CN,zh;q=0.8";
    $httpheader[] = "Connection:close";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    }
    if ($header) {
        curl_setopt($ch, CURLOPT_HEADER, TRUE);
    }
    if ($cookie) {
        curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    }
    if ($referer) {
        curl_setopt($ch, CURLOPT_REFERER, $referer);
    }
    if ($ua) {
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    } else {
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.152 Safari/537.36');
    }
    if ($nobaody) {
        curl_setopt($ch, CURLOPT_NOBODY, 1);
    }
    curl_setopt($ch, CURLOPT_ENCODING, "gzip");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $ret = curl_exec($ch);
    if ($split) {
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($ret, 0, $headerSize);
        $body = substr($ret, $headerSize);
        $ret = array();
        $ret['header'] = $header;
        $ret['body'] = $body;
    }
    curl_close($ch);
    return $ret;
}

$uin = $_GET['uin'];
$skey = $_GET['skey'];
if (!$uin || !$skey) sysmsg('参数不完整！');
if (strlen($uin) < 5 || strlen($uin) > 12) sysmsg('uin参数格式不正确！');
$urlPre = 'http://r.qzone.qq.com/fcg-bin/cgi_get_portrait.fcg?g_tk=1518561325&uins=';
$data = file_get_contents($urlPre . $uin);
$data = iconv("GB2312", "UTF-8", $data);
$pattern = '/portraitCallBack\\((.*)\\)/is';
preg_match($pattern, $data, $result);
$result = $result[1];
$result = json_decode($result, true);
$name = $result["{$uin}"][6];
$ret = get_curl("http://mc.vip.qq.com/card/grow", 0, 0, "uin=o{$uin}; skey={$skey};");
if (strlen($ret) <= 0) sysmsg('SKEY有误！');
if (strlen($ret) > 0) {
    $start = strpos($ret, "window.growInfo = ");
    $end = strpos($ret, "window.num");
    if ($start > 0 && $end > $start) {
        $ret = substr($ret, $start, $end - $start);
        $ret = str_replace(['window.growInfo =', '};'], ['', '}'], $ret);
        $ret = trim($ret);
        if ($ret = json_decode($ret, true)) {
            if ($ret['iVip'] == 1) {
                $vip = "是";
            } else {
                $vip = "否";
            }
            if ($ret['iSVip'] == 1) {
                $svip = "是";
            } else {
                $svip = "否";
            }
            if ($ret['iYearVip'] == 1) {
                $yearvip = "是";
            } else {
                $yearvip = "否";
            }
            $vipjs = round($ret['iVipSpeedRate'] * 1 / 10, 1);
            if ($ret['QzoneVisitor'] == 1) {
                $qzone = "已加速";
            } else {
                $qzone = "未加速";
            }
            if ($ret['WeishiVideoview'] == 1) {
                $weishi = "已加速";
            } else {
                $weishi = "未加速";
            }
            if ($ret['iMobileQQOnline'] == 1) {
                $mobileqq = "已加速";
            } else {
                $mobileqq = "未加速";
            }
            if ($ret['iPCQQOnline'] == 1) {
                $pcqq = "已加速";
            } else {
                $pcqq = "未加速";
            }
            if ($ret['iPCSafeOnline'] == 1) {
                $pcsafe = "已加速";
            } else {
                $pcsafe = "未加速";
            }
            if ($ret['iMobileGameOnline'] == 1) {
                $game = "已加速";
            } else {
                $game = "未加速";
            }
            if ($ret['iUseQQMusic'] == 1) {
                $music = "已加速";
            } else {
                $music = "未加速";
            }
            if ($ret['iMedal'] == 1) {
                $medal = "已加速";
            } else {
                $medal = "未加速";
            }
            if ($ret['iNoHideOnline'] == 1) {
                $nohide = "已加速";
            } else {
                $nohide = "未加速";
            }
            if ($ret['QstoryScan'] == 1) {
                $wsten = "已加速";
            } else {
                $wsten = "未加速";
            }
            if ($ret['iQQSportStep'] == 1) {
                $sport = "已加速";
            } else {
                $sport = "未加速";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <title><?php echo $uin; ?>-资料详情</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
    <link rel="stylesheet" href="https://5sir.cn/tool/jiasu/css/bootswatch.css">
<body background="https://ae01.alicdn.com/kf/HTB1ur.wbfWG3KVjSZPcq6zkbXXao.jpg">
<div class="container" style="padding-top:20px;">
    <div class="col-xs-12 col-sm-10 col-lg-8 center-block" style="float: none;">
        <div class="panel panel-primary">
            <div class="panel-heading" style="background: linear-gradient(to right,#5ccdde,#b221ff);">
                <center><font color="#000000"><b><?php echo $uin; ?>-资料详情</b></font></center>
            </div>
            <div class="panel-body">
                <center>
                    <div class="alert alert-success"><a
                                href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $uin; ?>&site=qq&menu=yes"><img
                                    class="img-circle m-b-xs"
                                    style="border: 2px solid #1281FF; margin-left:3px; margin-right:3px;"
                                    src="https://q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $uin; ?>&spec=100" ;
                                    width="60px" height="60px" alt="<?php echo $uin; ?>"><br></a><?php echo $name; ?>
                    </div>
                    <table class="table table-bordered table-striped">
                        <tbody>
                        <tr>
                            <td>QQ账号</td>
                            <td><?php echo $uin; ?></td>
                        </tr>
                        <tr>
                            <td>QQ昵称</td>
                            <td><?php echo $name; ?></td>
                        </tr>
                        <tr>
                            <td>SKEY</td>
                            <td><?php echo $skey; ?></td>
                        </tr>
                        <tr>
                            <td>QQ等级</td>
                            <td><?php echo $ret['iQQLevel']; ?>级</td>
                        </tr>
                        <tr>
                            <td>总活跃天数</td>
                            <td><?php echo $ret['iTotalActiveDay']; ?>天</td>
                        </tr>
                        <tr>
                            <td>日最高活跃</td>
                            <td><?php echo $ret['iMaxLvlRealDays']; ?>天</td>
                        </tr>
                        <tr>
                            <td>QQ今日成长</td>
                            <td><?php echo $ret['iRealDays']; ?>天</td>
                        </tr>
                        <tr>
                            <td>距离下次升级</td>
                            <td><?php echo $ret['iNextLevelDay']; ?>天</td>
                        </tr>
                        <tr>
                            <td>是否VIP</td>
                            <td><?php echo $vip; ?></td>
                        </tr>
                        <tr>
                            <td>是否SVIP</td>
                            <td><?php echo $svip; ?></td>
                        </tr>
                        <tr>
                            <td>是否年费VIP</td>
                            <td><?php echo $yearvip; ?></td>
                        </tr>
                        <tr>
                            <td>VIP等级</td>
                            <td><?php echo $ret['iVipLevel']; ?>级</td>
                        </tr>
                        <tr>
                            <td>VIP加速</td>
                            <td><?php echo $vipjs; ?>倍</td>
                        </tr>
                        <tr>
                            <td>手机QQ在线</td>
                            <td><?php echo $mobileqq; ?></td>
                        </tr>
                        <tr>
                            <td>电脑QQ在线</td>
                            <td><?php echo $pcqq; ?></td>
                        </tr>
                        <tr>
                            <td>非隐身在线</td>
                            <td><?php echo $nohide; ?></td>
                        </tr>
                        <tr>
                            <td>QQ音乐加速</td>
                            <td><?php echo $music; ?></td>
                        </tr>
                        <tr>
                            <td>电脑管家加速</td>
                            <td><?php echo $pcsafe; ?></td>
                        </tr>
                        <tr>
                            <td>勋章墙加速</td>
                            <td><?php echo $medal; ?></td>
                        </tr>
                        <tr>
                            <td>手Q游戏加速</td>
                            <td><?php echo $game; ?></td>
                        </tr>
                        <tr>
                            <td>QQ运动加速</td>
                            <td><?php echo $sport; ?></td>
                        </tr>
                        <tr>
                            <td>微视观看加速</td>
                            <td><?php echo $weishi; ?></td>
                        </tr>
                        <tr>
                            <td>微视访客加速</td>
                            <td><?php echo $wsten; ?></td>
                        </tr>
                        <tr>
                            <td>空间访客加速</td>
                            <td><?php echo $qzone; ?></td>
                        </tr>
                        </tbody>
                    </table>
                    Copyright © <?php echo date("Y") ?> 梁Sir 版权所有</a>
                </center>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>
