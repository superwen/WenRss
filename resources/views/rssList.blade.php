<!DOCTYPE html>
<!--[if lte IE 6 ]>
<html class="ie ie6 lte-ie7 lte-ie8" lang="zh-CN"> <![endif]-->
<!--[if IE 7 ]>
<html class="ie ie7 lte-ie7 lte-ie8" lang="zh-CN"> <![endif]-->
<!--[if IE 8 ]>
<html class="ie ie8 lte-ie8" lang="zh-CN"> <![endif]-->
<!--[if IE 9 ]>
<html class="ie ie9" lang="zh-CN"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="zh-CN">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Slim - a bootstrap theme made by overtrue.</title>
    <meta name="keywords" content="overtrue,bootstrap, bootstrap theme"/>
    <meta name="description" content="a bootstrap theme made by overtrue."/>
    <link rel="stylesheet" href="./dist/css/slim-min.css" media="screen">
    <link href="//cdn.bootcss.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="//cdn.bootcss.com/html5shiv/r29/html5.min.js"></script>
    <script src="//cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        /*body {background: #f8f8f8;}*/
        a, a:hover {
            text-decoration: none;
        }

        .container {
            max-width: 820px;
        }

        .page-header {
            padding-bottom: 10px;
            margin: 44px 0 22px;
            border-bottom: 1px solid #eee
        }

        .nav-item {
            padding: 34px 10px 0 10px;
        }

        .bs-component {
            padding: 10px 0
        }

        .tab-content {
            padding: 15px 0
        }

        .card {
            max-width: 400px
        }

        .buttons-section .btn {
            margin-bottom: 10px;
        }

        ul#icons {
            margin: 10px auto;
            padding-left: 5px;
            width: 100%;
            list-style: none;
            text-align: left;
            font-size: 1px
        }

        ul#icons li {
            position: relative;
            z-index: 0;
            display: inline-block;
            padding: 22px;
            width: 42px;
            border-radius: 4px;
            list-style: none;
            text-align: center;
            font-weight: normal;
            font-size: 32px;
            cursor: pointer
        }

        ul#icons li:hover {
            color: #4f8ef7
        }

        #icon-panel {
            position: absolute;
            top: -9999px;
            left: -9999px;
            padding: 10px 2px;
            width: 260px;
            height: 58px;
            border-radius: 4px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
            text-align: center;
            font-size: 14px;
            font-family: Monaco, Menlo, Consolas, "Courier New", monospace;
            opacity: .96
        }

        #icon-name {
            display: block;
            font-family: Monaco, Menlo, Consolas, "Courier New", monospace;
            font-size: 14px;
            text-align: center;
            width: 100%;
            border: 0
        }

        #icons {
            transition: opacity .2s ease-in-out
        }

        footer ul li {
            float: left;
            margin-right: 20px
        }

        #banner {
            border-top: 1px solid #eee;
            padding: 40px 0 20px;
            color: #efefef;
            background: ghostwhite url(http://7b1fpn.com2.z0.glb.clouddn.com/stardust.png)
        }

        .example-modal .modal {
            position: relative;
            top: auto;
            right: auto;
            bottom: auto;
            left: auto;
            z-index: 1;
            display: block
        }

        .padding-20 {
            padding: 20px
        }
        .card {
            max-width: 100%;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="bs-docs-section clearfix">
        <div class="bs-component">
            <nav class="navbar navbar-dark bg-inverse">
                <a class="navbar-brand" href="#"><h2>伯文阅读</h2></a>
                <ul class="nav navbar-nav">
                    @if ($tag == null)
                    <li class="nav-item active">
                        @else
                    <li class="nav-item">
                        @endif
                        <a class="nav-link" href="/">全部</a>
                    </li>
                    @if ($tag == 'tech')
                    <li class="nav-item active">
                        @else
                    <li class="nav-item">
                        @endif
                        <a class="nav-link" href="/?tag=tech">技术</a>
                    </li>
                    @if ($tag == 'shop')
                    <li class="nav-item active">
                        @else
                    <li class="nav-item">
                        @endif
                        <a class="nav-link" href="/?tag=shop">生活</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    <div class="bs-docs-section">
        <h4>{{$tagTitle}}</h4>
        <div class="bs-component">
            @foreach($entries as $index => $entrie)
            <div class="card card-block">
                <h5 class="card-title"><a href="{{$entrie->link}}" target="_blank">{{$entrie->title}}</a></h5>
                <a class="card-link" href="#"><span class="ion-university"></span>&nbsp;{{$entrie->rss->title}}</a>
                <a class="card-link" href="#">{{date("m-d H:i", $entrie->published)}}</a>
            </div>
            @endforeach
            <nav>
                {{ $entries->appends(['tag' => $tag])->links() }}
            </nav>
        </div>
    </div>


    <div id="source-modal" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Source Code</h4>
                </div>
                <div class="modal-body">
                    <pre></pre>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <footer>
        <ul class="list-unstyled clearfix">
            <li class="pull-right"><a href="#top">Back to top</a></li>
            <li><a href="http://overtrue.me" onclick="pageTracker._link(this.href); return false;"
                   target="_blank">Blog</a></li>
            <li><a href="http://weibo.com/joychaocc" target="_blank">微博</a></li>
            <li><a href="https://github.com/overtrue/bootstrap-theme/" target="_blank">GitHub</a></li>
        </ul>
        <p>Made by <a href="https://github.com/overtrue" rel="nofollow">overtrue</a>. </p>
        <div>Code released under the MIT License.</div>
    </footer>
</div>


<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/tether/1.2.0/js/tether.min.js"></script>
<script src="./dist/js/bootstrap.min.js"></script>
<script>
    $(function () {
        $("body").tooltip({
            selector: '[data-toggle="tooltip"]',
            container: "body"
        }),
            $("body").popover({
                selector: '[data-toggle="popover"]',
                container: "body"
            }),

            $('ul#icons li').on('click', function () {
                var iconPanel = $('#icon-panel');

                iconPanel.find('#icon-name').val($(this).attr('class'));
                iconPanel.css({'left': $(this).offset().left - 90, top: $(this).offset().top - 30}).show();
            });
    });
</script>
</body>
</html>