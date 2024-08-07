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


use app\cms\model\Question;
use app\common\service\FileService;


/**
 * Question列表
 * Class QuestionLists
 * @package app\api\lists
 */
class QuestionLists extends BaseApiDataLists
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
        $where[] = ['status', '=', 1];

        // 学习班id
        if (!empty($this->params['paper_id'])) {
            $where[] = ['paper_id', '=', $this->params['paper_id']];
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
        $list = Question::where($this->searchWhere)
            ->where($this->queryWhere())->append(['typeName'])
            //->field(['id', 'user_id', 'content', 'contact', 'attachment', 'feeb_status', 'create_time'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                // 解码 options 字段为关联数组
                $options = json_decode($value['options'], true);

                // 将 options 中的每个选项作为新字段保存
                /*foreach ($options as $optionKey => $optionValue) {
                    $list[$key][$optionKey] = $optionValue;
                }*/
                $list[$key]['options'] = $options;
                // 如果 type 等于 2，处理 select 字段（假设 select 是以逗号分隔的字符串）
                if ($value['type'] == 2) {
                    $list[$key]['select'] = explode(',', $value['select']);
                }
            }
        }
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
        return Question::where($this->queryWhere())->where($this->searchWhere)->count();
    }

}