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


use app\mxadmin\model\AdminModel;
use app\common\{enum\notice\NoticeEnum,
    enum\user\UserTerminalEnum,
    enum\YesNoEnum,
    logic\BaseLogic,
    model\user\User,
    model\user\UserAuth,
    service\FileService,
    service\sms\SmsDriver,
    service\wechat\WeChatMnpService};
use app\mxadmin\model\UserModel;
use think\facade\Config;

/**
 * 会员逻辑层
 * Class UserLogic
 * @package app\shopapi\logic
 */
class UserLogic extends BaseLogic
{

    /**
     * @notes 个人中心
     * @param array $userInfo
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/16 18:04
     */
    public static function center(array $userInfo): array
    {
        $user = User::where(['id' => $userInfo['user_id']])
            ->field('id,sn,sex,account,nickname,real_name,headimg,phone,create_time,is_new_user,user_money,password')
            ->findOrEmpty();

        if (in_array($userInfo['terminal'], [UserTerminalEnum::WECHAT_MMP, UserTerminalEnum::WECHAT_OA])) {
            $auth = UserAuth::where(['user_id' => $userInfo['user_id'], 'terminal' => $userInfo['terminal']])->find();
            $user['is_auth'] = $auth ? YesNoEnum::YES : YesNoEnum::NO;
        }

        $user['has_password'] = !empty($user['password']);
        $user->hidden(['password']);
        return $user->toArray();
    }


    /**
     * @notes 个人信息
     * @param $userId
     * @return array
     * @author 段誉
     * @date 2022/9/20 19:45
     */
    public static function info(int $userId)
    {
        $user = UserModel::with(['educationalType', 'positionType', 'professionalType'])->where(['id' => $userId])
            ->withoutField(
                'password,login_ip,login_time,create_time,update_time,last_login_ip,last_login_time,login_num,user_agent'
            )
            ->findOrEmpty();
        $user['has_password'] = !empty($user['password']);
        //$user['has_auth'] = self::hasWechatAuth($userId);
        $user['version'] = config('project.version');
        if($user->first_graduate_time==='0000-00-00'){
            $user['first_graduate_time'] = '';
        }
        if($user->highest_graduate_time==='0000-00-00'){
            $user['highest_graduate_time'] = '';
        }
        $user['departmentname'] = getDidName($user['d_id']);
        $user->hidden(['password']);
        return $user->toArray();
    }


    /**
     * @notes 设置用户信息
     * @param int $userId
     * @param array $params
     * @return User|false
     * @author 段誉
     * @date 2022/9/21 16:53
     */
    public static function setInfo(int $userId, array $params)
    {
        try {
            return User::update([
                    'id' => $userId,
                    $params['field'] => $params['value']
                ]
            );
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }


    /**
     * @notes 是否有微信授权信息
     * @param $userId
     * @return bool
     * @author 段誉
     * @date 2022/9/20 19:36
     */
    public static function hasWechatAuth(int $userId)
    {
        //是否有微信授权登录
        $terminal = [UserTerminalEnum::WECHAT_MMP, UserTerminalEnum::WECHAT_OA, UserTerminalEnum::PC];
        $auth = UserAuth::where(['user_id' => $userId])
            ->whereIn('terminal', $terminal)
            ->findOrEmpty();
        return !$auth->isEmpty();
    }


    /**
     * @notes 重置登录密码
     * @param $params
     * @return bool
     * @author 段誉
     * @date 2022/9/16 18:06
     */
    public static function resetPassword(array $params)
    {
        try {
            // 验证图形验证码
            $key = cache('ADMIN_LOGIN_VERIFY_'.$params['uniqid']);
            if($key && password_verify(mb_strtolower($params['code'], 'UTF-8'), $key)){
                cache('ADMIN_LOGIN_VERIFY_'.$params['uniqid'],null);
            }else{
                throw new \Exception('图形验证码错误');
            }
            // 校验验证码
            $smsDriver = new SmsDriver();
            if (!$smsDriver->verify($params['phone'], $params['phone_code'], NoticeEnum::FIND_LOGIN_PASSWORD_CAPTCHA)) {
                throw new \Exception('验证码错误');
            }

            // 重置密码
            //$passwordSalt = Config::get('project.unique_identification');
            $password = md5($params['password']);

            // 更新
            User::where('phone', $params['phone'])->update([
                'password' => $password
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 修稿密码
     * @param $params
     * @param $userId
     * @return bool
     * @author 段誉
     * @date 2022/9/20 19:13
     */
    public static function changePassword(array $params, int $userId)
    {
        try {
            $user = User::findOrEmpty($userId);
            if ($user->isEmpty()) {
                throw new \Exception('用户不存在');
            }

            // 密码盐
            $passwordSalt = Config::get('project.unique_identification');

            if (!empty($user['password'])) {
                if (empty($params['old_password'])) {
                    throw new \Exception('请填写旧密码');
                }
                $oldPassword = md5($params['old_password']);
                if ($oldPassword != $user['password']) {
                    throw new \Exception('原密码不正确');
                }
            }

            // 保存密码
            $password = md5($params['password']);
            $user->password = $password;
            $user->save();

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 获取小程序手机号
     * @param array $params
     * @return bool
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @author 段誉
     * @date 2023/2/27 11:49
     */
    public static function getMobileByMnp(array $params)
    {
        try {
            $response = (new WeChatMnpService())->getUserPhoneNumber($params['code']);
            $phoneNumber = $response['phone_info']['purePhoneNumber'] ?? '';
            if (empty($phoneNumber)) {
                throw new \Exception('获取手机号码失败');
            }

            $user = User::where([
                ['phone', '=', $phoneNumber],
                ['id', '<>', $params['user_id']]
            ])->findOrEmpty();

            if (!$user->isEmpty()) {
                throw new \Exception('手机号已被其他账号绑定');
            }

            // 绑定手机号
            User::update([
                'id' => $params['user_id'],
                'phone' => $phoneNumber
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }


    /**
     * @notes 绑定手机号
     * @param $params
     * @return bool
     * @author 段誉
     * @date 2022/9/21 17:28
     */
    public static function bindMobile(array $params)
    {
        try {
            // 变更手机号场景
            $sceneId = NoticeEnum::CHANGE_MOBILE_CAPTCHA;
            $where = [
                ['id', '=', $params['user_id']],
                ['phone', '=', $params['phone']]
            ];

            // 绑定手机号场景
            if ($params['type'] == 'bind') {
                $sceneId = NoticeEnum::BIND_MOBILE_CAPTCHA;
                $where = [
                    ['phone', '=', $params['phone']]
                ];
            }

            // 校验短信
            $checkSmsCode = (new SmsDriver())->verify($params['phone'], $params['code'], $sceneId);
            if (!$checkSmsCode) {
                throw new \Exception('验证码错误');
            }

            $user = User::where($where)->findOrEmpty();
            if (!$user->isEmpty()) {
                throw new \Exception('该手机号已被使用');
            }

            User::update([
                'id' => $params['user_id'],
                'phone' => $params['phone'],
            ]);

            return true;
        } catch (\Exception $e) {
            self::setError($e->getMessage());
            return false;
        }
    }

    public static function changeInfo(int $userId, $params)
    {
        try {
            $data = [
                'd_id' => $params['d_id'],
                'position_id' => $params['position_id'],
                'professional_id' => $params['professional_id'],
                'headimg' => FileService::setFileUrl($params['headimg']),
                'department' => $params['department'],
                'first_education' => $params['first_education'],
                'first_graduate_school' => $params['first_graduate_school'],
                'first_graduate_time' => $params['first_graduate_time'],
                'highest_education' => $params['highest_education'],
                'educational_id' => $params['educational_id'],
                'highest_graduate_school' => $params['highest_graduate_school'],
                'highest_graduate_time' => $params['highest_graduate_time'],
                'level' => $params['level'],
                'sex' => $params['sex'],
                'id_type' => $params['id_type'],
                'idcard' => $params['idcard'],
                'email' => $params['email'],
                'birthday' => $params['birthday'],
                'work_start_date' => $params['work_start_date'],
                'specialized_nurse' => $params['specialized_nurse'],
                'specialized_name' => $params['specialized_name'],
                'political_status' => $params['political_status'],
                'ethnicity' => $params['ethnicity'],
                'marital_status' => $params['marital_status'],
                'native_place' => $params['native_place'],
                'work_phone' => $params['work_phone'],
                'credit_card_number' => $params['credit_card_number'],
                'employment_status' => $params['employment_status'],
                'address'=> $params['address'],
                'zip_code' => $params['zip_code'],
                'household' => $params['household']
            ];
            // 获取用户手机号
            $user = User::where('id', $userId)->findOrEmpty();
            if (!$user->isEmpty()) {
                $phone = $user['phone'];
                // 更新AdminModel
                AdminModel::where('username', $phone)->update(['d_id' => $params['d_id']]);
            }
            // 计算年龄
            if (!empty($data['birthday'])) {
                $data['age'] = calculateAge($data['birthday']);
            }

            return User::where('id', $userId)->update($data);
        } catch (\Exception $e) {
            self::$error = $e->getMessage();
            return false;
        }
    }

}