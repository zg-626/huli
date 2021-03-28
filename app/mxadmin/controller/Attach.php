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

namespace app\mxadmin\controller;

use app\mxadmin\AdminBase;
use app\mxadmin\model\Attachment;

class Attach extends AdminBase
{
    /**
     * 附件管理
     * @return \think\response\View
     */
    public function index()
    {
        return view();
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist($limit=15)
    {
        $list = Attachment::with(['username'])->order('id', 'desc')->paginate($limit);
        return $this->result($list);
    }

    /**
     * 搜索数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function serach($limit=15)
    {
        if (request()->isGet()) {
            $data = input('param.');
            $serach = new Attachment();
            if ($data['storage'] != '') {
                $serach = $serach->where('storage', $data['storage']);
            }
            $list = $serach->with(['username'])->order('id', 'desc')->paginate($limit);
            return $this->result($list);
        }
    }

    /**
     * 删除附件
     * @param $id
     */
    public function del($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            if (empty($id)) {
                $ids = explode(',', $data['ids']);
            } else {
                $ids = $id;
            }

            $idsdata = Attachment::whereIn('id', $ids)->select();
            foreach($idsdata as $value){
                if ($value['storage'] === '七牛云') {
                    $arr = parse_url($value['url']);
                    $upload = new Upload($this->app);
                    $upload->delFile(substr($arr['path'],1));
                } else if($value['storage'] === '本地') {
                    $url = str_ireplace(request()->domain(), '', $value['url']);
                    $path = substr($url, 1);
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
            }
            $result = Attachment::destroy($ids);
            if ($result == true) {
                return $this->success('删除成功');
            } else {
                return $this->error('删除失败');
            }
        }
    }
}
