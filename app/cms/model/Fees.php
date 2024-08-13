<?php

declare (strict_types = 1);

namespace app\cms\model;

use app\cms\controller\Msg;
use app\common\model\BaseModel;
use app\common\service\FileService;
use app\mxadmin\model\DictData;
use app\mxadmin\model\UserModel;
use think\Model;

class Fees extends BaseModel
{
    public function user()
    {
        return $this->hasOne(UserModel::class, 'id', 'user_id')->bind(['nickname' => 'nickname']);
    }

    //关联发票
    // 定义和 invoice 表的关联关系
    public function invoice()
    {
        // belongsTo 表示 Fees 模型属于 Invoice 模型的一条数据
        // 第一个参数是关联的模型名，第二个参数是关联条件
        return $this->hasMany(Invoices::class, 'fees_id', 'id');
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
        if($value){
            return trim($value) ? FileService::getFileUrl('/'.$value) : '';
        }
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
}