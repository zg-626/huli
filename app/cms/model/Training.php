<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\mxadmin\model\AdminModel;
use app\mxadmin\model\DictData;
use think\Model;

class Training extends Model
{
    /**
     * 获取发布者信息
     * @return \think\model\relation\HasOne
     */
    public function admin()
    {
        return $this->hasOne(AdminModel::class, 'id', 'admin_id')->bind(['username']);
    }

    public function typename()
    {
        return $this->hasOne(DictData::class, 'id', 'type');
    }

    // 定义学习班和报名记录的关联关系
    public function signups()
    {
        return $this->hasMany(TrainingSign::class, 'training_id', 'id');
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

    public function getstudyTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }

    public function setstudyTimeAttr($value)
    {
        return strtotime($value);
    }

    public function getdeadlineTimeAttr($value)
    {
        return date('Y-m-d', $value);
    }

    public function setdeadlineTimeAttr($value)
    {
        return strtotime($value);
    }
}