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
use app\common\model\Shop;
use app\common\lists\ListsSearchInterface;
use app\common\service\FileService;


/**
 * 店铺列表
 * Class ShopLists
 * @package app\api\lists
 */
class ShopLists extends BaseApiDataLists
{


    /**
     * @notes 设置搜索条件
     * @return \string[][]
     * @author likeadmin
     * @date 2023/12/22 13:47
     */
    public function setSearch(): array
    {
        return [
            '=' => ['is_show'],
            '%like%' => ['title'],

        ];
    }


    /**
     * @notes 获取店铺列表
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author likeadmin
     * @date 2023/12/22 13:47
     */
    public function lists(): array
    {
        $where[] = ['is_show', '=', 1];
        $orderRaw = 'sort desc, id desc';
        $list = Shop::where($this->searchWhere)
            ->where($where)
            ->field(['id', 'title', 'desc', 'abstract', 'image', 'is_show','content', 'create_time','banner'])
            ->limit($this->limitOffset, $this->limitLength)
            ->orderRaw($orderRaw)
            ->select()
            ->toArray();
        foreach ($list as &$item) {
            if (!empty($item['banner'])) {
                $bannerArray = explode(',', $item['banner']);
                $bannerArray = array_map([FileService::class, 'getFileUrl'], $bannerArray);
                $item['banner'] = $bannerArray;
            }
        }

        return $list;
    }


    /**
     * @notes 获取店铺数量
     * @return int
     * @author likeadmin
     * @date 2023/12/22 13:47
     */
    public function count(): int
    {
        return Shop::where($this->searchWhere)->count();
    }

}