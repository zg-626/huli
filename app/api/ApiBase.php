<?php
namespace app\api;

use app\api\model\UserModel;
use app\BaseController;
use app\common\service\JsonService;
use think\App;
use think\facade\Db;
use think\facade\Request;
class ApiBase extends BaseController
{
    
    /* @var array $userInfo 登录信息 */
    protected $oUser;

    /* @var string $route 当前控制器名称 */
    protected $controller = '';

    /* @var string $route 当前方法名称 */
    protected $action = '';

    /* @var string $route 当前路由uri */
    protected $routeUri = '';

    /* @var string $route 当前完整路由uri */
    protected $totalRouteUri = '';

    /* @var string $route 当前路由：分组名称 */
    protected $group = '';

    public $krpano = '';

    /* @var array $allowAllAction 登录验证白名单 */
    protected $allowAllAction = [
        // 登录页面
        'user/login',
        'user/adlogin',
        'token/setRtcToken',
        'token/setRtmToken',
        'live/verticalCdnLive',
        'user/index',
        'user/department',
        'user/register', //註冊頁面
        'user/send_sms', //發送短信
        'user/send_sms_android', //發送短信
        'user/send_sms_all',
        'user/log_out', //退出登录
        'user/h5_out', //退出登录
        'user/myh5info', //退出登录

    ];

    public function __construct(App $app)
    {
        parent::__construct($app);
        // 当前路由信息
        $this->getRouteinfo();
        $this->checkLogin();
        //微信后台信息
        //$this->getweixin();
        

    }

    /*public function getweixin(){
        $weixin = Db::name('Config')->where('type', 'weixin')->find()['value'];
        $weixin = json_decode($weixin,true);
        config($weixin);
        $system = Db::name('Config')->where('type', 'system')->find()['value'];
        $system = json_decode($system,true);
        config($system);
        $storage = Db::name('Config')->where('type', 'storage')->find()['value'];
        $storage = json_decode($storage,true);
        config($storage);
        // $wxpay = Db::name('Config')->where('type', 'wxpay')->find()['value'];
        // $wxpay = json_decode($wxpay,true);
        // $wxpay['wxpay'] = $wxpay;
        // config($wxpay);
    }*/


   /**
     * 验证登录状态
     */
    public function checkLogin()
    {
        // 验证当前请求是否在白名单
        if (in_array($this->routeUri, $this->allowAllAction)) {
            return true;
        }
        $token = $this->request->header('token');

        //$token = input('post.Token');
        if (empty($token)) {
            exit(json_encode(['code' => 201, 'msg' => '缺少token'],JSON_UNESCAPED_UNICODE));die;
        }
        $where['token'] = ['eq',$token];
        $is = Db::name('user_token')->where($where)->find();
        if(empty($is)){
            exit(json_encode(['code' => 201, 'msg' => '请登录'],JSON_UNESCAPED_UNICODE));die;
        }

        $oUser = UserModel::where('id',$is['u_id'])->find()->toArray();

        if(empty($oUser)){
            http_response_code(401);
            exit(json_encode(['code' => 201, 'msg' => '查询不到用户信息'],JSON_UNESCAPED_UNICODE));die;
        }
        $this->oUser = $oUser;
    }

    /**
     * 解析当前路由参数 （分组名称、控制器名称、方法名）
     */
    protected function getRouteinfo()
    {
        // 控制器名称
        $this->controller = toUnderScore($this->request->controller());
        // 方法名称
        $this->action = $this->request->action();
        // 控制器分组 (用于定义所属模块)
        $groupstr = strstr($this->controller, '.', true);
        $this->group = $groupstr !== false ? $groupstr : $this->controller;
        // 当前uri
        $this->routeUri = $this->controller . '/' . $this->action;
        // 当前完整uri
        $this->totalRouteUri = 'api/' . $this->controller . '/' . $this->action;
    }

    /**
     * 返回操作成功json
     * @param array $data
     * @return array
     */
    protected function returnSuccess($data = [] ,$ntid = [])
    {
        return $this->renderJson(1, '成功', $data, $ntid);
	}
	/**
     * 返回封装后的 API 数据到客户端
     * @param int $code
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function renderJson($code = 0, $msg = '', $data = [], $ntid = [])
    {
        return json(compact('code', 'msg', 'data', 'ntid'));
    }
    /**
     * 返回操作失败json
     * @param string $msg
     * @param array $data
     * @return array
     */
    protected function returnError($msg = 'error', $code = 0, $data = [])
    {
        return $this->renderJson($code, $msg, $data);
    }

    /**
     * @notes 操作成功
     * @param string $msg
     * @param array $data
     * @param int $code
     * @param int $show
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/27 14:21
     */
    protected function success(string $msg = 'success', array $data = [], int $code = 1, int $show = 0)
    {
        return JsonService::success($msg, $data, $code, $show);
    }


    /**
     * @notes 数据返回
     * @param $data
     * @return \think\response\Json
     * @author 段誉
     * @date 2021/12/27 14:21\
     */
    protected function data($data)
    {
        return JsonService::data($data);
    }


}