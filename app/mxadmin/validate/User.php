<?php

declare (strict_types = 1);

namespace app\mxadmin\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     * @var array
     */	
	protected $rule = [
        'phone' => 'require|length:11|mobile|unique:user',
        'nickname' => 'require|length:2,20|chsAlphaNum',
        //'workname' => 'require',
        'd_id' => 'require',
        'newpassword' => 'require|length:6,20|alphaNum',
        'repassword' => 'require|confirm:newpassword',
        //'userEditRoleSel' => 'require',
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'phone.require' => '登录账号不能为空！',
        'phone.length' => '登录账号必须11个字符！',
        'phone.mobile' => '登录账号只能是手机号！',
        'phone.unique' => '登录账号已经存在，请使用其它账号！',
        'nickname.require' => '账号昵称不能为空！',
        'nickname.length' => '账号昵称必须2到20个字符！',
        'nickname.chsAlphaNum' => '账号昵称只能是汉字、字母和数字！',
        //'workname.require' => '工作队不能为空！',
        'd_id.require' => '所属机构不能为空！',
        'newpassword.require' => '新密码不能为空！',
        'newpassword.length' => '新密码必须6到20个字符！',
        'newpassword.alphaNum' => '新密码只能是字母和数字！',
        'repassword.require' => '确认密码不能为空！',
        'repassword.confirm' => '新密码和确认密码不一致！',
        //'userEditRoleSel.require' => '请选择所属角色！',
    ];

    /**
     * edit 验证编辑场景定义
     * @return Admin
     */
    public function sceneEdit()
    {
        return $this->only(['nickname','phone','workname','d_id'])
            ->remove('newpassword', 'require')
            ->remove('repassword', 'require');
    }
    
    /**
     * edit 验证编辑场景定义
     * @return Admin
     */
    public function sceneShenhe()
    {
        return $this->only(['nickname','phone','workname','d_id'])
            ->remove('newpassword', 'require')
            ->remove('repassword', 'require');
    }

    /**
     * 修改密码场景定义
     * @var array
     */
    protected $scene = [
        'editpassword'  =>  ['newpassword','repassword'],
    ];
}
