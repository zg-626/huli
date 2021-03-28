<?php
// +----------------------------------------------------------------------
// | mxAdmin
// +----------------------------------------------------------------------
// | 版权所有 2020~2050 福州目雪科技有限公司 [ http://www.muxue.com.cn ]
// +----------------------------------------------------------------------
// | 演示地址: http://demo.muxue.com.cn
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 代码仓库：https://gitee.com/muxue2020/mxAdmin
// +----------------------------------------------------------------------
// | Author: 明仔 <350656405@qq.com>    微信号：zlmlovem
// +----------------------------------------------------------------------
declare (strict_types = 1);

namespace app\cms\controller;

use app\mxadmin\AdminBase;
use app\cms\model\CmsArticle;
use app\cms\model\CmsCategory;
use think\exception\ValidateException;

class Category extends AdminBase
{
    /**
     * 栏目管理
     * @return \think\response\View
     */
    public function index()
    {
        return view();
    }

    /**
     * 返回Json格式的数据
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function datalist()
    {
        $list = CmsCategory::order(['weight','id'])->select();
        return $this->result($list);
    }

    /**
     * 展开/折叠栏目
     */
    public function datalist_open_same()
    {
        if (request()->isPost()) {
            $openType = input('post.type/d');
            if ($openType == 0) {
                $data = CmsCategory::field('id,pid,open')->select()->toArray();
                foreach($data as $k => $v){
                    $children = CmsCategory::where('pid', $v['id'])->count();
                    if($children == 0){
                        $data[$k]['open'] = 1;
                    }else{
                        $data[$k]['open'] = 0;
                    }
                }
                $category = new CmsCategory();
                $category->saveAll($data);
            } else if($openType == 1) {
                CmsCategory::update(['open' => 1], ['open' => 0]);
            }
            $list = CmsCategory::order(['weight','id'])->select();
            return $this->result($list);
        }
    }

    /**
     * 添加栏目
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Category');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            $result = CmsCategory::create($data);
            if ($result == true) {
                CmsCategory::setUrl($result->id);
                CmsCategory::setTopid($result->id);
                return $this->success('添加成功');
            } else {
                return $this->error('添加失败');
            }
        }
    }

    /**
     * 修改栏目
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            try {
                $this->validate($data, 'Category');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }
            $result = CmsCategory::update($data, ['id' => $id]);
            if ($result == true) {
                CmsCategory::setUrl($id);
                CmsCategory::setTopid($id);
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 修改排序
     */
    public function edit_weight_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = CmsCategory::update(['weight' => $data['weight']], ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 修改状态
     */
    public function edit_state_same($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $result = CmsCategory::update(['status' => $data['status']], ['id' => $id]);
            if ($result == true) {
                return $this->success($data['status'] ? '已启用' : '已禁用');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 删除栏目
     */
    public function del($id)
    {
        if (request()->isPost()) {
            $result = CmsCategory::destroy($id);
            CmsArticle::where('category_id', $id)->delete();
            if ($result == true) {
                return $this->success('删除成功');
            } else {
                return $this->error('删除失败');
            }
        }
    }
}
