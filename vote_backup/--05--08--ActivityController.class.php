<?php
namespace Home\Controller;
use Think\Controller;
class ActivityController extends Controller {
    public function index(){
      echo 'activity--message';
	  return;
    }

    public function UserCreate(){
        $parames = $_POST;
        //var_dump($parames);die;
        if (!$parames) {
            $parames = $_GET;
        }
        if(!$parames){
            $parames = file_get_contents("php://input");
            $parames = json_decode($parames,true);
            //$parames['information'] = json_decode($parames['information']);    
        }

    	$db_user = M('user_info');
    	$db_activity = M('activity');
    	$db_activity_user = M('activity_user');

    	$user['unionId'] = $parames['unionId'];
    	$user['avatarurl'] = $parames['avatarUrl'];
    	$user['nickname'] = $parames['nickName'];
    	$user['gender'] = intval($parames['gender']);
    	$user['city'] = $parames['city'];
    	$user['province'] = $parames['province'];
    	$user['country'] = $parames['country'];
    	$user['openId'] = $parames['openId'];
    	$user['date'] = date('Y-m-d');
    	$is_new = 1;
    	$data_where['unionId'] = $parames['unionId'];
    	$new = $db_user->where($data_where)->find();       
    	if($new){
    		$res = $db_user->where($new['unionId'])->save($user);
    	}else{
    		$uid = $db_user->add($user);
    	}      
    	if (empty($uid) || !$uid) {
    		$uid = $new['id'];
    	}
    	$created = $db_activity->where('uid=%d',$uid)->select();
    	$involved = $db_activity_user->where('uid=%d',$uid)->select();

    	if($created || $involved){
    		$is_new = 0;
    	}

    	$res_data['uid'] = $uid;
    	$res_data['is_new'] = $is_new;

    	$result = array(
    			'ret'=>1,
    			'msg'=>'OK',
    			'data'=>$res_data
    		);
    	echo json_encode($result);die;
    }


    public function CreatedList(){
    	$db_activity = M('activity');
    	$uid = intval(I('uid'));
    	$limit = intval(I('limit'));
    	if(!$limit || $limit<0){
    		$limit = 5;
    	}
    	if ($uid && $uid>0) {
            $sele_data['uid'] = $uid;
            $sele_data['del_status'] = 0;
    		$createdlist = $db_activity->where($sele_data)->order('addtime desc')->field('id,activity_name,start_time,end_time,site,poster,del_status')->select();	
    	}
        //var_dump($createdlist);die;
    	if(is_array($createdlist) && count($createdlist)>0){
            foreach ($createdlist as $key => $value) {
                $createdlist[$key]['start_time'] = date('Y-m-d H:i',$value['start_time']);
                $createdlist[$key]['end_time'] = date('Y-m-d H:i',$value['end_time']);
error_log("end_time+++++log+++++++++".$value['end_time']);
                if($value['end_time']>=time()){
                    $createdlist[$key]['status'] = 1;
                }else{
                    $createdlist[$key]['status'] = 2;
                }

                if(($value['del_status'] ==1) || ($value['del_status']==2)){
                	$CreatedList[$key]['status'] = 3;
                }
            }


	    	$data['ret'] = 1;
	    	$data['msg'] = 'ok';
	    	$data['data'] = $createdlist;	
    	}else{
    		$data['ret'] = -1;
	    	$data['msg'] = 'failed';
	    	$data['data'] = '';
    	}

    	echo json_encode($data);die;
    }

    public function InvolvedList(){
    	$db_activity = M('activity');
    	$db_activity_user = M('activity_user');

    	$uid = intval(I('uid'));
    	$limit = intval(I('limit'));
    	if(!$limit || $limit<0){
    		$limit = 5;
    	}
    	if ($uid && $uid>0) {
            //$data_where['uid'] = $uid;
            //ß$data_where['is_involved'] = 1;
    		//$list = $db_activity_user->join('activity ON activity.id = activity_user.aid')->where('activity_user.uid=%d and is_involved=1',$uid)->order('activity_user.addtime desc')->limit($limit)->select();
            $where_data['uid'] = $uid;
            $where_data['is_involved'] =1;
            $where_data['extra1'] = array('exp','is null');
            $list = $db_activity_user->where($where_data)->order('addtime desc')->select();
            error_log("involved_list_log++++++++++++".json_encode($list));
    	}
//var_dump($list);die;
    	if(is_array($list) && count($list)>0){
            foreach ($list as $key => $value) {
                $invo_list[$key] = $db_activity->where('id=%d',$value['aid'])->field('id,activity_name,start_time,end_time,site,poster,del_status')->find();

                if($invo_list[$key]['end_time']>=time()){
                    $invo_list[$key]['status'] = 1;
                }else{
                    $invo_list[$key]['status'] = 2;
                }

                if(($value['del_status'] ==1) || ($value['del_status']==2)){
                	$invo_list[$key]['status'] = 3;
                }

                error_log("involved_list++++++end_time+++++".$invo_list[$key]['end_time']);    
                
                $invo_list[$key]['start_time'] = date('Y-m-d H:i',$invo_list[$key]['start_time']);
                $invo_list[$key]['end_time'] = date('Y-m-d H:i',$invo_list[$key]['end_time']);            
                
            }

	    	$data['ret'] = 1;
	    	$data['msg'] = 'ok';
	    	$data['data'] = $invo_list;	
    	}else{
    		$data['ret'] = -1;
	    	$data['msg'] = 'failed';
	    	$data['data'] = '';
    	}

    	echo json_encode($data);die;
    }

    //参与者删除活动列表
    public function Delete(){
    	$db_activity_user = M('activity_user');
        $db_activity = M('activity');

    	//参与者删除活动列表
    	$uid = intval(I('uid'));
    	$aid = intval(I('aid'));
    	if($uid && $uid>0){
    		$activityinfo = $db_activity_user->where('aid=%d',$aid)->select();
            //var_dump($activityinfo);
    		if (is_array($activityinfo)&&count($activityinfo)>0) {
    			$datas['del_status'] = 1;
    			$rs = $db_activity->where('id=%d',$aid)->save($datas);

                //var_dump($rs);
    		}else{
                
                $res = $db_activity->where("id=%d",$aid)->delete();
               
            }
    	}
    	if ($rs || $res) {
    		$data['ret'] = 1;
	    	$data['msg'] = 'ok';
	    	$data['data'] = $rs;
    	}else{
    		$data['ret'] = -1;
	    	$data['msg'] = 'failed';
	    	$data['data'] = '';
    	}
    	echo json_encode($data);die;
    }

    //创建者删除活动列表
    public function OwnerDelete(){
    	$db_activity = M('activity');

    	$uid = intval(I('uid'));
    	$aid = intval(I('aid'));
    	if($uid && $uid>0){
    		$activityinfo = $db_activity->where('uid=%d and aid=%d',array($uid,$aid))->select();
    		if (is_array($activityinfo)&&count($activityinfo)>0) {
    			$data['del_status'] = 1;
    			$rs = $db_activity->where('uid=%d and aid=%d',array($uid,$aid))->save($data);
    		}
    	}

    	if ($rs) {
    		$data['ret'] = 1;
	    	$data['msg'] = 'ok';
	    	$data['data'] = $rs;
    	}else{
    		$data['ret'] = -1;
	    	$data['msg'] = 'failed';
	    	$data['data'] = '';
    	}
    	echo json_encode($data);die;
    }


    public function ActivityType(){
    	$db_activity_type = M('activity_type');

    	$typelist = $db_activity_type->where('activity_type=%d',1)->select();

    	if (is_array($typelist)&&count($typelist)>0) {
    		foreach ($typelist as $key => $value) {
    			$k = $key + 1;
    			$list[$k] = $value['type_name'];
    		}
    	}
    	if ($list) {
    		$result_data['ret'] = 1;
	    	$result_data['msg'] = 'ok';
	    	$result_data['data'] = $list;
    	}else{
    		$result_data['ret'] = -1;
	    	$result_data['msg'] = 'failed';
	    	$result_data['data'] = '';
    	}
    	echo json_encode($result_data);die;
    }

    public function Create(){
        $parames = file_get_contents("php://input");
        $parames = json_decode($parames,true); 
        
        if(!$parames){
            $parames = $_GET;
        }
        if (!$parames) {
            $parames = $_POST;
        }
    	
        $db_activity = M('activity');
    	$db_activity_attach = M('activity_attach');
        $db_activity_user = M('activity_user');
        $db_activity_userinfo = M('activity_userinfo');
        
//var_dump($parames);die;        
    	$activity['type'] = $parames['type'];
    	$activity['activity_name'] = $parames['activity_name'];
    	$activity['start_time'] = $parames['start_time'];
    	$activity['end_time'] = $parames['end_time'];
    	if(!$activity['end_time']){
    		$activity['end_time'] = strtotime ("+30 days");
    	}
	$activity['poster'] = $parames['poster'];
    	$activity['site'] = $parames['site'];
    	$activity['sponsor'] = $parames['sponsor'];
    	$activity['description'] = $parames['description'];
    	$activity['date'] = date('Y-m-d');
    	$activity['uid'] = $parames['uid'];        
error_log("create_____log________".json_encode($activity));
    	$aid = $db_activity->add($activity);

    	if ($aid>0) {

            $activity_user_data['aid'] = $aid;
            $activity_user_data['uid'] = $parames['uid'];
            $activity_user_data['is_involved']=1;
            $activity_user_data['join_time']=date();
            $activity_user_data['extra1'] = 1;//只增家数量，不在我参与的列表显示 
            $activity_user_data['date'] = date('Y-m-d');
            $resu = $db_activity_user->add($activity_user_data);

            $ac_user['uid'] = $parames['uid'];
            $ac_user['aid'] = $aid;
           // $ac_user['username'] = $parames['nickName'];

            $ks = $db_activity_userinfo->add($ac_user); 


    		$attach_info = $parames['information'];
            if(!is_array($attach_info)){
                $attach_info = json_decode($attach_info,true);
            }
            //var_dump($attach_info);die;
error_log("message-------------".json_encode($attach_info));
    		if(is_array($attach_info) && count($attach_info)>0){
    			$attach_info['aid'] = $aid;
                $attach_info['date'] = date('Y-m-d');
error_log("create_____log________".json_encode($attach_info));                
    			$rs = $db_activity_attach->add($attach_info);
    		}
    	}

    	if ($aid>0 || $rs) {
    		$data['ret'] = 1;
	    	$data['msg'] = 'ok';
	    	$data['data'] = array('aid'=>$aid);
    	}else{
    		$data['ret'] = -1;
	    	$data['msg'] = 'failed';
	    	$data['data'] = '';
    	}

    	echo json_encode($data);die;
    }


    public function ActivityInfo(){
    	$db_activity = M('activity');
    	$db_activity_user = M('activity_user');
        $db_activity_attach = M('activity_attach');
        $db_activity_user_info=M('user_info');

        $parames = $_POST;
        if (!$parames) {
            $parames = $_GET;
        }

    	$in_data['aid'] = $parames['aid'];
    	$in_data['uid'] = $parames['uid'];
        $in_data['is_involved'] = 1;

        $aid = $in_data['aid'];
        $uid = $in_data['uid'];

    	//判断用户是否参与该活动
    	$involve_info = $db_activity_user->where($in_data)->order('addtime desc')->find();
    	if ($involve_info) {
    		$is_involved = 1;
    	}else{
    		$is_involved = 2;
    	}
        //新加读取参与活动用户信息---2017/5/6------start----------
        $users['aid'] = $parames['aid'];
        $user_s = $db_activity_user->where($users)->order('id asc')->group('uid')->limit(5)->select();
        $user_arr = array();
        if($user_s){
            foreach ($user_s as $k => $v) {
                $user_uid['id'] = $v['uid'];
                $per_user = $db_activity_user_info->where($user_uid)->find();
                if($per_user){
                    $user_arr[$k] = $per_user['avatarurl'];
                }
            }
        }
        $data['avatarurl'] = $user_arr;
        //新加读取参与活动用户信息---2017/5/6------start----------

    	if($aid>0){
            $db_aid['id'] = $aid;
    		$ainfo = $db_activity->where($db_aid)->find();
    		if(is_array($ainfo) && count($ainfo)>0){
    			$data['aid'] = $ainfo['id'];
    			$data['user_id'] = $ainfo['uid'];
                $data['uid'] = $parames['uid'];
    			$data['poster'] = $ainfo['poster'];
    			$data['type'] = $ainfo['type'];
    			$data['activity_name'] = $ainfo['activity_name'];
    			$data['start_time'] = date('Y-m-d H:i',$ainfo['start_time']);
    			$data['end_time'] = date('Y-m-d H:i',$ainfo['end_time']);
    			$data['site'] = $ainfo['site'];
    			$data['sponsor'] = $ainfo['sponsor'];
    			$data['description'] = $ainfo['description'];
                $data['del_status'] = $ainfo['del_status'];
    			if($ainfo['end_time']>=time()){
    				$data['status'] = 1;
    			}else{
    				$data['status'] = 2;
    			}
    			$data['is_involved'] = $is_involved;

    			if ((time() > $ainfo['start_time'])&&($involve_info['is_involved']==2)) {
    				$data['is_join'] = 2;
    			}else{
    				$data['is_join'] = 1;
    			}

    			//参加人数
    			$join_num_arr['aid'] = $in_data['aid'];
    			$join_num_arr['is_involved'] =1;
    			$data['involved_num'] = $db_activity_user->where($join_num_arr)->count('distinct(uid)');
    			//$data['involved_num'] = count($join_num_arr);

/*
                $information = $_POST['information'] ? $_POST['information'] : $_GET['information'] ;
                if (is_array($information) && count($information)) {
                    $information = json_decode($information,true);
                    foreach ($information as $key => $value) {
                        $data['information'][] = array('key'=>$key,'value'=>$value);
                    }
                }
*/

                $attachinfo = $db_activity_attach->where('aid=%d',$aid)->find();
                if(is_array($attachinfo) && count($attachinfo)>0){
                    unset($attachinfo['date']);
                    unset($attachinfo['addtime']);
                    unset($attachinfo['id']);
                    unset($attachinfo['aid']);
                    foreach ($attachinfo as $key => $value) {
                        if($value){
                            $data['information'][] = array('key'=>$key,'value'=>$value);
                        }
                    }
                }


    		}
    	}
    	if ($ainfo) {
    		$result_data['ret'] = 1;
	    	$result_data['msg'] = 'ok';
	    	$result_data['data'] = $data;
    	}else{
    		$result_data['ret'] = -1;
	    	$result_data['msg'] = 'failed';
	    	$result_data['data'] = '';
    	}

    	echo json_encode($result_data);die;
    }

    public function EditActivity(){
        $parames = $_POST;
        if (!$parames) {
            $parames = $_GET;
        }
        error_log("message+++EditActivity++++++".json_encode($_POST));
        error_log("message+++EditActivity++++++".json_encode($_GET));

        if(!$parames){
            $parames = file_get_contents("php://input");
            $parames = json_decode($parames,true);  
        }
    	
        error_log("file_get_contents_log++++++++++++".json_encode($parames));

    	$db_activity=M('activity');
    	$db_activity_attach=M('activity_attach');
        $db_activity_userinfo=M('activity_userinfo');

    	$activity_data['id'] = $parames['aid'];
    	$activity['type'] = $parames['type'];
    	$activity['activity_name'] = $parames['activity_name'];
    	$activity['start_time'] = $parames['start_time'];
    	$activity['end_time'] = $parames['end_time'];
    	if(!$activity['end_time']){
    		$activity['end_time'] = strtotime ("+30 days");
    	}
    	$activity['site'] = $parames['site'];
    	$activity['sponsor'] = $parames['sponsor'];
    	$activity['description'] = $parames['description'];
        $activity['poster'] = $parames['poster'];
    	$activity['date'] = date('Y-m-d');
    	$information = $parames['information'];
        //var_dump($information);die;
        if (!$information) {
            $information = $parames['information'];
        }
    	$res = $db_activity->where($activity_data)->save($activity);

    	//if ($res) {
            $attach_exist_data['aid'] = $parames['aid'];
            $attach_exist = $db_activity_attach->where($attach_exist_data)->find();

            $attach_info = $parames['information'];

            if (is_array($attach_exist) && count($attach_exist)>0 ) {
                
                if(is_array($attach_info) && count($attach_info)>0){
                    
                    $attach['aid'] = $parames['aid'];
                    foreach ($attach_info as $key_s => $value_s) {
                        unset($attach_info['id']);
                        unset($attach_info['uid']);
                        if($value_s){
                            $attach_info[$key_s] = $value_s;
                        }
                    }

                    $rs = $db_activity_attach->where($attach)->save($attach_info);
                }else{
                    $attach['aid'] = $parames['aid'];
                    $rs = $db_activity_attach->where($attach)->delete();
                }
            }else{
                $attach_info['aid'] = $parames['aid'];
                $rs = $db_activity_attach->add($attach_info);
            }
    	//}
        error_log("res &&&& rs ++++++++++log++++++++++++".$rs."+++++".$res);
    	if(true){
    		//编辑成功
    		$activity_info = $db_activity->where('id=%d',$parames['aid'])->find();
    		if (is_array($activity_info)&&count($activity_info)>0) {
    			$data_return['aid'] = $activity_info['id'];
    			$data_return['poster'] = $activity_info['poster'];
    			$data_return['type'] = $activity_info['type'];
    			$data_return['activity_name'] = $activity_info['activity_name'];
    			$data_return['start_time'] = date('Y-m-d H:i',$activity_info['start_time']);
    			$data_return['end_time'] = date('Y-m-d H:i',$activity_info['start_time']);
    			$data_return['site'] = $activity_info['site'];
    			$data_return['sponsor'] = $activity_info['sponsor'];
    			$data_return['description'] = $activity_info['description'];
    			if($activity_info['end_time']>=time()){
    				$data_return['status'] = 1;
    			}else{
    				$data_return['status'] = 2;
    			}
                $attachs = $db_activity_attach->where('aid=%d',$parames['aid'])->find();
                if($attachs){
                    unset($attachs['date']);
                    unset($attachs['addtime']);
                    unset($attachs['id']);
                    unset($attachs['aid']);
                    foreach ($attachs as $key => $value) {
                        if($value){
                            $data_return['information'][] = array('key'=>$key,'value'=>$value);
                        }
                    }
                }
    		}

    		$data['ret'] = 1;
	    	$data['msg'] = 'ok';
	    	$data['data'] = $data_return;
    	}else{
    		$data['ret'] = -1;
	    	$data['msg'] = 'failed';
	    	$data['data'] = '';
    	}
    	echo json_encode($data);die;
    }


    public function Uninvolvement(){
    	$db_activity_user=M('activity_user');
        $db_activity_userinfo=M('activity_userinfo');
    	$db_activity=M('activity');

        $params = $_POST;
        if (!$params) {
            $params = $_GET;
        }

    	$data['aid']=$params['aid'];
    	$data['uid']=$params['uid'];
    	$data_save['is_involved'] = 2;

        $del_rs = $db_activity_userinfo->where($data)->delete();
    	$res = $db_activity_user->where($data)->save($data_save);

    	if ($res) {
    		$where_data['aid'] = $data['aid'];
    		$where_data['uid'] = $data['uid'];
    		$activity_user_info = $db_activity_user->where($where_data)->find();
    		if ($activity_user_info) {
    			$data_return['aid'] = $activity_user_info['aid'];
    			$data_return['uid'] = $activity_user_info['uid'];
    			$data_return['cancel'] = $activity_user_info['cancel'];
    			$data_return['join_time'] = $activity_user_info['join_time'];
    			$activity_info = $db_activity->where('id=%d',$data['aid'])->find();
    			if($activity_info){
    				/*
    				$data_return['type'] = $activity_info['type'];
    				$data_return['name'] = $activity_info['name'];
    				$data_return['start_time'] = date('Y-m-d H:i',$activity_info['start_time']);
    				$data_return['end_time'] = date('Y-m-d H:i',$activity_info['end_time']);
    				$data_return['location'] = $activity_info['location'];
    				*/
    				$data_return = array_merge($data_return,$activity_info);
    			}else{
    				$data_return = false;
    			}
    		}else{
    			$data_return = false;
    		}
    	}else{
    		$data_return = false;
    	}

    	if($data_return){
    		$reg['data'] = $data_return;
    		$reg['ret'] = 1;
    		$reg['msg'] = 'ok';
    	}else{
    		$reg['data'] = '';
    		$reg['ret'] = -1;
    		$reg['msg'] = 'failed';
    	}
    	echo json_encode($reg);die;
    }


    public function GetAttach(){
    	$db_activity_attach=M('activity_attach');
    	$data['aid'] = I('aid');
    	$attach_info = $db_activity_attach->where($data)->find();
    	if(is_array($attach_info)&&count($attach_info)>0){
    		$reg['data'] = $attach_info;
    	}else{
    		$reg['data'] = false;
    	}

    	if ($reg['data']) {
    		$reg['ret'] = 1;
    		$reg['mag'] = 'ok';
    	}else{
    		$reg['ret'] = -1;
    		$reg['msg'] = 'failed';
    	}
    	echo json_encode($reg);die;
    }


    public function SetUserinfo(){
    	$db_activity_userinfo = M('activity_userinfo');
        $db_activity_user = M('activity_user');
        $db_activity_attach = M('activity_attach');
    	$data = $_POST;
    	if (empty($data) || !$data) {
    		$data = $_GET;
    	}
        if(!$data){
            $data = json_decode(file_get_contents("php://input"),true);
        }
        error_log("setuserinfo++++++++++++++log++++++".json_encode($data));
        $activity_user_data['aid'] = $data['aid'];
        $activity_user_data['uid'] = $data['uid'];
        $activity_user_data['is_involved']=1;
        $activity_user_data['join_time']=date('Y-m-d H:i:s');
        $activity_user_data['date'] = date('Y-m-d');
        $resu = $db_activity_user->add($activity_user_data);
        //unset($data['aid']);

        $attach['aid'] = $data['aid'];
        $attach_info = $db_activity_attach->where($attach)->find();
        $attach_arr = array();
        if(is_array($attach_info)&&count($attach_info)>0){
            unset($attach_info['id']);
            foreach ($attach_info as $key => $value) {
                if($value){
                    //$attach_arr[$key] = $data[$key]; 2017-3-30 17:00  修改
                    $attach_arr[$key] = $data[$key] ? $data[$key] : '*';
                }
            }
            $attach_arr['uid'] = $data['uid'];
            $attach_arr['date'] = date('Y-m-d');
            $rs = $db_activity_userinfo->add($attach_arr);
        }else{
            $rs = $db_activity_userinfo->add($data);
        }
    	if ($rs && $resu) {
    		$reg['ret'] = 1;
    		$reg['msg'] = 'ok';
    		$reg['data'] = $rs;
    	}else{
    		$reg['ret'] = -1;
    		$reg['msg'] = 'failed';
    		$reg['data'] = '';
    	}

    echo json_encode($reg);die;
}

    public function ImgUpload(){

        $data['aid'] = I('aid');
        $upload = new \Think\Upload();
        $upolad->maxSize = 209715200 ;// 设置附件上传大小209715200
        $upload->rootPath = './Uploads/';
        $upload->savePath = rand(10000,99999);
        $info = $upload->upload();

        if(!$info){
            error_log("upload_error_log------".$upload->getError());
            $this->error($upload->getError());
        }else{
            foreach ($info as $file) {
                $imgurl = 'http://events.p100.com/Uploads/'.$file['savepath'].$file['savename'];
                $result = array(
                            'ret'=>1,
                            'msg'=>'ok',
                            'data'=>array('url'=>$imgurl)
                        );
                    header('Content-type: application/json');
                    echo json_encode($result);die;
            }
        }
        $result = array(
                'ret'=>-1,
                'msg'=>"failure",
                'data'=>''
                );
        echo json_encode($result);die;
    }


    //生成表格并且发送电子邮件
    //public function sendemail(){
    public function Export(){
        $params = $_POST;
        if (!$params) {
            $params = $_GET;
        }
        error_log('export_params_log+++++++++++'.json_encode($params));
        $db_activity_userinfo=M('activity_userinfo');
	    $db_activity_user=M('activity_user');
        $db_activity_attach=M('activity_attach');
        $db_activity_export=M('activity_export');
        $db_activity=M('activity');
        $db_activity_user_info=M('user_info');

        //---------添加判断 是否已存在 二维码---------2017/4/18 17:11
        $export_data['aid'] = $params['aid'];
        $export_data['uid'] = $params['uid'];
        $export_data['extra1'] = array('exp','is not null');
        $export_info = $db_activity_export->where($export_data)->order('id desc')->find();

        if(is_array($export_info) && count($export_info)>0){
            //已经有二维码地址
            $qr_path = "/usr/share/nginx/html/vote/Uploads/".$export_info['extra1'];
        }else{
            $data['aid']=$params['aid'];
            $data['uid']=$params['uid'];
            $data['mail']=$params['email'];
            $data['date'] = date('Y-m-d');
            
            //获取二维码 
            $qr_code = $this->Qrcode($params['aid']);
            $qr_base_path = "/usr/share/nginx/html/vote/Uploads/";

            $qr_path = $qr_base_path."{$data['aid']}.png";
            file_put_contents($qr_path, $qr_code);
            $data['extra1'] = $data['aid'].".png";
            
            $db_activity_export->add($data);
        }
        //--------------------------end-------------------------------

        
        //获取表头信息
        $header = $db_activity_attach->where('aid=%d',$params['aid'])->find();
        
        foreach ($header as $key => $value) {
            if(!$header[$key] || empty($header[$key])){
                unset($header[$key]);
            }
            if ($header[$key] == $header['id']) {
                $header[$key] = '编号';
            }
            if ($header[$key] == $header['aid']) {
                $header[$key] = '微信昵称';
            }

            unset($header['date']);
            unset($header['addtime']);
        }
        $header = array_values($header);
        $content_data['aid'] = $params['aid'];
        $content_data['is_involved'] = 1;
        //$contents = $db_activity_user->where('aid=%d and is_involved=%d',array($data['aid']),1)->order('addtime desc')->select();
        $contents = $db_activity_user->where($content_data)->order('addtime asc')->select();
        foreach ($contents as $key2 => $value2) {
            $ke_arr['aid'] = $value2['aid'];
            $content = $db_activity_userinfo->where($ke_arr)->group('uid')->order('id asc')->select();
            if(is_array($content) && count($content)>0){
                foreach ($content as $k => $v) {
                    foreach ($v as $k1 => $v1) {
                        if(!$v1 || empty($v1)){
                            unset($content[$k][$k1]);
                        }
                    }
                    

                    if($content[$k]['id'] >=0){
                        $content[$k]['id'] = $k + 1;
                    }
                    if($content[$k]['uid']){
                        $userinfo = $db_activity_user_info->where('id=%d',$content[$k]['uid'])->find();
                        $content[$k]['uid'] = $userinfo['nickname'];
                    }
                    unset($content[$k]['aid']);
                    unset($content[$k]['date']);
                    unset($content[$k]['addtime']);
                }
            }
        }
        //获取活动名称
        $activity_title = $db_activity->where('id=%d',$params['aid'])->find();
        $title = $activity_title['activity_name'];

        $base_path = "/tmp/activity_export/(".rand(0,999).")".$title;
        $save_path = $base_path."_用户信息.csv";

        

        $file = fopen($save_path,'w');
        fwrite($file,chr(0xEF).chr(0xBB).chr(0xBF));//输出BOM头
        foreach ($header as $hk => $hv) {
            $arr[] = $hv;
        }
        $b[0] = $arr;
        foreach ($content as $keys => $values) {
                $ke = $keys+1;
                $b[$ke] = $values;
        }

        //$b[1] = $arrs;  
        foreach ($b as $keyl => $valuel) {
            fputcsv($file, $valuel);
        }

        fclose($file);

        $name='hello';
        $to=$params['email']?$params['email']:'1542964971@qq.com';
        $subject='【微群活动】“'.$title.'”报名表导出信息';
        $body='来自微群活动';
        //获取报名信息

        $aa = $this->think_send_mail($to, $name, $subject, $body,$attachment = array($save_path,$qr_path));
        if ($aa) {
            $reg['ret'] = 1;
            $reg['msg'] = 'ok';
            $reg['data'] = '';
        }else{
            $reg['ret'] = -1;
            $reg['msg'] = 'failed';
            $reg['data'] = '';
        }

    echo json_encode($reg);die;
}



    //获取分享页面二维码
    public function createqrcode(){

        $access_token = I('access_token');
        $data['path'] = I('path');
        $data['width'] = I('width');

        $url = 'https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token='.$data['access_token'];

        //$param = json_encode($data);
        $rs = $this->post($url,$data);
        return $rs;
    }


    private function postmethod($url, $post_data = '', $timeout = 5){//curl
 
        $ch = curl_init();
 
        curl_setopt ($ch, CURLOPT_URL, $url);
 
        curl_setopt ($ch, CURLOPT_POST, 1);
 
        if($post_data != ''){
 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
 
        }
 
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
 
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
 
        curl_setopt($ch, CURLOPT_HEADER, false);
 
        $file_contents = curl_exec($ch);
 
        curl_close($ch);
 
        return $file_contents;
 
    }

    /**
     * 系统邮件发送函数
     * @param string $to    接收邮件者邮箱
     * @param string $name  接收邮件者名称
     * @param string $subject 邮件主题 
     * @param string $body    邮件内容
     * @param string $attachment 附件列表
     * @return boolean 
     */
    function think_send_mail($to, $name, $subject = '', $body = '', $attachment = null){
        vendor('PHPMailer.class#phpmailer');
        $mail = new \PHPMailer();

        //判断 邮箱类型 解决163 发给 QQ邮箱 进入垃圾箱问题 2017/4/14
        $mail_type = stripos($to,'qq.com');

        if($mail_type){//是 qq邮箱，用qq进行发送
            $mail->isSMTP();// 使用SMTP服务
            $mail->CharSet = "UTF-8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
            $mail->Encoding = "base64"; //编码方式
            $mail->Host = "smtp.qq.com";// 发送方的SMTP服务器地址
            $mail->SMTPAuth = true;// 是否使用身份验证
            $mail->Username = "weiqunhuodong@qq.com";// 发送方的163邮箱用户名
            $mail->Password = "yofxwrrdbgaocbaa";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！
            $mail->SMTPSecure = "tls";// 使用ssl协议方式
            $mail->Port = 25;// 163邮箱的ssl协议方式端口号是465/994

            $mail->setFrom("weiqunhuodong@qq.com","微群活动");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
            $mail->addAddress($to,$name);// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
            $mail->addReplyTo("weiqunhuodong@qq.com","Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
            //$mail->addCC("aaaa@inspur.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址
            //$mail->addBCC("mail_hehao@126.com");// 设置秘密抄送人
            if(is_array($attachment) && count($attachment)>0){
                foreach ($attachment as $key => $value) {
                    $mail->addAttachment($value);// 添加附件
                }
            }
        }else{
            $mail->isSMTP();// 使用SMTP服务
            $mail->CharSet = "UTF-8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
            $mail->Encoding = "base64"; //编码方式
            $mail->Host = "smtp.163.com";// 发送方的SMTP服务器地址
            $mail->SMTPAuth = true;// 是否使用身份验证
            $mail->Username = "weiqunhuodong@163.com";// 发送方的163邮箱用户名
            $mail->Password = "51weiqun";// 发送方的邮箱密码，注意用163邮箱这里填写的是“客户端授权密码”而不是邮箱的登录密码！
            $mail->SMTPSecure = "tls";// 使用ssl协议方式
            $mail->Port = 25;// 163邮箱的ssl协议方式端口号是465/994

            $mail->setFrom("weiqunhuodong@163.com","微群活动");// 设置发件人信息，如邮件格式说明中的发件人，这里会显示为Mailer(xxxx@163.com），Mailer是当做名字显示
            $mail->addAddress($to,$name);// 设置收件人信息，如邮件格式说明中的收件人，这里会显示为Liang(yyyy@163.com)
            $mail->addReplyTo("weiqunhuodong@163.com","Reply");// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
            //$mail->addCC("aaaa@inspur.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址
            //$mail->addBCC("mail_hehao@126.com");// 设置秘密抄送人
            if(is_array($attachment) && count($attachment)>0){
                foreach ($attachment as $key => $value) {
                    $mail->addAttachment($value);// 添加附件
                }
            }
        }


        $mail->Subject = $subject ? $subject : "This is a test mailxx";// 邮件标题
        $mail->Body = $body ? $body : "This is the html body";// 邮件正文
        
        if(!$mail->send()){// 发送邮件
            error_log("sendemail_error_log____".date('Y-m-d H:i:s').'++++++++++'.$mail->ErrorInfo);
            return false;
        }else{
            return true;
        }
        
    }


    //获取 参与活动用户信息 openid
    public function UserDetail(){
        $params = $_POST;
        if (empty($params)) {
            $params = $_GET;
        }
        $code = $params['code'];
        if ($code) {
            $url = "https://api.weixin.qq.com/sns/jscode2session?appid=wxc03547d2b4191ca2&secret=d39b39a953ac132617d6df84ca16cf26&grant_type=authorization_code&js_code=".$code;
            $res = file_get_contents($url);
            $res = json_decode($res,true);
            if (isset($res['openid'])) {
                $result = array(
                    'ret'=>1,
                    'msg'=>"OK",
                    'data'=>$res
                    );
            }else{
                $result = array(
                    'ret'=>-1,
                    'msg'=>"failure",
                    'data'=>$res
                    );
            }
        }else{
            $result = array(
                'ret'=>-1,
                'msg'=>"failure",
                'data'=>''
                );
        }
        echo json_encode($result);die;
    }

    private function GetSecret(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxc03547d2b4191ca2&secret=d39b39a953ac132617d6df84ca16cf26";
        $res = file_get_contents($url);
        $res = json_decode($res,true);

        return $res;
    }

    //生成二维码
    public function Qrcode($aid){
        $data['path'] = "pages/cardDetail/cardDetail?id=".$aid;
        $data['width'] = 430;
        $params = json_encode($data);
        $res = $this->GetSecret();
        if($res){
            $access_token = $res['access_token'];
            $qr_url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=".$access_token;

            $result = $this->postmethod($qr_url,$params);

            return $result;

        }else{
            return false;
        }

    }

    //添加 点击生成二维码接口 2017/4/19
    public function Getqr(){
        $params = $_GET;
        if(!$params){
            $params = file_get_contents("php://input");
            $params = json_decode($params,true);
        }


        $db_activity_export = M('activity_export');
        if(isset($params['path']) && isset($params['width'])){
            $arr = explode("=", $params['path']);     
            if($arr[1]){
                //判断该活动 是否已经生成过二维码
                $export_arr['aid'] = $arr[1];
                $export_arr['extra1'] = array('exp','is not null');
                $export_info = $db_activity_export->where($export_arr)->order('id desc')->find();
                if($export_info){//之前导出过，有记录

                    $result_data['ret'] = 1;
                    $result_data['img_url'] = 'http://events.p100.com/Uploads/'.$export_info['extra1'];

                }else{//没导出过，去生成  把生成的放在 相应位置，并且返回路径
                    
                    $data['path'] = $params['path'];
                    $data['width'] = $params['width'];

                    $qr_params = json_encode($data);
                    $res = $this->GetSecret();
                    if($res){
                        $access_token = $res['access_token'];
                        $qr_url = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token=".$access_token;

                        $qr_code = $this->postmethod($qr_url,$qr_params);

                        $qr_base_path = "/usr/share/nginx/html/vote/Uploads/";

                        $qr_path = $qr_base_path."{$export_arr['aid']}.png";
                        file_put_contents($qr_path, $qr_code);

                        $db_data['extra1'] = $export_arr['aid'].".png";
                        $db_data['aid'] = $export_arr['aid'];

                        $db_activity_export->add($db_data);

                        $result_data['ret'] = 1;
                        $result_data['img_url'] = 'http://events.p100.com/Uploads/'.$db_data['extra1'];

                    }else{
                        $result_data['ret'] = -1;
                        $result_data['img_url'] = '';                        
                    }
                }
            }
        }else{
            $result_data['ret'] = -1;
            $result_data['img_url'] = '';  
        }

        echo json_encode($result_data);die;
    }

    //新增参与者信息展示页----2017/5/6 10:49-----start------------
    public function JoinList(){
        $params = $_POST;
        if (empty($params)) {
            $params = $_GET;
        }
        $db_activity_user_info=M('user_info');
        $db_activity_user=M('activity_user');
        //获取该活动所有参与者
        $users['aid'] = $params['aid'];
        $user_s = $db_activity_user->where($users)->order('id asc')->group('uid')->select();
        if($user_s){
            $join_list = array();
            foreach ($user_s as $key => $value) {
                $join_list[$key]['join_time'] = $value['addtime'];
                $user_uid['id'] = $value['uid'];
                $per_user = $db_activity_user_info->where($user_uid)->find();
                if($per_user){
                    $join_list[$key]['nickname'] = $per_user['nickname'];
                    $join_list[$key]['avatarurl'] = $per_user['avatarurl'];
                    $join_list[$key]['id'] = $per_user['id'];
                    //判断用户参与状态
                    $join_list[$key]['is_involved'] = $value['is_involved'];
                    if(($per_user['id'] == $params['uid']) && ($value['extra1'] != 1)){
                        $current_user[$key] = $join_list[$key];
                        $key_num = $key;
                    }
                }
            }

            //将当前用户放到第二位
            array_splice($join_list, 1,0,$current_user);
            unset($join_list[$key_num]);
        }else{
            $join_list = array();
        }
        echo json_encode($join_list);die;
    }
    //新增参与者信息展示页----2017/5/6 10:49-----end--------------


}
