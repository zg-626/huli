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

use app\mxadmin\model\AdminModel;
use think\Model;

class CmsArticle extends Model
{
    /**
     * 获取发布者信息
     * @return \think\model\relation\HasOne
     */
    public function admin()
    {
        return $this->hasOne(AdminModel::class, 'id', 'admin_id')->bind(['username']);
    }

    /**
     * 获取分类名称
     * @return \think\model\relation\HasOne
     */
    public function category()
    {
        return $this->hasOne(CmsCategory::class, 'id', 'category_id')->bind(['name']);
    }

    //多图保存
    public function setPhotosAttr($value)
    {
        if(!$value){
            $list = array();
        }else{
            $list = implode(',',array_filter($value));
        }
        return $list;
    }

    //多图获取
    public function getPhotosAttr($value)
    {
        if(!$value){
            $list = array();
        }else{
            $list = explode(',', $value);
        }
        return $list;
    }
}