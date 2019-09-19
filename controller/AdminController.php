<?php
/**
 * Created by PhpStorm.
 * User: mingzhanghui
 * Date: 9/12/2019
 * Time: 16:32
 */

namespace controller;

use lib\Logger;
use lib\Request;
use lib\Str;
use models\Snapshot;
use service\Curl;
use service\MapService;
use service\SmsProvider;

class AdminController extends Controller
{
    /**
     * 地理位置记录列表
     */
    public function index(Request $request) {
        $page = $request->get('p', 1);
        $size = $request->get('sz', 20);

        $count = Snapshot::count();
        if ($page < 1) {
            $page = 1;
        }
        $lastPage = ceil($count / $size);
        if ($page > $lastPage) {
            $page = $lastPage;
        }
        $list = Snapshot::all(($page-1)*$size, $size);

        $this->view->assign("title", "地理位置列表");
        $this->view->assign("list", $list);
        $this->view->assign("lastPage", $lastPage);
        $this->view->render("admin", false);
    }
    /**
     * 需要发送给别人的长链接 => 短链接
     * @param Request $request
     * @return int
     */
    public function short(Request $request) {
        $mobile = $request->get('m');

        if (!Str::isMobile($mobile)) {
            // throw new \InvalidArgumentException();
            $err = sprintf("手机号[%s]格式错误\n", $mobile);
            $code = 22;
            $this->fail($err, $code);
            return $code;
        }

        // URL 配置查看项目根目录的config.php
        // http://47.93.27.106:8042/?m=18771099612
        $url = BASE_URL.'?m='.$mobile;
        Logger::write(sprintf("Generating short URL for: [%s]\n", $url));

        $this->success("success", Curl::shortURL($url));
        return 0;
    }

    /**
     * http://127.0.0.1:8042/index.php/admin/send?m=13830140540
     * @param Request $request
     * @return int
     */
    public function send(Request $request) {
        $mobile = $request->get("m");
        if (!Str::isMobile($mobile)) {
            // throw new \InvalidArgumentException();
            $err = sprintf("手机号[%s]格式错误\n", $mobile);
            $code = 22;
            $this->fail($err, $code);
            return $code;
        }
        // 发送短信诱导点击 获取位置 需要https服务
//        $content = $request->get("c");
//        $sms = new SmsProvider(SMS_USER_ID, SMS_PASSWORD, SMS_API);
//        echo $sms->send($mobile, $content);

        // 暂时没有https 在本地 http://127.0.0.1 可以取得定位信息 用来测试
        $url = BASE_URL.'?m='.$mobile;
        Logger::write(sprintf("Generating short URL for: [%s]\n", $url));

        echo Curl::shortURL($url);
    }

    /**
     * http://47.93.27.106:8042/index.php/admin/queryGeoApi?lat=40.0534199&lng=116.29633319999999
     * @param Request $request
     * @return string jQuery1102005223567051282596_1568444517839&&jQuery1102005223567051282596_1568444517839({"status":200,"msg":"ok","count":1,"result":[{"lat":[40.0534199000, 40.0546764382, 40.0607575365, 40.0546893300, 40.0535599000, 40.0546893300],"lng":[116.2963332000, 116.3024110856, 116.3088872258, 116.3024285700, 116.2914832000, 116.3024285700],"address":"北京市海淀区清河街道智学苑西北方向","rids":"156110108017034","rid":"110108"}],"match":"1"})
     */
    public function queryGeoApi(Request $request) {
        $lat = $request->get('lat');
        $lng = $request->get('lng');
        header("Content-Type: application/json; charset=UTF-8");
        Logger::write( sprintf("lat=%s, lng=%s", $lat, $lng));

        echo MapService::gaode($lat, $lng);
    }
}