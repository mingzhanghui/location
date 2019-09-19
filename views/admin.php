<html>
<head>
    <meta charset="UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $this->get("title"); ?></title>
    <link type="text/css" rel="stylesheet" href="<?php echo $this->getResSrc(); ?>/css/table.css">
    <link type="text/css" rel="stylesheet" href="<?php echo $this->getResSrc(); ?>/css/page.css">
    <style>
        #footer {clear: both; text-align: center;}
    </style>
</head>
<body>
<!--<div id="header">Header</div>-->
<div id="content">
    <div class="panel">
        <p id="alert">点击发送生成短连接, 访问短连接即可获取地理位置。</p>
        手机号: <input id="mobile" type="text" name="mobile" title="输入要发送短信的手机号" />&nbsp;&nbsp;
        <a href="javascript:;" onclick="sendSms(this)" title="生成的短连接发送出去">发送</a>
    </div>
    <div class="tableWrapper">
        <h2 class="text-left">定位数据列表</h2>
        <?php
        $list = $this->get("list");
        ?>
        <table id="gridtable" class="gridtable">
            <thead>
            <tr>
                <th>ID</th><th>手机号</th><th>经度</th><th>维度</th><th>上报日期</th>
                <th>操作</th>
            </tr>
            </thead>
            <tbody>
            <?php
            /** @var $e \models\Snapshot */
            foreach ($list as $e) {
                $link = BASE_URL.'/admin/queryGeoApi?lat='. $e->getLatitude().
                    '&lng='.$e->getLongitude();
                ?>
                <tr>
                    <td><?php echo $e->getId(); ?></td>
                    <td><?php echo $e->getMobile(); ?></td>
                    <td><?php echo $e->getLongitude(); ?></td>
                    <td><?php echo $e->getLatitude(); ?></td>
                    <td><?php echo $e->getCreatedTime(); ?></td>
                    <td><a href="<?php echo $link; ?>" target="_blank">查看定位</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <br />
    <div class="pagination">
        <ul class="page" id="page">
            <!--            <li class="pageItemDisable" data-page="1" page-rel="firstpage">首页</li>-->
            <!--            <li class="pageItemDisable" data-page="0" page-rel="prepage">&lt;上一页</li>-->
            <!--            <li class="pageItemActive" data-page="1" page-rel="itempage">1</li>-->
            <?php
            $lastPage = $this->get("lastPage");
            $uri = BASE_URL."/admin/index";
            for ($i = 1; $i <= $lastPage; $i++) {
                ?>
                <li class="pageItem">
                    <a href="<?php echo $uri.'?p='.$i; ?>"><?php echo $i; ?></a>
                </li>
                <?php
            }
            ?>
            <!--            <li class="pageItem" data-page="2" page-rel="nextpage">下一页&gt;</li>-->
            <!--            <li class="pageItem" data-page="8" page-rel="lastpage">尾页</li>-->
        </ul>
    </div>
</div>
<div id="footer">&nbsp;&nbsp;</div>
<script type="text/javascript" src="<?php echo $this->getResSrc(); ?>/js/jquery.min.js"></script>
<script>
  window.onload = function() {
    // 让分页居中
    var page = document.getElementById("page");
    var count = page.childElementCount;
    // var margin = window.innerWidth - 50 * count;
    var table = document.getElementById("gridtable");
    var margin = table.clientWidth - 50 * count;
    if (margin > 0) {
      var marginLeft = Math.floor(margin / 2);
    }
    page.style.marginLeft = marginLeft + "px";
  };

  function sendSms(target) {
    var api = "<?php echo BASE_URL."/admin/send"; ?>";
    var mobile = $("#mobile").val();
    var pat = /^1[356789][0-9]{9}$/;
    var $alert = $("#alert");
    if (!pat.test(mobile)) {
      $alert.html("手机号错误");
      return false;
    }
    $.get(api, {m:mobile}, function(data) {
      $alert.html(data);
      // $alert.empty().append(
      //   $("<a target='_blank'>").attr("href", data).html(data);
      // )
    });
    $alert.empty();
  }
</script>
</body>