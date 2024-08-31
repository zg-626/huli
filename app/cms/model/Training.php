<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\common\service\FileService;
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

    public function class()
    {
        return $this->hasOne(DictData::class, 'id', 'type')->bind(['classname'=>'name']);
    }

    // 定义学习班和报名记录的关联关系
    public function signups()
    {
        return $this->hasMany(TrainingSign::class, 'training_id', 'id');
    }

    //关联试卷
    public function paper()
    {
        return $this->belongsTo(Paper::class, 'paper_id', 'id');
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

    /**
     * @notes 公共处理图片,补全路径
     * @param $value
     * @return string
     * @author 张无忌
     * @date 2021/9/10 11:02
     */
    public function getImageAttr($value)
    {
        return trim($value) ? FileService::getFileUrl($value) : '';
    }

    /**
     * @notes 公共图片处理,去除图片域名
     * @param $value
     * @return mixed|string
     * @author 张无忌
     * @date 2021/9/10 11:04
     */
    public function setImageAttr($value)
    {
        return trim($value) ? FileService::setFileUrl($value) : '';
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
        return date('Y-m-d H:i:s', $value);
    }

    public function setdeadlineTimeAttr($value)
    {
        return strtotime($value);
    }
}