<?php /*a:1:{s:60:"/www/wwwroot/0768.cc/application/home/view/index_detail.html";i:1552575646;}*/ ?>
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
    <link rel="stylesheet" href="/static/home/css/common.css"/>
    <link rel="stylesheet" href="/static/home/css/bootstrap.css"/>
    <!-- ////////////////////////////////// -->
    <!-- //        Favicon Files         // -->
    <!-- ////////////////////////////////// -->
    <link rel="shortcut icon" href="/static/home/images/favicon.ico"/>


    <script src="/static/home/js/jquery.min.js"></script>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

</head>
<body>
<section class="panel b-a base_info" id="Cominfo">
    <div class="container">
        <div class="col-lg-10">
            <table class="ntable">
                <tbody>
                <tr>
                    <td width="20%" class="tb">数量</td>
                    <td width="30%" class="">
                        <?php echo htmlentities($data['num']); ?>
                    </td>
                    <td width="20%" class="tb">金额</td>
                    <td width="30%" class="">
                        <?php echo htmlentities($data['price']); ?>
                    </td>
                </tr>
                <tr>
                    <td class="tb">公司名称</td>
                    <td class="">
                        <?php echo htmlentities($data['company_name']); ?>
                    </td>
                    <td class="tb" width="18%">纳税人识别号</td>
                    <td class="">
                        <?php echo htmlentities($data['crad_no']); ?>
                    </td>
                </tr>
                <tr>
                    <td class="tb">法定代表人信息</td>
                    <td class="">
                        <?php echo htmlentities($data['fa_info']); ?>
                    </td>
                    <td class="tb">注册资本</td>
                    <td class="">
                        <?php echo htmlentities($data['has_price']); ?>
                    </td>
                </tr>
                <tr>
                    <td class="tb">成立日期</td>
                    <td class="">
                        <?php echo htmlentities($data['ctime']); ?>
                    </td>
                    <td class="tb" width="15%">核准日志</td>
                    <td class="">
                        <?php echo htmlentities($data['htime']); ?>
                    </td>
                </tr>
                <tr>
                    <td class="tb">所属地区</td>
                    <td class="">
                        <?php echo htmlentities($data['area']); ?>
                    </td>
                    <td class="tb">登记机关</td>
                    <td class="">
                        <?php echo htmlentities($data['djjg']); ?>
                    </td>
                </tr>
                <tr>
                    <td class="tb">企业地址</td>
                    <td class="" colspan="3">
                        <?php echo htmlentities($data['company_area']); ?>
                    </td>
                </tr>
                <tr>
                    <td class="tb">经营范围</td>
                    <td class="" colspan="3">
                        <?php echo htmlentities($data['do']); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

</body>
<!-- header end here -->