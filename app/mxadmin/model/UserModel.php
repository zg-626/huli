<?php

declare (strict_types = 1);

namespace app\mxadmin\model;

use app\cms\model\MsgRead;
use app\cms\model\TrainingSign;
use app\common\service\FileService;
use think\Model;
use app\cms\model\CmsCategory;

class UserModel extends Model
{
    public $appId = 'wxcdb11ea60ed1d688';
    public $appSecret = '76134647047f674973cafec310743b8f';

    /**
     * 获取医院
     * @return \think\model\relation\HasOne
     */
    public function hospital()
    {
        return $this->hasOne(CmsCategory::class, 'id', 'd_id')->bind(['departmentname'=>'name']);
    }

    /**
     * @return 获取token
     */
    public function AccessToken()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $this->appId . "&secret=" . $this->appSecret;
        
        $json = https_request($url);
        $array = json_decode($json, true);
        
        return $array;
    }

    /**
     * @return 获取token
     */
    public function sendMessage($accessToken,$data)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=". $accessToken;
        
        $json = https_request($url,$data,'POST');
        $array = json_decode($json, true);
        
        return $array;
    }

     /**
     * 将模板消息json格式化
     */
     public function json_tempalte($openid,$data,$template_id){
     //模板消息
     $time=date( "Y-m-d H:i:s", time());
     $url ="";
     $template=array(
     'touser'=>"$openid", //用户openid
     'template_id'=>"$template_id", //在公众号下配置的模板id
     'url'=>"$url", //点击模板消息会跳转的链接
     'topcolor'=>"#7B68EE",
     'data'=>array(
     'first'=>array('value'=>urlencode("你好，你的申请审核结果如下"),'color'=>""),
     'keyword1'=>array('value'=>urlencode($data['msg']),'color'=>'#173177'), //keyword需要与配置的模板消息对应
     'keyword2'=>array('value'=>urlencode($time),'color'=>'#173177'),
     'keyword3'=>array('value'=>urlencode("如果有疑问，请联系客服"),'color'=>''),
     //'keyword4'=>array('value'=>urlencode('测试状态'),'color'=>'#FF0000'),
        )
     );
     $json_template=json_encode($template);
     return $json_template;
     }

    // 模型名
    protected $name = 'user';

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

    // 学历
    public function educationalType()
    {
        return $this->hasOne(DictData::class, 'id', 'educational_id')->bind(['educational_name' => 'name']);
    }

    // 职称
    public function professionalType()
    {
        return $this->hasOne(DictData::class, 'id', 'professional_id')->bind(['professional_name' => 'name']);
    }

    // 职务
    public function positionType()
    {
        return $this->hasOne(DictData::class, 'id', 'position_id')->bind(['position_name' => 'name']);
    }

    // 用户阅读记录关联
    public function msgReads()
    {
        return $this->hasMany(MsgRead::class, 'user_id', 'id');
    }

    // 用户报名记录关联
    public function trainingSigns()
    {
        return $this->hasMany(TrainingSign::class, 'user_id', 'id');
    }

    // 处理图片
    public function getHeadimgAttr($value)
    {
        return FileService::getFileUrl($value);
    }



}