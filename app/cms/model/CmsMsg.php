<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\mxadmin\model\AdminModel;
use app\mxadmin\model\DictData;
use think\Model;

class CmsMsg extends Model
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
    public function type()
    {
        return $this->hasOne(DictData::class, 'id', 'category_id')->bind(['type_name' => 'name']);
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

    // 关联阅读表
    public function read()
    {
        return $this->hasMany(MsgRead::class, 'm_id', 'id');
    }
}