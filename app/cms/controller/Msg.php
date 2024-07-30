<?php

declare (strict_types = 1);

namespace app\cms\controller;

use app\mxadmin\AdminBase;
use app\cms\model\CmsMsg;
use app\cms\model\CmsCategory;
use think\facade\Db;
use MobTech\MobPush\Config\MobPushConfig;
use MobTech\MobPush\Client\Push\PushV3Client;
use think\exception\ValidateException;

class Msg extends AdminBase
{
    private $ios_access_id = '1680012835';
    private $ios_secret_key = 'c8ec29397e44999e8c799b89bde3e59a';
    /**
     * 表单弹窗页面
     * @return \think\response\View
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function form()
    {
        return view('',[
            'category'   =>  getDictDataId(5),
        ]);
    }


    /**
     * 消息管理
     * @return \think\response\View
     */
    public function index()
    {
        return view('', [
            'msgtype'   =>  getDictDataId(5),
        ]);
    }

    /**
     * 返回Json格式的数据
     * @param int $limit
     * @throws \think\db\exception\DbException
     */
    public function datalist($limit=15)
    {
        $list = CmsMsg::with(['admin', 'type'])->order('weight asc,id desc')->paginate($limit);
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
            $serach = new CmsMsg();
            if ($data['type'] != '') {
                $serach = $serach->where('category_id', $data['type']);
            }
            if ($data['title'] != '') {
                $serach = $serach->whereLike('title', '%' . $data['title'] . '%');
            }
            /*if ($data['status'] != '') {
                $serach = $serach->where('status', $data['status']);
            }*/
            $list = $serach->with(['admin', 'type'])->order('weight asc,id desc')->paginate($limit);
            return $this->result($list);
        }
    }

    /**
     * 添加消息
     */
    public function add()
    {
        if (request()->isPost()) {
            $data = input('param.');
            /*try {
                $this->validate($data, 'Article');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }*/
            $data['admin_id'] = getAdminId();

            $result = CmsMsg::create($data);
            if ($result == true) {
                return $this->success('添加成功');
            } else {
                return $this->error('添加失败');
            }
        }
    }

    /**
     * 修改消息
     */
    public function edit($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            /*try {
                $this->validate($data, 'Article');
            } catch (ValidateException $e) {
                // 验证失败 输出错误信息
                return $this->error($e->getError());
            }*/

            $result = CmsMsg::update($data, ['id' => $id]);
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
            $result = CmsMsg::update(['status' => $data['status']], ['id' => $id]);
            if ($result == true) {
                return $this->success($data['status'] ? '已启用' : '已禁用');
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
            $result = CmsMsg::update(['weight' => $data['weight']], ['id' => $id]);
            if ($result == true) {
                return $this->success('修改成功');
            } else {
                return $this->error('修改失败');
            }
        }
    }

    /**
     * 删除消息
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
            $result = CmsMsg::destroy($ids);
            if ($result == true) {
                return $this->success('删除成功');
            } else {
                return $this->error('删除失败');
            }
        }
    }

    /**
     * 推送消息
     * @param $id
     */
    public function tui($id)
    {
        if (request()->isPost()) {
            $config = [
               'appid' => '1580013096',

               'secretKey' => 'f6db56d4fcf94cf0eb9db66ef6c9a54b',
               
               'appidios' => '1680013098',

               'secretKeyios' => '77734f3353b23dd0eca5ca0d6f993e6c',

               //'message_type' => ''
            ];
            $message_type='notify';
            $data = input('param.');
            $info=Db::name('cms_msg')->where('id',$id)->find();
            $userid=Db::name('user')->where('status',1)->field('id')->select();

            if($info['category_id']==1){
                $params='messageSystem';
                foreach ($userid as $v) {
                    $result = Db::name('msginfo')->insert(['u_id' => $v['id'],'title' => $info['title'],'content' => $info['content'],'category_id' => 1,'msgid' => $info['id'],'create_time'=>time(),'update_time'=>time()]);
                } 
                $content=[
                   'title'=>$info['title'],
                   'content'=>$info['content']
                ];
                $content['android']['custom_content'] = json_encode('messageSystem');
            }else{
                $params='messageSlager';
                foreach ($userid as $v) {
                    $result = Db::name('msginfo')->insert(['u_id' => $v['id'],'title' => $info['title'],'content' => $info['content'],'category_id' => 2,'msgid' => $info['id'],'redirecturl' => $info['redirecturl'],'image' => $info['image'],'create_time'=>time(),'update_time'=>time()]);
                } 
                $content=[
                   'title'=>$info['title'],
                   'content'=>$info['content']
                ];
                $content['android']['custom_content'] = json_encode('messageSlager');
            }
            $app_push = new TencentPushTpns($config);
            

            $push=$app_push->send_all($content,$message_type);
            
            $pushs=$app_push->send_allios($info['title'],$info['content'],$message_type,$params);
            
            if($result==true){
                CmsMsg::where('id',$id)->update(['status'=>1]);
                if($push['err_msg']=='NO_ERROR'){
                    return $this->success('推送成功');
                }else{
                     return $this->error('推送失败，请查看推送运营商');
                }
                
            }else{
                return $this->error('推送失败');
            }
        }
    }

    /**
     * 推送消息
     * @param $id
     */
    public function che($id)
    {
        if (request()->isPost()) {
            $data = input('param.');
            $info=Db::name('cms_msg')->where('id',$id)->find();
            
            $mobPushConfig = new MobPushConfig();
            $mobPushConfig::$appkey = '365c7964e62d6';
            $mobPushConfig::$appSecret = '8da7442066435f68d236231c0eb9cdb0';

            /* Registration ID推送 */
            $push=json_decode((new PushV3Client())->recallPushTask($info['batchId']),true);
            if($push['status']==200){
                CmsMsg::where('id',$id)->update(['batchId'=>$push['res']['batchId'],'status'=>2]);
                return $this->success('撤回成功');
            }elseif($push['code']==400){
                $message=json_decode($push['message'],true);
                return $this->error($message['error']);
            }
        }
    }
}
