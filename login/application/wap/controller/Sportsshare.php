
<?php
require_once "./../wechatjs/signPackage.php";
$_AppID = "wx022c2930ddabf569"; //广州日报appid
$signPackage = get_wx_sign_package_from_hd($_AppID);
?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="format-detection" content="telephone=no, email=no">
    <title>2018雅加达亚运会</title>
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
    <script>
        (function(doc, win) {
            var docEl = doc.documentElement,
                resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
                recalc = function() {
                    var clientWidth = docEl.clientWidth;
                    if (!clientWidth) return;
                    var _size = 20 * (clientWidth / 375) > 40 ? "40" : 20 * (clientWidth / 375);
                    docEl.style.fontSize = _size + 'px';
                };
            if (!doc.addEventListener) return;
            win.addEventListener(resizeEvt, recalc, false);
            doc.addEventListener('DOMContentLoaded', recalc, false);
        })(document, window);
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        html {
            height: 100%;
            overflow-y: scroll;
            background: #ac3110;
        }

        body {
            height: 100%;
            font-family: PingFangSC-Regular, STXihei, Verdana, Calibri, Helvetica, Arial, sans-serif;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            max-width: 750px;
            margin: 0 auto;
            color: #fff;
        }

        img {
            display: block;
            border: 0 none;
            vertical-align: middle;
        }

        li {
            list-style: none;
        }

        #conn {
            position: relative;
            width: 100%;
            margin: 0 auto;
            max-width: 750px;
            min-height: 100%;
            height: auto;
            background: #F4D6BA;
        }

        #conn .banner {
            position: fixed;
            left: 50%;
            top: 0;
            z-index: 2;
            width: 100%;
            max-width: 750px;
            -moz-transform: translate(-50%, 0);
            -ms-transform: translate(-50%, 0);
            -webkit-transform: translate(-50%, 0);
            transform: translate(-50%, 0);
            height: 8rem;
        }

        #conn .banner .bannerImg {
            width: 100%;
            height: 100%;
        }

        .wrap {
            width: 100%;
            max-width: 750px;
            height: auto;
            min-height: 74%;
            position: absolute;
            top: 8rem;
            left: 0;
            background: #F4D6BA;
            padding-bottom: 2%;
        }

        .searchDiv {
            width: 100%;
            height: 2rem;
        }

        .select {
            background: #fff;
            height: 1.5rem;
            width: auto;
            font-size: 0.65rem;
            line-height: 1.5rem;
            border: 1px solid #f4d6ba;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            min-width: 30%;
        }

        option:hover {
            background-color: #f80;
            color: #fff;
        }

        .country {
            margin-left: 5%;
        }

        .schBtn {
            width: 16%;
            height: 1.5rem;
            line-height: 1.5rem;
            min-width: 60px;
            background: #da451e;
            border-radius: 5px;
            color: #fff;
            font-size: 0.8rem;
            font-weight: bold;
            outline: none;
            border: 1px solid transparent;
            margin-left: 2%;
        }

        .jp {
            width: 90%;
            height: auto;
            margin: 0 auto;
        }

        .jpTop {
            width: 100%;
            height: 2rem;
            background: url(http://h5-1251324935.cosgz.myqcloud.com/yayun/jpTop.png?sign=IcdEkFQuAAp8Y/tZFzZFRvTJ97FhPTEyNTEzMjQ5MzUmaz1BS0lEREJtRUh6YXhHU0RoT2xuUkgzNXc5M1Y5VTBxYjVIbEcmZT0xNTM2ODA5OTkxJnQ9MTUzNDIxNzk5MSZyPTE2OTk0MDI5NzQmZj0veWF5dW4vanBUb3AucG5nJmI9aDU=) 0 0 no-repeat;
            background-size: 100% 100%;
        }

        .jpConn {
            width: 100%;
            min-height: 3rem;
            background: #ec6643;
            border-bottom-right-radius: 10px;
            border-bottom-left-radius: 10px;
            padding-bottom: 2rem;
        }

        table.jpShow {
            width: 100%;
            height: 100%;
            border-spacing: 0;
            font-size: 0.8rem;
            text-align: center;
        }

        .jpShow thead {
            background: #da451e;
        }

        .jpShow thead th {
            padding: 5px 0;
        }

        .jpShow tbody td {
            padding: 5px 0;
        }
        /**/

        .ani1,
        .ani2,
        .ani3 {
            width: 5rem;
            height: 5rem;
            position: absolute;
            z-index: 9999;
        }

        .ani1 {
            background: url(img/ani1.png) 0 0 no-repeat;
            background-size: 100%;
            top: 9.3rem;
            right: 0;
        }

        .ani2 {
            background: url(img/ani2.png) 0 0 no-repeat;
            bottom: 0;
            background-size: 100%;
            left: 0;
        }

        .ani3 {
            background: url(img/ani3.png) 0 0 no-repeat;
            background-size: 100%;
            bottom: 0;
            right: 0;
        }
        /*新闻列表*/

        .newsShow {
            width: 90%;
            margin: 0 auto;
        }
        /*.newsUl {
            padding: 0 0 15px 15px;
        }*/

        .newsLi {
            padding: 12px 10px 8px 0;
        }

        .time {
            font-size: 20px;
            color: #da451e;
            line-height: 42px;
            height: 42px;
            font-weight: bold;
            font-family: "Microsoft Yahei";
        }

        .listUl {
            position: relative;
        }

        .listLi {
            position: relative;
            margin-top: 22px;
            padding-left: 15px;
        }

        .listLi:first-child {
            margin-top: 0;
        }

        .listLi:before {
            content: "";
            position: absolute;
            left: -4px;
            top: 9px;
            width: 7px;
            height: 7px;
            background-color: #fff;
            border-radius: 50%;
            overflow: hidden;
            border: 1px solid #da451e;
        }

        .listUl:after {
            content: "";
            position: absolute;
            left: 0;
            top: 12px;
            bottom: 0;
            width: 1px;
            background-color: rgba(255, 255, 255, 0.6);
        }

        .title {
            position: relative;
            font-family: PingFangSC-Medium;
            font-size: 0.85rem;
            line-height: 24px;
            padding-right: 21px;
            padding-bottom: 5px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            text-overflow: ellipsis;
            overflow: hidden;
            color: #333;
        }

        .desc {
            font-size: 0.8rem;
            color: #666;
            padding-bottom: 5px;
            line-height: 22px;
            margin-top: 6px;
            word-wrap: break-word;
        }

        .articleUl {
            margin-top: 12px;
            background: #ec6643;
            box-shadow: 0 4px 6px 0 #723030;
            font-size: 16px;
            color: rgba(255, 255, 255, 0.85);
            line-height: 24px;
        }

        .articleLi {
            position: relative;
            padding: 11px;
            border-top: 1px solid #b61b1a;
        }

        .articleLi:first-child {
            border-top: none;
        }

        a {
            text-decoration: none;
            width: 100%;
            height: 100%;
            color: #fff;
        }

        .articleLi p {
            height: 48px;
            overflow: hidden;
        }

        .articleLi em {
            position: absolute;
            right: 11px;
            bottom: 14px;
            font-size: 12px;
            line-height: 16px;
            color: #fff;
            padding: 0 5px;
            background: #7f0103;
            border-radius: 2px;
            overflow: hidden;
            font-style: normal;
            font-weight: normal;
        }
    </style>
</head>

<body>
<div class="conn" id="conn">
    <div class="banner">
        <img src="http://h5-1251324935.cosgz.myqcloud.com/yayun/topBg.jpg?sign=dPotzr55joIGM0gF4S6YnKeZ7rhhPTEyNTEzMjQ5MzUmaz1BS0lEREJtRUh6YXhHU0RoT2xuUkgzNXc5M1Y5VTBxYjVIbEcmZT0xNTM2ODA5OTkxJnQ9MTUzNDIxNzk5MSZyPTI4MzkxNzEwNyZmPS95YXl1bi90b3BCZy5qcGcmYj1oNQ=="
             alt="" class="bannerImg">
    </div>
    <div class="wrap" id="wrap">
        <div class="searchDiv">
            <select name="country" id="country" class="country select">
                <option value="">国家/地区</option>
            </select>
            <select name="item" id="item" class="item select">
                <option value="">全部项目</option>
            </select>
            <button id="btn" class="schBtn">搜索</button>
        </div>
        <div class="jp">
            <div class="jpTop"></div>
            <div class="jpConn">
                <table class="jpShow">
                    <thead>
                    <tr>
                        <th class="t1">国家/地区</th>
                        <th class="t2">金牌</th>
                        <th class="t3">银牌</th>
                        <th class="t4">铜牌</th>
                        <th class="t5">总奖牌</th>
                    </tr>
                    </thead>
                    <tbody id="tb">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="newsShow">
            <ul class="newsUl" id="newsUl">
            </ul>
        </div>
    </div>
</div>
<script>
    wx.config({
        debug: false,//调试开关
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: [
            // 所有要调用的 API 都要加到这个列表中
            'onMenuShareTimeline', //分享到朋友圈
            'onMenuShareAppMessage'//分享给朋友
        ]
    });
    wx.ready(function () {
        //config后系统自动调用
        //分享到朋友圈”
        var title = "广州参考带你看亚运会";
        var link = "https://h5.gzdaily.com/yayun/index.php";
        var imgUrl = 'http://h5-1251324935.cosgz.myqcloud.com/yayun/ico.jpg?sign=cOcsav8CAmSfbZqOSWexw2WRdUJhPTEyNTEzMjQ5MzUmaz1BS0lEREJtRUh6YXhHU0RoT2xuUkgzNXc5M1Y5VTBxYjVIbEcmZT0xNTM2ODEzMDg3JnQ9MTUzNDIyMTA4NyZyPTIwMzY5NjYyMTcmZj0veWF5dW4vaWNvLmpwZyZiPWg1';
        wx.onMenuShareTimeline({
            title: title, // 分享标题
            link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: imgUrl, // 分享图标
            success: function () {
                console.log("朋友圈OK");
                // 用户确认分享后执行的回调函数
            }
        });

        //分享给朋友
        wx.onMenuShareAppMessage({
            title: title , // 分享标题
            desc: '2018雅加达亚运会全景报道，尽在广州参考', // 分享描述
            link: link, // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: imgUrl, // 分享图标
            //type: '', // 分享类型,music、video或link，不填默认为link
            //dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function () {
                // 用户确认分享后执行的回调函数
                console.log("朋友OK");
            },
            cancel: function () {
                // 用户取消分享后执行的回调函数
            }
        });
    });
</script>
<script>
    function prizeShow(data) {
        data.forEach(function(prize, index) {
            if (prize["allprize"] !== 0) {
                $("#tb").append("<tr name=" + prize["n_id"] + "><td>" + prize["name"] + "</td><td>" + prize["goldnum"] + "</td><td>" + prize["silvernum"] + "</td><td>" + prize["coppernum"] + "</td><td>" + prize["allprize"] + "</td></tr>");
            }
        })
    }

    function prizeShow2(data) {
        data.forEach(function(prize, index) {
            $("#tb").append("<tr name=" + prize["n_id"] + "><td>" + prize["name"] + "</td><td>" + prize["goldnum"] + "</td><td>" + prize["silvernum"] + "</td><td>" + prize["coppernum"] + "</td><td>" + prize["allprize"] + "</td></tr>");
        })
    }

    function newsShow(data) {
        console.log(data);
        keyArr = Object.keys(data);
        console.log(keyArr);
        for (var i = 0; i < keyArr.length; i++) {
            var key = keyArr[i];
            $("#newsUl").append("<li class='newsLi'><p class='time'>" + keyArr[i] + "</p><ul class='listUl listUl" + i + "'></ul></li>");
            var newsLi = $(".listUl" + i);
            for (var j = 0; j < data[key].length; j++) {
                newsLi.append("<li class='listLi' orderId=" + data[key][j]["id"] + "><p class='title'>" + data[key][j]["title"] + "</p><p class='desc'>" + data[key][j]["sub_title"] + "</p><ul class='articleUl arcticleUl" + key + j + "'></ul></li>");
                var arcUl = $(".arcticleUl" + key + j);
                var linkList = data[key][j]["link_title_list"];
                if (linkList.length !== 0) {
                    for (var k = 0; k < linkList.length; k++) {
                        arcUl.append("<li class='articleLi'><a href=" + linkList[k]["link"] + "><p>" + linkList[k]["title"] + "</p><em>文章</em></a></li>")
                    }
                }
            }
        }
    }
    // 获取数据
    $.ajax({
        url: 'https://h5.gzdaily.com/index.php/gzck/Yayun/initData',
        type: "post",
        async: false,
        success: function(data) {
            var nationArr = data["nation"];
            nationArr.forEach(function(nation, index) {
                $("#country").append("<option value=" + nation["id"] + ">" + nation["name"] + "</option>");
            })
            var itemArr = data["item"];
            itemArr.forEach(function(item, index) {
                $("#item").append("<option value=" + item["id"] + ">" + item["name"] + "</option>");
            })
            var prizeArr = data["prize"];
            prizeShow2(prizeArr);
        }
    });
    // 搜索数据
    $("#btn").click(function() {
        var nid = $('#country option:selected').val();
        var iid = $('#item option:selected').val();
        console.log(nid);
        console.log(iid);
        $.ajax({
            type: 'post',
            url: 'https://h5.gzdaily.com/index.php/gzck/Yayun/listNews',
            data: {
                "nation_id": nid,
                "item_id": iid
            },
            dataType: "json",
            success: function(data) {
                $("#newsUl").empty();
                var prizeData = data["prize"];
                var nationNews = data["nation"];
                var itemNews = data["item"];
                var repeatNews = data["repeat"];

                // 只选国家
                if (nid !== "" && iid == "") {
                    $("#tb").empty();
                    prizeShow2(prizeData);
                    newsShow(nationNews);
                }
                // 只选项目
                else if (nid == "" && iid !== "") {
                    $("#tb").empty();
                    prizeShow(prizeData);
                    newsShow(itemNews);
                }
                // 两者都选
                else if (nid !== "" && iid !== "") {
                    $("#tb").empty();
                    prizeShow2(prizeData);
                    newsShow(repeatNews);
                }
                // 两者都没选
                else {
                    $("#tb").empty();
                    prizeShow2(prizeData);
                }
            }
        })
    });
</script>
<script>
    var _hmt = _hmt || [];
    (function() {
        var hm = document.createElement("script");
        hm.src = "https://hm.baidu.com/hm.js?bb5dc48a8f5680cb47e27365fcc43efb";
        var s = document.getElementsByTagName("script")[0];
        s.parentNode.insertBefore(hm, s);
    })();
</script>
</body>

</html>