<?php
declare (strict_types = 1);

namespace app\api\controller;
use app\api\ApiBase;
use app\api\model\UserModel;
use app\api\model\CmsDepartment;
use think\facade\Request;
use think\facade\Db;
use app\mxadmin\model\Config;
class User extends ApiBase
{
    public function index()
    {
        //过滤图片地址的域名  
        /*$path =ltrim(parse_url(qrcode(1,null),PHP_URL_PATH));
        return $path; */
        $a = array(11,2,5,3);

        $total = array_sum($a);

        array_walk($a, function(&$item, $key, $prefix) { $item = round($item*100/$prefix); }, $total);

        if($d = (100 - array_sum($a))) $a[rand(0, count($a)-1)] += $d;

        print_r($a);
         //echo Db::name('user_live')->getlastSql();
    }


    public function getUserinfo()
    {
        if (request()->isPost()) {
            $oUser = $this->oUser;
            //计算天数
            $origin = date_create(date("Y-m-d",time()));
            $target = date_create(date("Y-m-d",$oUser['vip_time']));
            $interval = date_diff($origin, $target);
            $day = $interval->format('%a');
            if($oUser['vip_time']==0){
                $user['end_time']="";
            }else{
                $user['end_time']="体验剩余时间".$day.'天';
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$user['end_time']]);
        }
    }
    /**
     * 机构列表
     * @return \think\response\View
     */
    public function department()
    {
       return json(['code'=>200,'msg'=>'成功','data'=>list_to_trees(CmsDepartment::getCategoryData(), true)]);
    }

    /**
     * 手机号登录
     * @return \think\response\View
     */
    public function login()
    {
        $model = new UserModel();
        $params = $this->request->post();
        if(empty($params)){
            return $this->returnError('无参数');
        }
        if (empty($params['phone'])) {
            return $this->returnError('账号不能为空');
        }
        if (empty($params['code'])) {
            return $this->returnError('缺少验证码');
        }
        return $model->loginPhone($params);
    }
    /**
     * 手机号登录
     * @return \think\response\View
     */
    public function adlogin()
    {
        $model = new UserModel();
        $params = $this->request->post();
        if(empty($params)){
            return $this->returnError('无参数');
        }
        if (empty($params['phone'])) {
            return $this->returnError('账号不能为空');
        }
        if (empty($params['code'])) {
            return $this->returnError('缺少验证码');
        }
        return $model->loginAndroide($params);
    }

    //手机号注册
    public function register(){
        $model = new UserModel();
        $data = [
            'phone' => Request::instance()->param('phone'),
            //'password' => Request::instance()->param('password'),
            'workname' => Request::instance()->param('workname'),
            'nickname' => Request::instance()->param('nickname'),
            'd_id' => Request::instance()->param('d_id'),
            'code' => Request::instance()->param('code'),
        ];
        
        return $model->registerPhone($data);
    }

    //注册发送验证码
    public function send_sms(){
        $model = new UserModel();
        $data = [
            'phone' => Request::instance()->param('phone')
        ];
        return $model->send_sms($data);
    }
    
    //登录发送验证码
    public function send_sms_all(){
        $model = new UserModel();
        $data = [
            'phone' => Request::instance()->param('phone')
        ];
        return $model->send_sms_all($data);
    }

    //安卓注册登录发送验证码
    public function send_sms_android(){
        $model = new UserModel();
        $data = [
            'phone' => Request::instance()->param('phone')
        ];
        return $model->send_sms_android($data);
    }
    
    
    

    //退出登录
    public function log_out(){
        $model = new UserModel();
        $data = [
            'token' => Request::instance()->header('token'),
        ];
        return $model->log_out($data);
    }    //退出登录
    public function h5_out($token){
        $model = new UserModel();
        $data = [
            'token' => $token,
        ];
        return $model->log_out($data);
    }
    
    //找回密码
    public function retrieve_password(){
        $model = new UserModel();
        $params = $this->request->post();
        $result = $model->retrieve_password($params);
        return $this->returnSuccess();
    }
    //H5我的历史记录列表
    public function myinfo($category_id,$urltype){
        //$token = Request::instance()->header('token');
        $token = input('post.token');
        //echo $token;exit;
        if(empty($token)){
           return json(['code'=>400,'msg'=>'token empty']);
        }
        $uid = Db::name('user_token')->where('token',$token)->where('type', 1)->value('u_id');
        if(empty($uid)){
           return json(['code'=>401,'msg'=>'token error']);
        }
        //查询
        $page = !empty(input('page'))?input('page'):1;
        $limit = !empty(input('limit'))?input('limit'):9;
        $firstRow = $limit*($page-1);
       
        $where['u_id'] = $uid;
        $where['category_id'] = $category_id;
        if($urltype==1){
            $list = DB::name('user_live')->where($where)->field("from_unixtime(create_time,'%Y-%m-%d') as time")->group('time')->limit($firstRow,$limit)->select()->toArray();
            foreach ($list as $key=>$val){
                $startTime = $val['time'];
                $endTime = $val['time'].'23:59:59';
                $list[$key]['list']=Db::name('user_live')
                 ->alias('a')
                    ->join('cms_live c','a.l_id = c.id')
                    ->whereTime('a.create_time', 'between', [strtotime($startTime), strtotime($endTime)])
                    ->field(['c.id,c.title,c.image,c.description'])->order('a.create_time DESC')->select();
              
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }elseif($urltype==0){
            $wheres['a.u_id'] = $uid;
            $list=DB::name('user_article')->where($where)->field("from_unixtime(create_time,'%Y-%m-%d') as time")->group('time')->limit($firstRow,$limit)->select()->toArray();
            //echo Db::name('user_article')->getLastSql();exit;
            foreach ($list as $key=>$val){
                $startTime = $val['time'];
                $endTime = $val['time'].'23:59:59';
                $list[$key]['list']=Db::name('user_article')
                 ->alias('a')
                    ->where($wheres)
                    ->join('cms_article c','a.a_id = c.id')
                    ->whereTime('a.create_time', 'between', [strtotime($startTime), strtotime($endTime)])
                    ->field(['c.id,c.title,c.image,c.description'])->order('a.create_time DESC')->select();
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }else{

        }    
        
    }
    
    //安卓我的历史记录列表
    public function myandroidinfo($category_id,$urltype){
        //查询
        $page = !empty(input('page'))?input('page'):1;
        $limit = !empty(input('limit'))?input('limit'):9;
        $firstRow = $limit*($page-1);
        $oUser = $this->oUser;
        $uid = $oUser['id'];
        //print_r($oUser);exit;
        $where['u_id'] = $uid;
        $where['category_id'] = $category_id;
        if($urltype==1){
            $list = DB::name('user_live')->where($where)->field("from_unixtime(create_time,'%Y-%m-%d') as time")->group('time')->limit($firstRow,$limit)->select()->toArray();
            foreach ($list as $key=>$val){
                $startTime = $val['time'];
                $endTime = $val['time'].'23:59:59';
                $list[$key]['list']=Db::name('user_live')
                 ->alias('a')
                    ->join('cms_live c','a.l_id = c.id')
                    ->whereTime('a.create_time', 'between', [strtotime($startTime), strtotime($endTime)])
                    ->field(['c.id,c.title,c.image,c.description'])->order('a.create_time DESC')->select();
              
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }elseif($urltype==0){
            $wheres['a.u_id'] = $uid;
            $list=DB::name('user_article')->where($where)->field("from_unixtime(create_time,'%Y-%m-%d') as time")->group('time')->limit($firstRow,$limit)->select()->toArray();
            //echo Db::name('user_article')->getLastSql();exit;
            foreach ($list as $key=>$val){
                $startTime = $val['time'];
                $endTime = $val['time'].'23:59:59';
                $list[$key]['list']=Db::name('user_article')
                 ->alias('a')
                    ->where($wheres)
                    ->join('cms_article c','a.a_id = c.id')
                    ->whereTime('a.create_time', 'between', [strtotime($startTime), strtotime($endTime)])
                    ->field(['c.id,c.title,c.image,c.description'])->order('a.create_time DESC')->select();
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }else{
            return json(['code'=>500,'msg'=>'参数错误','data'=>'']);
        }   
        
    }
    
    //我的直播
    public function myh5info($category_id,$urltype){
        //$token = Request::instance()->header('token');
        $token = input('post.token');
        //echo $token;exit;
        if(empty($token)){
           return json(['code'=>10001,'msg'=>'token empty']);
        }
        $uid = Db::name('user_token')->where('token',$token)->where('type', 1)->value('u_id');
        if(empty($uid)){
           return json(['code'=>10002,'msg'=>'token error']);
        }
        //查询
        $page = !empty(input('page'))?input('page'):1;
        $limit = !empty(input('limit'))?input('limit'):9;
        $firstRow = $limit*($page-1);
       
        $where['u_id'] = $uid;
        $where['category_id'] = $category_id;
        if($urltype==1){
            $list = DB::name('user_live')->where($where)->field("from_unixtime(create_time,'%Y-%m-%d') as time")->group('time')->limit($firstRow,$limit)->select()->toArray();
            foreach ($list as $key=>$val){
                $startTime = $val['time'];
                $endTime = $val['time'].'23:59:59';
                $list[$key]['list']=Db::name('user_live')
                 ->alias('a')
                    ->join('cms_live c','a.l_id = c.id')
                    ->whereTime('a.create_time', 'between', [strtotime($startTime), strtotime($endTime)])
                    ->field(['c.id,c.title,c.image,c.description'])->order('a.create_time DESC')->select();
              
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }elseif($urltype==0){
            $wheres['a.u_id'] = $uid;
            $list=DB::name('user_article')->where($where)->field("from_unixtime(create_time,'%Y-%m-%d') as time")->group('time')->limit($firstRow,$limit)->select()->toArray();
            //echo Db::name('user_article')->getLastSql();exit;
            foreach ($list as $key=>$val){
                $startTime = $val['time'];
                $endTime = $val['time'].'23:59:59';
                $list[$key]['list']=Db::name('user_article')
                 ->alias('a')
                    ->where($wheres)
                    ->join('cms_article c','a.a_id = c.id')
                    ->whereTime('a.create_time', 'between', [strtotime($startTime), strtotime($endTime)])
                    ->field(['c.id,c.title,c.image,c.description'])->order('a.create_time DESC')->select();
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }else{
            return json(['code'=>500,'msg'=>'参数错误','data'=>'']);
        }   
        
    }
    
    //我的文章
    public function userarticle($category_id){
        $uid=1;
        $where['a.u_id'] = ['eq',$uid];
        $where['a.category_id'] = $category_id;
        $list = Db::name('user_article')
        ->alias('a')
        ->join('cms_article c','a.a_id = c.id')
        ->where($where)->order('a.create_time asc,a.id desc')->field('c.id,a.create_time,c.image,c.title,c.description')->limit(0,6)->select();
        //echo Db::name('user_live')->getLastSql();
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }
    public function dd(){

        $list['time']=DB::name('user_live')->field("from_unixtime(create_time,'%Y-%m-%d') as time,group_concat(l_id)")->group('time')->select();
        foreach ($list['time'] as &$item){
        //$value['money']=$item['group_concat(id)'];
        $list['list']=Db::name('cms_live')->where('id','in',$item['group_concat(l_id)'])->field(['id,title,image,description'])->order('create_time DESC')->select();
        //array_push($data,$value);
        }
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }
}
