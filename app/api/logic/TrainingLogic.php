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
        return Training::with(['typename'])->withCount(['signups'])->findOrEmpty($params['id'])->toArray();
    }
}