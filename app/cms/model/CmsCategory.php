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

namespace app\cms\model;

use think\Model;

class CmsCategory extends Model
{
    /**
     * 获取文章模型分类数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCategoryData()
    {
        $list = self::where('status',1)->order(['weight','id'])->select()->toArray();
        return $list;
    }

    /**
     * 自动填写栏目链接地址
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function setUrl($id)
    {
        $urlType = self::where('id', $id)->value('urltype');
        if ($urlType == 0) {
            self::where('id', $id)->update(['url' => '/list/'.$id.'.html']);
        } else if ($urlType == 1) {
            self::where('id', $id)->update(['url' => '/category/'.$id.'.html']);
        } else if ($urlType == 2) {
            self::where('id', $id)->update(['url' => '/show/'.$id.'.html']);
        }
    }

    /**
     * 获取顶级栏目ID
     * @param $id
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function setTopid($id)
    {
        if (isset($id)) {
            $row = self::where('id', $id)->field('id,pid')->find();
            while ($row['pid'] > 0) {
                $row = self::where('id', $row['pid'])->field('id,pid')->find();
            }
            self::where('id', $id)->update(['topid' => $row['id']]);
        }
    }
}