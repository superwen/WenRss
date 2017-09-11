<!DOCTYPE HTML>
<html>
<head>
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>胜闻阅读</title>
    <meta content="胜闻阅读,阅读器,IT订阅" name="keyword"/>
    <link rel="shortcut icon" href="./images/icon.ico" type="image/x-icon"/>
    <link href="./css/style.css" type="text/css" rel="stylesheet">
    <script src="./js/jquery-2.1.1.min.js" type="text/javascript"></script>
    <script language="javascript">
        var infoTimer,scrollTimer;
        var nowtime = "{{ $nowtime}}";
        var page = 0;
        var pagesize = 20;
        var isloading = false;
        var tag = "{{ $tag }}";

        /*
         * ready
         */
        $(document).ready(function() {
            $('.nav').hide();
            $('#show').click(function (){
                if($('.nav').css("display") == "none")
                    $('.nav').show();
                else
                    $('.nav').hide();
            });
            $(document).scrollTop(0);
            loadEntry();
            //setInterval("checkUpdates()",60000);
        });

        /*
         * 滚屏
         */
        $(window).scroll(function(){
            dh = parseInt($(document).height());
            st = parseInt($(document).scrollTop());
            wh = parseInt($(window).height());
            if(scrollTimer) {
                clearTimeout(scrollTimer);
            }
            if((dh-st) < (wh+2)) {
                scrollTimer = setTimeout("loadEntry();",600);
            }
        });

        /*
         * 加载Entry
         */
        function loadEntry() {
            if(page*pagesize > 400) {
                showInfo("休息一下吧...");
                return false;
            }
            if(!isloading){
                isloading = true;
                showInfo("加载数据中...");
                $.get("/rssentries",{"do": "loadEntry", "tag": tag, "page": (page+1), "pagesize": pagesize, "nowtime": nowtime},function(msg){
                    if(msg.length>0){
                        showInfo("加载完成。")
                        page = page + 1;
                        isloading = false;
                        $("#zblog_main").append(msg);
                        setTimeout("removeItemHeight();",1000);
                    }else{
                        showInfo("没有加载到内容！");
                    }
                });
            }
        }

        /*
         * 检查更新
         */
        function checkUpdates() {
            $.get("ajax.php",{"do" : "checkUpdates", "nowtime": nowtime},function(msg){
                var updates = parseInt(msg);
                if(updates > 0){
                    var html = "(" + updates + ")";
                    $("#all_unread").html(html);

                }else{
                    $("#all_unread").html("");
                }
            });
        }

        /*
         * 显示状态
         */
        function showInfo(msg) {
            if(infoTimer) {
                clearTimeout(infoTimer);
            }
            $("#ajaxinfo").html(msg);
            $("#ajaxinfo").show();
            infoTimer = setTimeout("hideInfo();",2000);
        }

        /*
         * 隐藏状态
         */
        function hideInfo() {
            $("#ajaxinfo").hide();
        }

        /*
         * 显示下拉菜单
         */
        function dropDown(e) {
            $(e).next().show();
        }

        /*
         * 标记所有为已读
         */
        function makeAllRead() {
            $.get("ajax.php",{"do" : "makeAllRead", "nowtime": nowtime},function(msg){
                if(msg == "OK"){
                    $(".zblog_item_title").each(function(){
                        $(this).addClass("zblog_item_title_visited");
                    });
                }else{
                }
            });
        }

        /*
         * 查看Entry
         */
        function readEntry(e,entryId) {
            $.get("ajax.php",{"do" : "makeOneRead", "entryId": entryId},function(msg){
                if(msg == "OK"){
                    $("#itemTitle_"+entryId).addClass("zblog_item_title_visited");
                }else{
                }
            });
            return false;
        }

        /*
         * 移除高亮
         */
        function removeItemHeight(){
            $(".zblog_item_height").each(function(){
                $(this).removeClass("zblog_item_height");
            });
        }
    </script>
</head>
<body>
<div id="wrap">
    <div class="header">
        <div class="logo">
            <a href="index.html"><img src="images/logo.png" alt="Playlist Mobi"/></a>
        </div>
        <div class="button" id="show">
            <a>Menu</a>
        </div>
        <div class="clear-float"></div>
        <div class="nav">
            <ul>
                <li><a href="/">全部</a></li>
                <li><a href="/?tag=tech">技术</a></li>
                <li><a href="/?tag=shop">生活</a></li>
                <li>
                    <ul>
                        <li><a href="login.php">Log in</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="content">
        <div class="post">
            <h2>{{ $tagTitle }}</h2>
            <div id="zblog_main" class="zblog_item_list"></div>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2014 <a href="http://shengwen.sinaapp.com/">shengwen.sinaapp.com</a></p>
    </div>
    <div class="by">Design by <a href="http://www.mobifreaks.com">Mobifreaks.com</a></div>
    <div id="ajaxinfo" class="ajaxinfo">ajaxinfo</div>
</div>
</body>
</html>