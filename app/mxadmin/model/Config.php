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

class Config extends Model
{
    // 设置json类型字段
    protected $json = ['value'];

    // 设置JSON数据返回数组
    protected $jsonAssoc = true;

    /**
     * 设置配置数据
     * @param $type
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function setConfigData($type, $data) {
        $result = self::where('type', $type)->find();
        if ($result == true) {
            self::where('type', $type)->update(['value' => $data]);
        } else {
            self::create(['type' => $type, 'value' => $data]);
        }
    }

    /**
     * 获取配置数据
     * @param $type
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getConfigData($type) {
        $result = self::where('type', $type)->find();
        if ($result == true) {
            $data = self::where('type', $type)->find()['value'];
            return $data;
        } else {
            self::create(['type' => $type]);
        }
    }
}