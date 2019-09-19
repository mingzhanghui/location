<p>Home page</p>
<script type="text/javascript" src="js/UrlParam.js"></script>

<p>当前的IP地址: <?php echo $this->get("ip"); ?></p>

<p id="demo"></p>

<script>

    function isMobile(mobile) {
        var pat = /^1[356789][0-9]{9}$/;
        return pat.test(mobile);
    }

    function params(o) {
        return Object.keys(o).map(function(prop) {
            return encodeURIComponent(prop) + "=" + encodeURIComponent(o[prop]);
        }).join('&');
    }

    function dataReport(longitude, latitude) {
        if (! UrlParam.hasParam("m") ) {
            alert("m参数不存在");
            return false;
        }
        var mobile = UrlParam.param("m");
        if (!isMobile(mobile)) {
            alert("手机号格式错误");
            return false;
        }
        // http://47.93.27.106:8042/Index/reportLocation
        // var url = window.location.protocol + "//" + window.location.host + "/index.php/Index/reportLocation";
        var url = "<?php echo BASE_URL; ?>/Index/reportLocation";
        console.log(url);
        var image = new Image();
        var o = {
            mobile: mobile,
            longitude: longitude,
            latitude: latitude
        };
        image.src = url + "?" + params(o);
        document.body.appendChild(image);
    }

    (function() {
        if (!navigator.geolocation) {
            alert("Geolocation is not supported by this browser.");
            return false;
        }

        // 数据上报 \controller\IndexController::reportLocation
        // debug 自己设定数据 暂不可支持https, 用http IP地址只能用127.0.0.1测试
        // var longitude = 116.29633319999999;
        // var latitude = 40.0534199;
        // dataReport(longitude, latitude);

        var demo = document.getElementById("demo");
        var posHandler = function(position) {
            demo.innerHTML= "Longitude(经度): " + position.coords.longitude + "<br />" +
                "Latitude(纬度): " + position.coords.latitude;

            dataReport(position.coords.longitude, position.coords.latitude);
       };
       navigator.geolocation.getCurrentPosition(posHandler);

        return true;
    })();
</script>
