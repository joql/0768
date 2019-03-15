<?php /*a:1:{s:59:"/www/wwwroot/0768.cc/application/home/view/index_index.html";i:1552618712;}*/ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang="zh-cn"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9" lang="zh-cn"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="zh-cn"> <!--<![endif]-->
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width"/>
    <title><?php echo isset($info['title']) ? htmlentities($info['title']) : ($title ? $title : $sys['title']); ?></title>
    <meta name="keywords" content="<?php echo isset($info['keywords']) ? htmlentities($info['keywords']) : ($keywords ? $keywords : $sys['key']); ?>"/>
    <meta name="description" content="<?php echo isset($info['description']) ? htmlentities($info['description']) : ($description ? $description : $sys['des']); ?>"/>
    <!-- ////////////////////////////////// -->
    <!-- //      Stylesheets Files       // -->
    <!-- ////////////////////////////////// -->
    <link rel="stylesheet" href="/static/home/css/app.css"/>
    <link rel="stylesheet" href="/static/home/css/bootstrap.css"/>
    <!-- ////////////////////////////////// -->
    <!-- //        Favicon Files         // -->
    <!-- ////////////////////////////////// -->
    <link rel="shortcut icon" href="/static/home/images/favicon.ico"/>


    <script src="/static/home/js/jquery.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

</head>
<body>
<section id="search">
    <div class="intro">
        <div class="container" style="width: 100%;max-width: 1250px;">
            <div class="col-md-12">
                <h1 id="V3_index_search_h1" class="text-center heading m-b-lg text-white">
                查查查！
                </h1>
                <div class="col-md-10 col-sm-10">
                    <div class="search-nav ">
                        <ul class="V3_index_search_item">
                            <li class="hidden-xs"> </li>
                            <li class="hidden-xs"> </li>
                            <li class="hidden-xs"> </li>
                            <li class="hidden-xs"> </li>
                            <li class="order-type" onclick="getlist(1)">数量正序</li>
                            <li class="hidden-xs"> </li>
                            <li class="order-type" onclick="getlist(2)">数量倒序</li>
                            <li class="hidden-xs"> </li>
                            <li class="order-type" onclick="getlist(3)">价格正序</li>
                            <li class="hidden-xs"> </li>
                            <li class="order-type" onclick="getlist(4)">价格倒序</li>
                            <li class="hidden-xs"> </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="col-md-10 col-sm-10 col-md-offset-1">
                    <form action="?" method="get" style="padding-right: 0px;">
                        <div class="input-group">
                            <input type="text" value="<?php echo htmlentities(app('request')->get('key')); ?>" id="searchkey" autocomplete="off" placeholder="请输入企业名称,省份,经营范围" name="key" class="form-control input-lg">
                            <span class="input-group-btn">
                                <input hidden id="order_type" name="order_type" value="4">
                                <input id="V3_Search_bt" type="submit" class="btn-lg" value="查一下" >
                                <input id="reset_bt" type="button" class="btn-lg" value="重置" style="
    /* background: #24c2a6; */
    background: #3c763d;
    font-size: 20px;
    color: white;
    padding: 5px 25px 5px;
    border: none;
    border-radius: 0px;
">
                            </span>
                        </div>
                    </form>
                    <section class="panel hidden-md" id="search-list" style="display: none;"></section>
                </div>
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1" style="width: 850px;">
                            <p class="index-hot">
                                <span id="hot_data">
                                    <?php if(is_array($province) || $province instanceof \think\Collection || $province instanceof \think\Paginator): $i = 0; $__LIST__ = $province;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <a onclick="getlistbyprovince(this)" class="index-hot-company"><?php echo htmlentities($vo['name']); ?></a>
                                    <?php endforeach; endif; else: echo "" ;endif; ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
<div class="col-md-12" id="ajaxlist">
    <section class="panel b-a n-s" id="searchlist" style="border-top: none;margin-bottom: 0px;">
        <table class="m_srchList">
            <thead>
            <tr>
                <td>
                    <span class="nstatus .text-primary m-l-xs">排序</span>
                </td>
                <td>
                    <span class="nstatus .text-primary m-l-xs">公司名称</span>
                </td>
                <td width="100">
                    <span class="nstatus .text-primary m-l-xs">数量</span>
                </td>
                <td width="100">
                    <span class="nstatus .text-primary m-l-xs">价格</span>
                </td>
            </tr>
            </thead>
            <tbody id="search-result">
            <?php if(!empty($company)): if(is_array($company) || $company instanceof \think\Collection || $company instanceof \think\Paginator): $i = 0; $__LIST__ = $company;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr>
                <td width="100">
                    <span class="nstatus text-success-lt m-l-xs"><?php echo htmlentities($vo['inc']); ?></span>
                </td>
                <td>
                    <a href="/home/index/detail?id=<?php echo htmlentities($vo['id']); ?>" target="_blank" class="ma_h1"><?php echo htmlentities($vo['name']); ?></a>
                    <p class="m-t-xs">
                        <span class="m-l"><?php echo htmlentities($vo['remark']); ?></span>
                    </p>
                </td>
                <td width="100">
                    <span class="nstatus text-success-lt m-l-xs"><?php echo htmlentities($vo['num']); ?></span>
                </td>
                <td width="100">
                    <span class="nstatus text-success-lt m-l-xs"><?php echo htmlentities($vo['price']); ?></span>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; endif; ?>
            </tbody>
        </table>
    </section>
</div>

</body>
<script>
    function getlist(type) {
        document.querySelector('#order_type').value = type;
        document.querySelector('#V3_Search_bt').click()
    }

    function getlistbyprovince(e) {
        document.querySelector('#searchkey').value = e.text;
        document.querySelector('#V3_Search_bt').click()
    }
    document.querySelector('#reset_bt').addEventListener('click', function () {
        location.href = '/';
    })
</script>
<!-- header end here -->