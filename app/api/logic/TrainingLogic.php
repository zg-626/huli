<?php
// +----------------------------------------------------------------------
// | likeadmin快速开发前后端分离管理后台（PHP版）
// +----------------------------------------------------------------------
// | 欢迎阅读学习系统程序代码，建议反馈是我们前进的动力
// | 开源版本可自由商用，可去除界面版权logo
// | gitee下载：https://gitee.com/likeshop_gitee/likeadmin
// | github下载：https://github.com/likeshop-github/likeadmin
// | 访问官网：https://www.likeadmin.cn
// | likeadmin团队 版权所有 拥有最终解释权
// +----------------------------------------------------------------------
// | author: likeadminTeam
// +----------------------------------------------------------------------

namespace app\api\logic;


use app\common\logic\BaseLogic;
use app\cms\model\Training;
use app\common\service\FileService;
use think\facade\Db;


/**
 * Training逻辑
 * Class TrainingLogic
 * @package app\api\logic
 */
class TrainingLogic extends BaseLogic
{
    /**
     * @notes 获取详情
     * @param $params
     * @return array
     * @author esc
     * @date 2023/09/18 14:09
     */
    public static function detail($params): array
    {
        $userId = $params['user_id'] ?? 0;
        $info = Training::with(['typename', 'signups'=>function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->withCount(['signups'])->findOrEmpty($params['id']);
        if ($info->isEmpty()) {
            return [];
        }

        // 增加点击量
        $info->click += 1;
        $info->save();

        // 格式化每个 signup 的 check_time
        foreach ($info->signups as $signup) {
            if ($signup->check_time) {
                $signup->check_time = date('Y-m-d H:i:s',$signup->check_time);
            }
            if ($signup->study_time) {
                $signup->study_time = date('Y-m-d H:i:s',$signup->study_time);
            }
        }




        return $info->append(['click'])
            ->toArray();
    }
}