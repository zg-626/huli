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
namespace app\api\validate;


use app\common\model\user\User;
use app\common\validate\BaseValidate;

/**
 * 注册验证器
 * Class RegisterValidate
 * @package app\api\validate
 */
class RegisterValidate extends BaseValidate
{

    protected $regex = [
        'register' => '^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$',
        'password' => '/^(?![0-9]+$)(?![a-z]+$)(?![A-Z]+$)(?!([^(0-9a-zA-Z)]|[\(\)])+$)([^(0-9a-zA-Z)]|[\(\)]|[a-z]|[A-Z]|[0-9]){6,20}$/'
    ];

    protected $rule = [
        'phone' => 'require|length:11|unique:' . User::class,
        'd_id' => 'require|number',
        'nickname' => 'require',
        'password' => 'require|length:8,20|regex:password',
        'password_confirm' => 'require|confirm'
    ];

    protected $message = [
        'phone.require' => '请输入手机号',
        'phone.length' => '手机号须为11位',
        'phone.unique' => '手机号已存在，请联系管理员',
        'd_id.require' => '请选择医院',
        'nickname.require' => '请输入姓名',
        'password.require' => '请输入密码',
        'password.length' => '密码须在8-20位之间',
        'password.regex' => '密码须为数字,字母或符号组合',
        'password_confirm.require' => '请确认密码',
        'password_confirm.confirm' => '两次输入的密码不一致'
    ];

}