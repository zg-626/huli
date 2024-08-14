<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\api\logic;

use app\cms\model\Fees;
use app\common\cache\WebScanLoginCache;
use app\common\logic\BaseLogic;
use app\mxadmin\model\AdminModel;
use app\mxadmin\model\AuthGroupAccess;
use app\mxadmin\model\DictData;
use app\mxadmin\model\UserModel;
use app\api\service\{UserTokenService, WechatUserService};
use app\common\enum\{LoginEnum, user\UserTerminalEnum, YesNoEnum};
use app\common\service\{
    ConfigService,
    FileService,
    wechat\WeChatConfigService,
    wechat\WeChatMnpService,
    wechat\WeChatOaService,
    wechat\WeChatRequestService
};
use app\common\model\user\{User, UserAuth};
use think\facade\{Db, Config};

/**
 * 登录逻辑
 * Class LoginLogic
 * @package app\api\logic
 */
class LoginLogic extends BaseLogic
{

    /**
     * @notes 账号密码注册
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/9/7 15:37
     */
    public static function register(array $params)
    {
        try {
            //$userSn = User::createUserSn();
            //$passwordSalt = Config::get('project.unique_identification');
            $password = md5($params['password']);
            $headimg = '';

            $user = User::create([
                //'sn' => $userSn,
                'headimg' => $headimg,
                'nickname' => $params['nickname'],
                'phone' => $params['phone'],
                'password' => $password,
                'd_id' => $params['d_id'],
            ]);
            // 增加缴费记录
            self::addFees($user->id);
            // 同步管理员
            self::synAdmin($user->id);
            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    //增加缴费记录
    public static function addFees($id)
    {
        //获取分类
        $categories = DictData::where('id',23)->select();
        //获取年份
        $years = DictData::where('dict_id', 12)->select();
        // 示例的嵌套循环创建记录
        foreach ($years as $year) {
            foreach ($categories as $category) {
                Fees::create([
                    'dict_id' => $category->id,
                    'dict_data_id' => $year->id,
                    'user_id' => $id, // 替换为你实际的用户ID
                    'status' => 0,
                    'fees_year' => $year->name,
                    'fees_type' => $category->name,
                ]);
            }
        }
    }

    // 同步管理员
    public static function synAdmin($id)
    {
        $user = UserModel::where('id', $id)->find();
        $create = [
            'password' => $user['password'],
            'nickname' => $user['nickname'],
            'username' => $user['phone'],
        ];
        $admin_info = AdminModel::create($create);
        // 新增用户所属角色
        $role_id = explode(',', '2');
        foreach ($role_id as $value) {
            $dataset[] = ['uid' => $admin_info->id, 'group_id' => $value];
        }
        AuthGroupAccess::insertAll($dataset);
    }


    /**
     * @notes 账号/手机号登录，手机号验证码
     * @param $params
     * @return array|false
     * @author 段誉
     * @date 2022/9/6 19:26
     */
    public static function login($params)
    {
        try {
            // 验证图形验证码
            $key = cache('ADMIN_LOGIN_VERIFY_'.$params['uniqid']);
            if($key && password_verify(mb_strtolower($params['code'], 'UTF-8'), $key)){
                cache('ADMIN_LOGIN_VERIFY_'.$params['uniqid'],null);
            }else{
                throw new \Exception('图形验证码错误');
            }
            // 账号/手机号 密码登录
            $where = ['phone' => $params['phone']];
            /*if ($params['scene'] == LoginEnum::MOBILE_CAPTCHA) {
                //手机验证码登录
                $where = ['phone' => $params['phone']];
            }*/

            $user = User::where($where)->findOrEmpty();
            if ($user->isEmpty()) {
                throw new \Exception('用户不存在');
            }

            // 如果未审核
            if ($user->status != YesNoEnum::YES) {
                throw new \Exception('用户未审核或已被禁用');
            }

            //更新登录信息
            $user->login_time = time();
            $user->login_ip = request()->ip();
            $user->save();

            //设置token
            $userInfo = UserTokenService::setToken($user->id, 1);

            //返回登录信息
            $headimg = $user->headimg ?: '';
            $headimg = FileService::getFileUrl($headimg);

            return [
                'nickname' => $userInfo['nickname'],
                //'sn' => $userInfo['sn'],
                'phone' => $userInfo['phone'],
                'headimg' => $headimg,
                'token' => $userInfo['token'],
            ];
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 退出登录
     * @param $userInfo
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 17:56
     */
    public static function logout($userInfo)
    {
        //token不存在，不注销
        if (!isset($userInfo['token'])) {
            return false;
        }

        //设置token过期
        return UserTokenService::expireToken($userInfo['token']);
    }


    /**
     * @notes 获取微信请求code的链接
     * @param string $url
     * @return string
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function codeUrl(string $url)
    {
        return (new WeChatOaService())->getCodeUrl($url);
    }


    /**
     * @notes 公众号登录
     * @param array $params
     * @return array|false
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function oaLogin(array $params)
    {
        Db::startTrans();
        try {
            //通过code获取微信 openid
            $response = (new WeChatOaService())->getOaResByCode($params['code']);
            $userServer = new WechatUserService($response, UserTerminalEnum::WECHAT_OA);
            $userInfo = $userServer->getResopnseByUserInfo()->authUserLogin()->getUserInfo();

            // 更新登录信息
            self::updateLoginInfo($userInfo['id']);

            Db::commit();
            return $userInfo;

        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 小程序-静默登录
     * @param array $params
     * @return array|false
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function silentLogin(array $params)
    {
        try {
            //通过code获取微信 openid
            $response = (new WeChatMnpService())->getMnpResByCode($params['code']);
            $userServer = new WechatUserService($response, UserTerminalEnum::WECHAT_MMP);
            $userInfo = $userServer->getResopnseByUserInfo('silent')->getUserInfo();

            if (!empty($userInfo)) {
                // 更新登录信息
                self::updateLoginInfo($userInfo['id']);
            }

            return $userInfo;
        } catch (\Exception  $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 小程序-授权登录
     * @param array $params
     * @return array|false
     * @author 段誉
     * @date 2022/9/20 19:47
     */
    public static function mnpLogin(array $params)
    {
        Db::startTrans();
        try {
            //通过code获取微信 openid
            $response = (new WeChatMnpService())->getMnpResByCode($params['code']);
            $userServer = new WechatUserService($response, UserTerminalEnum::WECHAT_MMP);
            $userInfo = $userServer->getResopnseByUserInfo()->authUserLogin()->getUserInfo();

            // 更新登录信息
            self::updateLoginInfo($userInfo['id']);

            Db::commit();
            return $userInfo;
        } catch (\Exception  $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 更新登录信息
     * @param $userId
     * @throws \Exception
     * @author 段誉
     * @date 2022/9/20 19:46
     */
    public static function updateLoginInfo($userId)
    {
        $user = User::findOrEmpty($userId);
        if ($user->isEmpty()) {
            throw new \Exception('用户不存在');
        }

        $time = time();
        $user->login_time = $time;
        $user->login_ip = request()->ip();
        $user->update_time = $time;
        $user->save();
    }


    /**
     * @notes 小程序端绑定微信
     * @param array $params
     * @return bool
     * @author 段誉
     * @date 2022/9/20 19:46
     */
    public static function mnpAuthLogin(array $params)
    {
        try {
            //通过code获取微信openid
            $response = (new WeChatMnpService())->getMnpResByCode($params['code']);
            $response['user_id'] = $params['user_id'];
            $response['terminal'] = UserTerminalEnum::WECHAT_MMP;

            return self::createAuth($response);

        } catch (\Exception  $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 公众号端绑定微信
     * @param array $params
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author 段誉
     * @date 2022/9/16 10:43
     */
    public static function oaAuthLogin(array $params)
    {
        try {
            //通过code获取微信openid
            $response = (new WeChatOaService())->getOaResByCode($params['code']);
            $response['user_id'] = $params['user_id'];
            $response['terminal'] = UserTerminalEnum::WECHAT_OA;

            return self::createAuth($response);

        } catch (\Exception  $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 生成授权记录
     * @param $response
     * @return bool
     * @throws \Exception
     * @author 段誉
     * @date 2022/9/16 10:43
     */
    public static function createAuth($response)
    {
        //先检查openid是否有记录
        $isAuth = UserAuth::where('openid', '=', $response['openid'])->findOrEmpty();
        if (!$isAuth->isEmpty()) {
            throw new \Exception('该微信已被绑定');
        }

        if (isset($response['unionid']) && !empty($response['unionid'])) {
            //在用unionid找记录，防止生成两个账号，同个unionid的问题
            $userAuth = UserAuth::where(['unionid' => $response['unionid']])
                ->findOrEmpty();
            if (!$userAuth->isEmpty() && $userAuth->user_id != $response['user_id']) {
                throw new \Exception('该微信已被绑定');
            }
        }

        //如果没有授权，直接生成一条微信授权记录
        UserAuth::create([
            'user_id' => $response['user_id'],
            'openid' => $response['openid'],
            'unionid' => $response['unionid'] ?? '',
            'terminal' => $response['terminal'],
        ]);
        return true;
    }


    /**
     * @notes 获取扫码登录地址
     * @return array|false
     * @author 段誉
     * @date 2022/10/20 18:23
     */
    public static function getScanCode($redirectUri)
    {
        try {
            $config = WeChatConfigService::getOpConfig();
            $appId = $config['app_id'];
            $redirectUri = UrlEncode($redirectUri);

            // 设置有效时间标记状态, 超时扫码不可登录
            $state = MD5(time().rand(10000, 99999));
            (new WebScanLoginCache())->setScanLoginState($state);

            // 扫码地址
            $url = WeChatRequestService::getScanCodeUrl($appId, $redirectUri, $state);
            return ['url' => $url];

        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 网站扫码登录
     * @param $params
     * @return array|false
     * @author 段誉
     * @date 2022/10/21 10:28
     */
    public static function scanLogin($params)
    {
        Db::startTrans();
        try {
            // 通过code 获取 access_token,openid,unionid等信息
            $userAuth = WeChatRequestService::getUserAuthByCode($params['code']);

            if (empty($userAuth['openid']) || empty($userAuth['access_token'])) {
                throw new \Exception('获取用户授权信息失败');
            }

            // 获取微信用户信息
            $response = WeChatRequestService::getUserInfoByAuth($userAuth['access_token'], $userAuth['openid']);

            // 生成用户或更新用户信息
            $userServer = new WechatUserService($response, UserTerminalEnum::PC);
            $userInfo = $userServer->getResopnseByUserInfo()->authUserLogin()->getUserInfo();

            // 更新登录信息
            self::updateLoginInfo($userInfo['id']);

            Db::commit();
            return $userInfo;

        } catch (\Exception $e) {
            Db::rollback();
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 更新用户信息
     * @param $params
     * @param $userId
     * @return User
     * @author 段誉
     * @date 2023/2/22 11:19
     */
    public static function updateUser($params, $userId)
    {
        return User::where(['id' => $userId])->update([
            'nickname' => $params['nickname'],
            'headimg' => FileService::setFileUrl($params['headimg']),
            'is_new_user' => YesNoEnum::NO
        ]);
    }
}