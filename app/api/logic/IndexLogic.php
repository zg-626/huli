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


use app\cms\model\CmsCategory;
use app\cms\model\CmsQrcode;
use app\common\logic\BaseLogic;
use app\common\model\article\Article;
use app\common\model\Banner;
use app\common\model\Barrage;
use app\common\model\decorate\DecoratePage;
use app\common\model\decorate\DecorateTabbar;
use app\common\model\Speak;
use app\common\model\Step;
use app\common\service\ConfigService;
use app\common\service\FileService;
use app\mxadmin\model\Config;
use app\mxadmin\model\DictData;


/**
 * index
 * Class IndexLogic
 * @package app\api\logic
 */
class IndexLogic extends BaseLogic
{

    /**
     * @notes 首页数据
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:15
     */
    public static function getIndexData()
    {
        // 装修配置
        $decoratePage = DecoratePage::findOrEmpty(1);

        // 首页文章
        $field = [
            'id',
            'title',
            'desc',
            'abstract',
            'image',
            'author',
            'click_actual',
            'click_virtual',
            'create_time'
        ];

        $article = Article::field($field)
            ->where(['is_show' => 1])
            ->order(['id' => 'desc'])
            ->limit(20)->append(['click'])
            ->hidden(['click_actual', 'click_virtual'])
            ->select()->toArray();

        return [
            'page' => $decoratePage,
            'article' => $article
        ];
    }

    /**
     * @notes banner
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:15
     */
    public static function getBanner()
    {
        // banner
        $field = [
            'id',
            'image'
        ];

        $article = Banner::field($field)
            //->where(['delete_time' => null])
            //->order(['sort' => 'desc'])
            ->select()->toArray();
        /*foreach ($article as &$item) {
            $item['image'] = FileService::getFileUrl($item['image']);
        }*/

        return $article;
    }

    /**
     * @notes banner
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:15
     */
    public static function getQrcode()
    {
        // banner
        $field = [
            'id',
            'image'
        ];

        $article = CmsQrcode::field($field)
            ->where('validity_time', '>', time())
            //->order(['sort' => 'desc'])
            ->select()->toArray();
        foreach ($article as &$item) {
            $item['image'] = FileService::getFileUrl($item['image']);
        }

        return $article;
    }

    /**
     * @notes 分类
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:15
     */
    public static function getCategory($pid = 0)
    {
        $field = [
            'id',
            'name'
        ];

        $article = CmsCategory::field($field)
            ->where(['pid' => $pid, 'status' => 1])
            ->order(['weight' => 'asc'])
            ->select()->toArray();

        return $article;
    }


    /**
     * @notes 获取政策协议
     * @param string $type
     * @return array
     * @author 段誉
     * @date 2022/9/20 20:00
     */
    public static function getPolicyByType(string $type)
    {
        return [
            'title' => ConfigService::get('agreement', $type . '_title', ''),
            'content' => ConfigService::get('agreement', $type . '_content', ''),
        ];
    }


    /**
     * @notes 装修信息
     * @param $id
     * @return array
     * @author 段誉
     * @date 2022/9/21 18:37
     */
    public static function getDecorate($id)
    {
        return DecoratePage::field(['type', 'name', 'data'])
            ->findOrEmpty($id)->toArray();
    }

    /**
     * @notes 发声列表
     * @return array
     * @author 段誉
     * @date 2022/9/21 18:37
     */
    public static function getSpeak()
    {
        return Speak::where(['is_show' => 1])->field(['id', 'title', 'image'])->order(['sort' => 'desc', 'id' => 'desc']
        )
            ->select()->toArray();
    }

    /**
     * @notes 弹幕列表
     * @return array
     * @author 段誉
     * @date 2022/9/21 18:37
     */
    public static function getBarrage()
    {
        return Barrage::where(['is_show' => 1])->field(['id', 'title'])->order(['sort' => 'desc', 'id' => 'desc'])
            ->select()->toArray();
    }


    /**
     * @notes 获取配置
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @author 段誉
     * @date 2022/9/21 19:38
     */
    public static function getConfigData()
    {
        $sysconf=Config::getConfigData('system');
        if($sysconf && $sysconf['logo'] !== ''){
            $sysconf['logo']=FileService::getFileUrl($sysconf['logo']);
        }
        return $sysconf;
    }

    public static function getDictionary(mixed $dict_id)
    {
        return DictData::where(['dict_id' => $dict_id, 'status' => 1])->order(['weight' => 'asc'])->select()->toArray();
    }

}