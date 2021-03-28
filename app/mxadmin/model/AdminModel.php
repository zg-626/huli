<?php
// +----------------------------------------------------------------------
// | mxAdmin
// +----------------------------------------------------------------------
// | 版权所有 2020~2050 福州目雪科技有限公司 [ http://www.muxue.com.cn ]
// +----------------------------------------------------------------------
// | 演示地址: http://demo.muxue.com.cn
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/muxue2020/mxAdmin
// +----------------------------------------------------------------------
// | Author: 明仔 <350656405@qq.com>    微信号：zlmlovem
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\mxadmin\model;

use think\Model;

class AdminModel extends Model
{
    // 模型名
    protected $name = 'admin';

    // 字段设置类型自动转换
    protected $type = [
        'login_time'  =>  'timestamp',
        'last_login_time'  =>  'timestamp',
    ];

    /**
     * @param $value
     * @return string
     */
    public function setPasswordAttr($value)
    {
        return md5($value);
    }

    /**
     * @return \think\model\relation\HasMany
     */
    public function roles()
    {
        return $this->hasMany(AuthGroupAccess::class,'uid','id')->with('group')->order('group_id');
    }
}