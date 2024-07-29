<?php

namespace app\api\controller;

use app\api\ApiBase;
use think\facade\Config;
use think\facade\Db;
use think\facade\Log;
use think\Response;

class Token extends ApiBase
{


    /**
     * description:有劳写下注释
     * author: esc
     * Date: 2023/11/18 20:49
     * @param $stream_name //频道名称。
     */
    public function setRtcToken($stream_name)
    {
        //log::write('setRtcToken',$stream_name);
        $result=generateAgoraToken($stream_name);

        return json(['code'=>200,'msg'=>'成功','data'=>$result]);
    }

    /**
     * description:有劳写下注释
     * author: esc
     * Date: 2023/11/18 20:49
     * @param $streamName //要查询播流统计的直播流名称。
     */
    public function setRtmToken($uid)
    {
        $result=generateAgoraRTMToken($uid);

        return json(['code'=>200,'msg'=>'成功','data'=>$result]);
    }

}