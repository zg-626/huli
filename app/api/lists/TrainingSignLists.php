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

namespace app\api\lists;


use app\cms\model\TrainingSign;
use app\common\service\FileService;


/**
 * TrainingSign列表
 * Class TrainingSignLists
 * @package app\api\lists
 */
class TrainingSignLists extends BaseApiDataLists
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function setSearch(): array
    {
        return [
            '=' => ['content', 'status'],
        ];
    }


    /**
     * @notes 搜索条件
     * @return array
     * @author esc
     * @date 2023/9/27 20:00
     */
    public function queryWhere()
    {
        // 指定用户
        $where[] = ['user_id', '=', $this->userId];

        // 变动类型
        if (!empty($this->params['feeb_status'])) {
            $where[] = ['feeb_status', '=', $this->params['feeb_status']];
        }

        return $where;
    }

    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function lists(): array
    {
        $list = TrainingSign::with(['training'])->where($this->searchWhere)
            ->where($this->queryWhere())
            //->field(['id', 'user_id', 'content', 'contact', 'attachment', 'feeb_status', 'create_time'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
        /*foreach ($list as &$item) {
        }*/
        return $list;
    }


    /**
     * @notes 获取数量
     * @return int
     * @author esc
     * @date 2023/09/18 14:09
     */
    public function count(): int
    {
        return TrainingSign::where($this->queryWhere())->where($this->searchWhere)->count();
    }

}