<?php
declare (strict_types = 1);

namespace app\api\controller;
use app\api\model\CmsAd;
use app\api\ApiBase;
use think\facade\Request;
use think\facade\Db;
use app\mxadmin\AdminBase;
use app\cms\model\CmsCategory;
use app\mxadmin\model\Config;
use think\facade\Log;
class Index extends ApiBase
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $sysconf = Config::where('type', 'system')->find()['value'];
        return json(['code'=>200,'msg'=>'成功','data'=>$sysconf]);
    }

    /**
     * 版本更新
     *
     * @return \think\Response
     */
    public function getNew()
    {   
        $sysconf = Config::where('type', 'system')->find()['value'];
        array_splice($sysconf, 2, 1);
        unset($sysconf['typename'],$sysconf['logo'],$sysconf['bgimg'],$sysconf['webname'],$sysconf['domain'],$sysconf['title'],$sysconf['keywords'],$sysconf['description']);
        //return json(['code'=>200,'msg'=>'成功','data'=>$sysconf]);
        return json(['code'=>200,'msg'=>'成功','data'=>$sysconf]);
    }


    //我的直播
    public function history($category_id,$urltype){
        //$token = Request::instance()->header('token');
        $token = input('post.token');
        if(empty($token)){
           return json(['code'=>400,'msg'=>'请登录']);
        }
        $uid = Db::name('user_token')->where('token',$token)->where('type', 1)->value('u_id');
        if(empty($uid)){
           return json(['code'=>401,'msg'=>'token 不存在！']);
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
            $list=DB::name('user_article')->where($where)->field("from_unixtime(create_time,'%Y-%m-%d') as time")->group('time')->limit($firstRow,$limit)->select()->toArray();
            foreach ($list as $key=>$val){
                $startTime = $val['time'];
                $endTime = $val['time'].'23:59:59';
                $list[$key]['list']=Db::name('user_article')
                 ->alias('a')
                    ->join('cms_article c','a.a_id = c.id')
                    ->whereTime('a.create_time', 'between', [strtotime($startTime), strtotime($endTime)])
                    ->field(['c.id,c.title,c.image,c.description'])->order('a.create_time DESC')->select();
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }else{

        }    
        
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     * @return $type--分类id
     */
    public function banner($type)
    {
        $where['status'] = 1;
        $where['type'] = ['eq',$type];
        $token = input('post.token');
        if(empty($token)){
            return json(['code'=>10001,'msg'=>'token empty']);
        }else{
            $uid = Db::name('user_token')->where('token',$token)->where('type', 1)->value('u_id');
            if(empty($uid)){
                return json(['code'=>10002,'msg'=>'token error']);
            }
            $d_id = Db::name('user')->where('id',$uid)->value('d_id');
             $where['d_id'] =$d_id;
             $list = CmsAd::where($where)->order('weight asc,id desc')->field('id,title,url,image')->select();
        }
        
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }

    /**
     * H5历史导航
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function nav()
    {
        $where['status'] = 1;
        $token = input('post.token');
        if(empty($token)){
            return json(['code'=>10001,'msg'=>'token empty']);
        }else{
            $uid = Db::name('user_token')->where('token',$token)->where('type', 1)->value('u_id');
            if(empty($uid)){
                return json(['code'=>10002,'msg'=>'token error']);
            }
            $d_id = Db::name('user')->where('id',$uid)->value('d_id');
            //获取顶级栏目id
            //$topid=$this->setTopid($d_id);
            $where['d_id'] =$d_id;
            $list = Db::name('cms_nav')->where($where)->whereNotIn('urltype','3,4')->order('weight asc,id desc')->field('id,urltype,name')->limit(4)->select();
        }     
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }
    
    /**
     * 安卓历史导航
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function androidnav()
    {
        $where['status'] = 1;
        $oUser = $this->oUser;
        $d_id=$oUser['d_id'];
        $where['d_id'] =$d_id;
        $list = Db::name('cms_nav')->where($where)->whereIn('urltype','0,1')->order('weight asc,id desc')->field('id,urltype,name')->limit(4)->select();
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }
    
    /**
     * 导航
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function mobilenav()
    {
        $where['status'] = 1;
        //$where['type'] = ['eq',$type];
        $list = Db::name('cms_nav')->where($where)->order('weight asc,id desc')->field('id,urltype,url,name,image')->limit(0,4)->select();
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }
    /**
     * 文章/直播
     * 1公告2党建引领
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function article($category_id,$urltype)
    {
        $page = !empty(input('page'))?input('page'):1;
        $limit = !empty(input('limit'))?input('limit'):9;
        $firstRow = $limit*($page-1);
        $where['status'] = 1;
        $where['category_id'] = $category_id;
        if($urltype==0){         
            $list = Db::name('cms_article')->where($where)->order('weight asc,id desc')->field('id,title,image,create_time,description')->limit($firstRow,$limit)->select()->toArray();     
        }else{
            $oUser = $this->oUser;
            //$where['d_id']=$oUser['d_id'];
            $list = Db::name('cms_live')->where($where)->order('weight asc,id desc')->field('id,title,image,create_time,description,time,start,end')->limit($firstRow,$limit)->select()->toArray();
            //echo Db::name('cms_live')->getLastSql();exit;
        }
        foreach($list as &$vas){
                $vas['create_time'] = date('Y-m-d',$vas['create_time']);
        }
       
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }
    /**
     * 直播回放
     * 1公告2党建引领
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function hflive($category_id)
    {
        $page = !empty(input('page'))?input('page'):1;
        $limit = !empty(input('limit'))?input('limit'):9;
        $firstRow = $limit*($page-1);
        $where['status'] = 1;
        $where['category_id'] = $category_id;
        $starttime=date('Y-m-d',time());
        $starthour=date('H:i:s',time());
        //echo $starthour;exit;                                                        
        $list = Db::name('cms_live')
        ->where('time', '<', $starttime)
        //->where('start', '<=', $starthour)
        //->where('end', '>=', $starthour)
        ->where($where)->order('weight asc,id desc')->field('id,title,image,create_time,description,time,start,end')->limit($firstRow,$limit)->select()->toArray();
        //echo Db::name('cms_live')->getLastSql();exit;
        foreach($list as &$vas){
                $vas['create_time'] = date('Y-m-d',$vas['create_time']);
        }
       
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }

    /**
     * 详情
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function details($id)
    {   
        $where['id'] = ['eq',$id];
        $list = Db::name('cms_article')->where($where)->field('id,title,keywords,category_id,description,click,content,create_time')->find();
        $token = Request::instance()->header('token');
        if(empty($token)){
            $token = input('post.token');
        }
        if(!empty($token)){
            $wheres['token'] = $token;
            $uid = Db::name('user_token')->where($wheres)->value('u_id'); 
            //查询当天是否记录
            $info['u_id'] = $uid;
            $info['a_id'] = $id;
            $info=Db::name('user_article')->where($info)->whereDay('create_time')->find(); 
            if(empty($info)){
                //保存浏览记录 
                $data = [
                    'u_id' =>$uid,
                    'a_id' =>$list['id'],
                    'category_id' =>$list['category_id'],
                    'create_time' =>time(),
                ];
                $res = DB::name('user_article')->insert($data);
            }
            
        }
        $list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }

    /**
     * 直播
     * 1视频点播2党建引领
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function live($category_id)
    {
        if(empty($category_id)){
            $category_id = "";
        }
        $page = !empty(input('page'))?input('page'):1;
        $limit = !empty(input('limit'))?input('limit'):9;
        $firstRow = $limit*($page-1);
        $where['status'] = 1;
        $where['category_id'] = $category_id;
        $starttime=date('Y-m-d',time());
        $list = Db::name('cms_live')->where('time', '>', $starttime)->where($where)->order('weight asc,id desc')->field('id,title,image,create_time,description,time,start,end,address')->limit($firstRow,$limit)->select()->toArray();
        foreach($list as &$vas){
            $vas['create_time'] = date('Y-m-d',$vas['create_time']);
        }
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }

    /**
     * 详情
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function livedetails($id)
    {   
        $where['id'] = ['eq',$id];
        $list = Db::name('cms_live')->where($where)->field('id,title,address,category_id')->find();
        $token = Request::instance()->header('token');
        if(empty($token)){
            $token = input('post.token');
        }
        if(!empty($token)){
            //保存浏览记录
            $wheres['token'] =$token;
            $uid = Db::name('user_token')->where($wheres)->value('u_id');
            //查询当天是否记录
            $info['u_id'] = $uid;
            $info['l_id'] = $id;
            $info=Db::name('user_live')->where($info)->whereDay('create_time')->find(); 
            if(empty($info)){
                $data = [
                'u_id' =>$uid,
                'l_id' =>$list['id'],
                'category_id' =>$list['category_id'],
                'create_time' =>time(),
                ];
                $res = DB::name('user_live')->insert($data);
            }
            
        }
        //$list['create_time']=date('Y-m-d H:i:s',$list['create_time']);
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }

    /** @DESC [党建活动室开关] */
    public function getOpen()
    {
        if (request()->isPost()) {
            $oUser = $this->oUser;
            $sysconf = Config::where('type', 'dangyuan')->find()['value'];
            array_splice($sysconf, 2, 1);
            switch ($sysconf['is_open']) {
                case 1:
                    //查询部门id
                    $d_id = $oUser['d_id'];
                    $where['id'] =$d_id;
                    $list = Db::name('cms_department')->where($where)->field('dylogo as logo,dytitle as webname')->find();
                    if(empty($list['logo']||empty($list['webname']))){
                        //显示总配置数据
                        unset($sysconf['typename'],$sysconf['is_open']);
                        return json(['code'=>200,'msg'=>'让显示','data'=>$sysconf]);
                    }else{
                        return json(['code'=>200,'msg'=>'让显示','data'=>$list]);
                    }
                    break;
                
                case 0:
                    return json(['code'=>201,'msg'=>'不让显示','data'=>null]);
                    break;
            }
        }
    }

    /**
     * 导航
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function dynav()
    {
        $where['status'] = 1;
        $where['pid'] = 0;
        $oUser = $this->oUser;
        $d_id=$oUser['d_id'];
        //获取顶级栏目id
        $where['d_id'] =$d_id;
        $listtop = Db::name('cms_navs')->where($where)->whereIn('open_type','1,2')->order('weight asc')->field('id,urltype,url,name,image')->limit(8)->select()->toArray();
        foreach ($listtop as $key => &$value) {
            //查询子导航
            $son=Db::name('cms_navs')->whereIn('open_type','1,2')->where('pid',$value['id'])->find();
            if($son){
                switch ($value['urltype']) {
                    case 6:
                        $value['urltype']=6;
                        //unset($value['url']);
                        break;
                    
                    default:
                        $value['urltype']=100;
                        break;
                }
                
            }
            if($value['name']=="党建风采"){
               $value['urltype']=6; 
            }
            switch ($value['urltype']) {
                case 0:
                    unset($value['url']);
                    break;
                
                case 1:
                    unset($value['url']);
                    break;
                case 6:
                    unset($value['url']);
                    break;
                case 7:
                    unset($value['url']);
                    break;
                case 8:
                    unset($value['url']);
                    break;
                case 100:
                    unset($value['url']);
                    break;
            }
        }

        /*$list=[
            $list['top']=$listtop,
            $list['buttom']=$listbutt,
        ];*/
        return json(['code'=>200,'msg'=>'成功','data'=>$listtop]);
    }

     /**
     * 导航
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function dynavbuttom()
    {
        $where['status'] = 1;
        $where['pid'] = 0;
        $oUser = $this->oUser;
        $d_id=$oUser['d_id'];
        //获取顶级栏目id
        $where['d_id'] =$d_id;
        $listbutt = Db::name('cms_navs')->where($where)->whereIn('open_type','1,2')->order('weight asc')->field('id,urltype,url,name,image')->limit(8,20)->select()->toArray();
        foreach ($listbutt as $key => &$value) {
            //查询子导航
            $son=Db::name('cms_navs')->where('pid',$value['id'])->whereIn('open_type','1,2')->find();
            if($son){
                $value['urltype']=100;
            }
            switch ($value['urltype']) {
                case 0:
                    unset($value['url']);
                    break;
                
                case 1:
                    unset($value['url']);
                    break;
                case 6:
                    unset($value['url']);
                    break;
                case 7:
                    unset($value['url']);
                    break;
                case 8:
                    unset($value['url']);
                    break;
                case 100:
                    unset($value['url']);
                    break;
            }
        }
        return json(['code'=>200,'msg'=>'成功','data'=>$listbutt]);
    }


    /**
     * 导航
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function sonnav($nid)
    {
        $where['status'] = 1;
        $where['pid'] = $nid;
        $oUser = $this->oUser;
        $d_id=$oUser['d_id'];
        //获取顶级栏目id
        //$topid=$this->setTopid($d_id);
        $where['d_id'] =$d_id;
        $list = Db::name('cms_navs')->where($where)->whereIn('open_type','1,2')->order('weight asc')->field('id,urltype,url,name,image')->select()->toArray();
        foreach ($list as $key => &$value) {
            switch ($value['urltype']) {
                case 0:
                    unset($value['url']);
                    break;
                
                case 1:
                    unset($value['url']);
                    break;
                case 6:
                    unset($value['url']);
                    break;
                case 10:
                    unset($value['url']);
                    break;
            }
        }
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }

    /**
     * 党建轮播
     *
     * @param  \think\Request  $request
     * @return \think\Response
     * @return $type--分类id
     */
    public function dybanner($type)
    {
        $where['status'] = 1;
        $where['type'] = ['eq',$type];
        $oUser = $this->oUser;
        $d_id=$oUser['d_id'];
        //获取顶级栏目id
        //$topid=$this->setTopid($d_id);
        $where['d_id'] =$d_id;
        $list = Db::name('cms_aduser')->where($where)->order('weight asc')->field('id,title,url,image')->select();
        if($list->isEmpty()){
            $list = Db::name('cms_ads')->where(['status'=>1,'type'=>$type])->order('weight asc')->field('id,title,url,image')->select();
        }
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }

     /**
     * 党建相册
     *
     * @param  \think\Request  $request
     * @return \think\Response
     * @return $type--分类id
     */
    public function dyimages($nid)
    {
        $where['status'] = 1;
        $where['category_id'] = $nid;
        $oUser = $this->oUser;
        $list = Db::name('cms_images')->where($where)->order('weight asc')->field('id,title,photos')->select()->toArray();
        if($list){
            foreach ($list as $key => &$value) {
                $value['photos']=self::getFieldAttr($value['photos']);
                $value['number']=count($value['photos']);
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }else{
            return json(['code'=>200,'msg'=>'没有数据','data'=>$list]);
        }
        
    }

    /**
     * 文章
     * 1公告2党建引领
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    /*public function dyarticle($category_id,$urltype)
    {
        $page = !empty(input('page'))?input('page'):1;
        $limit = !empty(input('limit'))?input('limit'):9;
        $firstRow = $limit*($page-1);
        $where['status'] = 1;
        $where['category_id'] = $category_id;
        $starthour=date('H:i:s',time());
        if($urltype==0){         
            $list = Db::name('cms_article')
            ->where($where)->order('weight asc,id desc')->field('id,title,image,create_time,description')->limit($firstRow,$limit)->select()->toArray();     
            foreach($list as &$vas){
                $vas['create_time'] = date('Y-m-d',$vas['create_time']);
            }
        }elseif($urltype==3){
             $list = Db::name('cms_nav')->where('id',$category_id)->field('id,url')->find();
        }elseif($urltype==4){
             $list = Db::name('cms_nav')->where('id',$category_id)->field('id,url')->find();
        }else{
            $oUser = $this->oUser;
            $where['d_id']=$oUser['d_id'];
            $list = Db::name('cms_live')
            ->whereTime('jieshu', '>=', $starthour)
            ->where($where)->order('weight asc,id desc')->field('id,title,image,create_time,description,time,start,end')->limit($firstRow,$limit)->select()->toArray();
            foreach($list as &$vas){
                $vas['create_time'] = date('Y-m-d',$vas['create_time']);
            }
        }
       
        return json(['code'=>200,'msg'=>'成功','data'=>$list]);
    }*/
    public function getList($nid,$urltype)
    {
        if (request()->isPost()) {
            $oUser = $this->oUser;
            $page = !empty(input('page'))?input('page'):1;
            $limit = !empty(input('limit'))?input('limit'):9;
            $firstRow = $limit*($page-1);
            $where['status'] = 1;
            $where['category_id'] = $nid;
            $starthour=date('H:i:s',time());
            switch ($urltype) {
                case 0:
                    $list = Db::name('cms_articledy')
                    ->where($where)->order('weight asc,id desc')->field('id,title,image,create_time,description')->limit($firstRow,$limit)->select()->toArray();     
                    foreach($list as &$vas){
                        $vas['create_time'] = date('Y-m-d',$vas['create_time']);
                    }
                    break;
                
                case 1:
                    //$where['d_id']=$oUser['d_id'];
                    $list = Db::name('cms_livedy')
                    ->whereTime('jieshu', '>=', $starthour)
                    ->where($where)->order('weight asc,id desc')->field('id,title,image,create_time,description,time,start,end')->limit($firstRow,$limit)->select()->toArray();
                    foreach($list as &$vas){
                        $vas['create_time'] = date('Y-m-d',$vas['create_time']);
                    }
                    break;
                case 6:
                    $list = Db::name('cms_images')->where($where)->order('weight asc')->field('id,title,photos,audio')->limit($firstRow,$limit)->select()->toArray();
                    if($list){
                        foreach ($list as $key => &$value) {
                            $value['photos']=self::getFieldAttr($value['photos']);
                            $value['number']=count($value['photos']);
                        }
                    }
                    break;

                case 7:
                    //查询台账目录
                    $wheres['d_id']=$oUser['d_id'];
                    $wheres['status']=1;
                    $list = Db::name('cms_adml')
                    ->where($wheres)->order('weight desc')->field('id,title')->select()->toArray();     
                    /*foreach($list as &$vas){
                        $vas['create_time'] = date('Y-m-d',$vas['create_time']);
                    }*/
                    break;
                    
                case 8:
                    $list = Db::name('cms_meet')->where('d_id', $oUser['d_id'])->where('status', 1)->order('id desc')->field(
                        'id,title,start,end,type,qrcode,address,meetstatus,kaishi,jieshu,is_live,anchor_uid,stream_name,push_url,playback_url,description'
                    )->select()->toArray();
                    if($list){
                        foreach ($list as $key => &$value) {
                            if(time()>$value['jieshu']){
                                $value['meetstatus']=2;
                            }else if(time()>$value['kaishi']&&time()<$value['jieshu']){
                                $value['meetstatus']=1;
                            }else if(time()<$value['kaishi']){
                                $value['meetstatus']=0;
                            }
                            $url=$value['playback_url'];
                            $value['playback_rtmp_url'] ='rtmps://'.$url;
                            $value['playback_hls_url'] ='https://'.$url.'/playlist.m3u8';
                            $value['playback_url'] = 'https://'.$url.'.flv';
                            //$value['stream_name'] .= '_vertical';
                            $value['type_name']=Db::name('dict_data')->where('id',$value['type'])->value('name');
                            unset($value['type'],$value['kaishi'],$value['jieshu']);
                        }
                    }
                    break;
                case 10:
                    Log::write($where,'条件');
                    $list = Db::name('cms_dangyuan')->where($where)->order('id desc')->field('id,title,image,description')->select()->toArray();
                    break;
                case 12:
                    $list = list_to_trees(CmsCategory::where(['status'=>1,'d_id'=>$oUser['d_id']])->order(['weight','id'])->field('id,pid,topid,name')->select()->toArray());
                    $content=replacePicUrl(Db::name('cms_navs')->where('id',$nid)->value('content'),Request::domain());
                    return json(['code'=>200,'msg'=>'成功','data'=>['right'=>$list,'left'=>$content]]);
                    break;
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }
    }

    //台账详情
    public function tzinfo()
    {
        if (request()->isPost()) {
            $data = $this->request->post();
            $page = !empty(input('page'))?input('page'):1;
            $limit = !empty(input('limit'))?input('limit'):9;
            $firstRow = $limit*($page-1);
            $wheres['status']=1;
            $year=[];
            $month=[];
            if(isset($data['ml_id']) && !empty($data['ml_id'])){
                $wheres['ml']=$data['ml_id'];
                $year = Db::name('cms_articletz')
                    ->where($wheres)->order('year asc')->field('year')->distinct(true)->order('year asc')->select()->toArray();
                foreach ($year as $key => $value) {
                    $year[$key]['month']=arrToOne(Db::name('cms_articletz')->where(['ml'=>$data['ml_id'],'year'=>$value['year']])->order('month desc')->field('month')->distinct(true)->select()->toArray());
                }
                //$year = arrToOne($year);   
            }
            /*if(isset($data['year']) && !empty($data['year'])){
                $wheres['ml']=$data['ml_id'];
                $wheres['year']=$data['year'];
                
                //$month = Db::name('cms_articletz')->where($wheres)->order('month asc')->field('month')->distinct(true)->select()->toArray();   
                  
            }*/
            if(isset($data['month']) && !empty($data['month'])){
                $wheres['month']=$data['month'];   
            }
            $listinfo = Db::name('cms_articletz')->where($wheres)->order('id asc')->field('id,title,fileurl')->order('weight desc')->select()->toArray();
            return json(['code'=>200,'msg'=>'成功','data'=>['year'=>$year,'list'=>$listinfo]]);
        }
    }

    //相册详情
    public function imagesList($id)
    {
        if (request()->isPost()) {
            $list = Db::name('cms_images')->where('id',$id)->field('id,title,photos,audio')->find();
            if($list){ 
                $list['photos']=self::getFieldAttr($list['photos']);
                $list['number']=count($list['photos']);    
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }
    }


    public function getMeetList($m_id)
    {
        if (request()->isPost()) {
            $oUser = $this->oUser;
            $d_id=$oUser['d_id'];
            $list = Db::name('meetinfo')->where('m_id',$m_id)->withoutField('admin_id,create_time,update_time,status,m_id,anonymous')->order('weight asc')->select()->toArray();
            foreach ($list as $key => &$value) {
                switch ($value['urltype']) {
                    case 1:
                        //$value['typename']='文字';
                        unset($value['vote_options'],$value['name'],$value['peoplefiles'],$value['radio'],$value['start'],$value['end'],$value['signtype']);
                        break;
                    
                    case 2:
                        //$value['typename']='自我批评';
                        $name=self::getFieldAttr($value['name']);
                        $peoplefiles=self::getFieldAttr($value['peoplefiles']);
                        $keys = array('name','peoplefiles');

                        $value['criticism_list']=array_merge_more($keys, $name, $peoplefiles);
                        unset($value['vote_options'],$value['name'],$value['peoplefiles'],$value['content'],$value['fileurl'],$value['filedescription'],$value['radio'],$value['start'],$value['end'],$value['signtype']);
                        break;

                    case 3:
                        //$value['typename']='投票';
                        //查询会议人员id
                        $assistant_uid=Db::name('cms_meet')->where('id',$m_id)->value('assistant_uid');
                        $value['total']=0;
                        if($assistant_uid){
                            //统计个数
                            $value['total']=count(self::getFieldAttr($assistant_uid));
                        }
                        //查询已投票人数
                        $value['isvote_number']=Db::name('voteinfo')->where('meetinfo_id',$value['id'])->count();
                        if($value['radio']){
                            $arr=self::getFieldAttr($value['radio']);
                            //获取各选项投票数
                            foreach ($arr as $keys => $values) {
                                $arrnumber[$keys]=Db::name('voteinfo')->where('meetinfo_id',$value['id'])->where('value',$arr[$keys])->count();
                            }
                            $as= array_filter($arrnumber);
                            
                            if(!empty($as)){
                                $a = $arrnumber;
                                $total = array_sum($a);
                                array_walk($a, function(&$item, $key, $prefix) 
                                    { if ($prefix != 0) {
                                       $item = round($item*100/$prefix); 
                                    }
                                        
                                    }, 
                                    $total
                                );
                                if($d = (100 - array_sum($a))) $a[rand(0, count($a)-1)] += $d;
                                //组合成数组返回给前端
                                $keys = array('radio','number','bai');
                                $value['vote_list']=array_merge_more($keys, $arr, $arrnumber,$a);
                            }else{
                                //组合成数组返回给前端
                                $keys = array('radio','number','bai');
                                $value['vote_list']=array_merge_more($keys, $arr, $arrnumber,$arrnumber);
                            }
                        } 
                        //未投票人数
                        $value['novote_number']=0;
                        if($value['total']!=0){
                            $value['novote_number']=$value['total']-$value['isvote_number'];
                        }
                        //print_r($assistant_uid);exit;
                        //查询部门名称
                        $value['department_name']=Db::name('cms_department')->where('id',$d_id)->value('name');
                        unset($value['vote_options'],$value['name'],$value['peoplefiles'],$value['fileurl'],$value['filedescription'],$value['signtype']);
                        break;

                    case 4:
                        //$value['typename']='签到';
                        //查询签到人数
                        $value['sign_number']=Db::name('signinfo')->where('meetinfo_id',$value['id'])->count();
                        //查询本地签到人数
                        $value['locu_number']=Db::name('signinfo')->where('meetinfo_id',$value['id'])->where('signtype',1)->count();
                        //查询远程签到人数
                        $value['remote_number']=Db::name('signinfo')->where('meetinfo_id',$value['id'])->where('signtype',2)->count();
                        //查询当前流程签到信息
                        $signinfo = Db::name('signinfo')
                            ->alias('s')
                            ->join('user u','s.u_id = u.id')
                            //->where('to_uid',$oUser['id'])
                            ->where('s.meetinfo_id',$value['id'])->Field('u.nickname,s.selfie,s.sign,s.signtype,s.create_time')->order('s.id desc')->select()->toArray();
                            foreach ($signinfo as $k => &$v) {
                                $v['selfie'] = str_replace("/http","http",$v['selfie']);
                                $v['create_time'] = date('Y-m-d H:i',$v['create_time']);
                                switch ($v['signtype']) {
                                    case 1:
                                        $v['signtype']='本地';
                                        break;
                                    
                                    default:
                                        //$v['selfie']=Request::domain().$v['selfie'];
                                        $v['signtype']='远程';
                                        break;
                                }
                            }
                            $value['sign_list']=$signinfo;
                        unset($value['vote_options'],$value['content'],$value['title'],$value['content'],$value['name'],$value['peoplefiles'],$value['radio'],$value['fileurl'],$value['filedescription'],$value['start'],$value['end']);
                        break;
                    case 5:
                        //查询会议人员id
                        $assistant_uid=Db::name('cms_meet')->where('id',$m_id)->value('assistant_uid');
                        $value['total']=0;
                        if($assistant_uid){
                            //统计个数
                            $value['total']=count(self::getFieldAttr($assistant_uid));
                        }
                        //查询已投票人数
                        $value['isvote_number']=0;
                        $value['isvote_number']=Db::name('ballotinfo')->where('meetinfo_id',$value['id'])->count("distinct u_id");
                        /*if($value['vote_options']){
                            $value['vote_options']=json_decode($value['vote_options'],true);
                            foreach ($value['vote_options'] as $key => &$val) {
                                foreach ($val['options'] as $keys => &$values) {
                                    $values['number']=Db::name('ballotinfo')->where(['orderNumber'=>$values['orderNumber'],'ballothemo'=>$val['theme'],'meetinfo_id'=>$value['id']])->count()?:0; 
                                    $values['bai']=0;
                                    if ($values['number'] != 0) {
                                       $values['bai']=round($values['number']/$value['isvote_number']*100)?:0;
                                    }
                                }
                            }
                        }*/ 
                        //未投票人数
                        $value['novote_number']=0;
                        if($value['total']!=0){
                            $value['novote_number']=$value['total']-$value['isvote_number'];
                        }
                        //print_r($assistant_uid);exit;
                        //查询部门名称
                        $value['department_name']=Db::name('cms_department')->where('id',$d_id)->value('name');
                        unset($value['name'],$value['peoplefiles'],$value['fileurl'],$value['filedescription'],$value['signtype']);
                        break;
                }
                unset($value['kaishi'],$value['content'],$value['jieshu'],$value['radio'],$value['signtype'],$value['choose']);
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }
    }

    /*表决类型事项列表*/
    public function vote_options($id)
    {
        $value = Db::name('meetinfo')->where('id',$id)->where('urltype',5)->withoutField('admin_id,create_time,update_time,status,m_id,anonymous')->find();
        if($value){
            //查询已投票人数
            $value['isvote_number']=0;
            $value['isvote_number']=Db::name('ballotinfo')->where('meetinfo_id',$value['id'])->count("distinct u_id");
            if($value['vote_options']){
                $value['vote_options']=json_decode($value['vote_options'],true);
                foreach ($value['vote_options'] as $key => &$val) {
                    foreach ($val['options'] as $keys => &$values) {
                        $values['number']=Db::name('ballotinfo')->where(['orderNumber'=>$values['orderNumber'],'ballothemo'=>$val['theme'],'meetinfo_id'=>$value['id']])->count()?:0; 
                        $values['bai']=0;
                        if ($values['number'] != 0) {
                           $values['bai']=round($values['number']/$value['isvote_number']*100)?:0;
                        }
                    }
                }
                return json(['code'=>200,'msg'=>'成功','data'=>$value['vote_options']]);
            }
        }
        
        return json(['code'=>200,'msg'=>'成功','data'=>null]);
        
    }

    /** @DESC [二级导航（党建之窗）-列表数据] */
    public function getSonInfo($nid)
    {
        if (request()->isPost()) {
            $where['status'] = 1;
            //只展示文章类型
            $where['urltype'] = 0;
            $where['pid'] = $nid;
            $oUser = $this->oUser;
            $d_id=$oUser['d_id'];
            //获取顶级栏目id
            //$topid=$this->setTopid($d_id);
            $where['d_id'] =$d_id;
            $list = Db::name('cms_navs')->where($where)->whereIn('open_type','1,2')->order('weight asc')->field('id,name,weight')->limit(4)->select()->toArray();
            foreach ($list as $key => &$value) {
                switch ($value['weight']) {
                    case 1:
                        $value['article']=Db::name('cms_articledy')->where('category_id',$value['id'])->field('id,title')->limit(6)->select()->order('update_time desc')->toArray();
                        break;
                    
                    case 2:
                        $value['article']=Db::name('cms_articledy')->where('category_id',$value['id'])->field('id,title,description')->limit(3)->select()->order('update_time desc')->toArray();
                        break;
                    case 3:
                        $value['article']=Db::name('cms_articledy')->where('category_id',$value['id'])->field('id,title')->limit(6)->select()->order('update_time desc')->toArray();
                        break;
                    case 4:
                        $value['article']=Db::name('cms_articledy')->where('category_id',$value['id'])->field('id,title,image')->limit(5)->select()->order('update_time desc')->toArray();
                        break;
                }
            }
            return json(['code'=>200,'msg'=>'成功','data'=>$list]);
        }
    }

    //机构列表
    public function departmentList()
    {
        if (request()->isPost()) {
            $data = input('param.');
            $where['status'] = 1;
            $page = !empty(input('page'))?input('page'):1;
            $limit = !empty(input('limit'))?input('limit'):9;
            $list = Db::name('cms_department');
            $firstRow = $limit*($page-1);
            if ($data['name'] != '') {
                $list = $list->whereLike('name', '%' . $data['name'] . '%');
            }
            $list = $list->where($where)->order('weight asc')->field('id,name')->limit($firstRow,$limit)->select()->toArray();
            if ($list==true) {
                return json(['code'=>200,'msg'=>'成功','data'=>$list]);
            }else{
                return json(['code'=>200,'msg'=>'没有','data'=>null]);
            }
        }
    }
    //多图获取
    public function getFieldAttr($value)
    {
        if(!$value){
            $list = array();
        }else{
            $list = explode(',', $value);
        }
        return $list;
    }

}
