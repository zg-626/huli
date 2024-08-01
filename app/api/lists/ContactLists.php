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


use app\common\lists\BaseDataLists;
use app\common\model\Contact;
use app\common\lists\ListsSearchInterface;


/**
 * Contact列表
 * Class ContactLists
 * @package app\api\lists
 */
class ContactLists extends BaseApiDataLists
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function setSearch(): array
    {
        return [
            '=' => ['type', 'company_name', 'occupation', 'website_url', 'phone_number', 'email', 'create_time'],
            '%like%' => ['title', 'department', 'contact_person'],

        ];
    }


    /**
     * @notes 获取列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function lists(): array
    {
        return Contact::where($this->searchWhere)
            ->field(['id', 'type', 'title', 'department', 'contact_person', 'company_name', 'occupation', 'website_url', 'phone_number', 'email', 'inquiry_text', 'create_time'])
            ->limit($this->limitOffset, $this->limitLength)
            ->order(['id' => 'desc'])
            ->select()
            ->toArray();
    }


    /**
     * @notes 获取数量
     * @return int
     * @author likeadmin
     * @date 2023/12/21 16:14
     */
    public function count(): int
    {
        return Contact::where($this->searchWhere)->count();
    }

}