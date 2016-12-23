<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }



    public function window(){

    	//window页面的操作判断        for window page action
		$this->assign('action',$_GET['action']);
		$this->assign('date',$_GET['date']);

		//window页面提交的url          url for window post
		$this->assign('url',U('Index/'.$_GET['action']));

		//编辑操作
		if($_GET['action']=='edit'){
			$sql['id']=$_GET['id'];
			$sel=M('demo')->where($sql)->select();
			$data=$sel[0];
			//假如是全天事件
			if($data['allDay']==1){
				if($data['end']){
					$data['end'] =date ( 'Y-m-d', $data['end']);
					$data['isend']=1;
				}
			}else{
				$data['s_hour'] =date ( 'H', $data['start']);
				$data['s_minute'] =date ( 'i', $data['start']);
				$data['e_hour'] =date ( 'H', $data['end']);
				$data['e_minute'] =date ( 'i', $data['end']);
				if($data['end']){
					//dump($data['end']);exit;
					$data['end'] =date ( 'Y-m-d', $data['end']);
					$data['isend']=1;
					$data['allDay']=0;
				}
			}
			$data['start'] =date ( 'Y-m-d', $data['start']);
			$this->assign('info',$data);
		}
    	$this->display();

    }

    //添加操作
    public function add(){
    	//dump($_POST['isallday']);exit;
    	$isallday=$_POST['isallday'];
    	$isend=$_POST['isend'];
		$s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间
		$e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间
		//根据是否为全天事件&&有结束标记进行判断
    	if($isallday==1 && $isend==1){
			$data['start'] = strtotime($_POST['startdate']);
			$data['end'] = strtotime($_POST['enddate']);
		}elseif($isallday==1 && $isend==""){
			$data['start'] = strtotime($_POST['startdate']);

		}elseif($isallday=="" && $isend==1){
			$data['start'] = strtotime($_POST['startdate'].' '.$s_time);
			$data['end'] = strtotime($_POST['enddate'].' '.$e_time);
		}else{
			$data['start'] = strtotime($_POST['startdate'].' '.$s_time);
		}
		if($_POST['finish']==1){
			$data['color']="#CCCCCC";
		}else{
			$data['color']="#999999";
		}
		$data['title']=$_POST['event'];
		$data['finish']=$_POST['finish'];
    	$data['allDay']=$isallday;
		$status=M("demo")->add($data);
		if($status){
			echo 1;
		}else{
			echo "添加失敗";
		}
    }

   //编辑操作
   public function edit(){
   		$isallday=$_POST['isallday'];
    	$isend=$_POST['isend'];
		$s_time = $_POST['s_hour'].':'.$_POST['s_minute'].':00';//开始时间
		$e_time = $_POST['e_hour'].':'.$_POST['e_minute'].':00';//结束时间
		//根据是否为全天事件&&有结束标记进行判断
    	if($isallday==1 && $isend==1){

			$data['start'] = strtotime($_POST['startdate']);
			$data['end'] = strtotime($_POST['enddate']);
		}elseif($isallday==1 && $isend==""){
			$data['start'] = strtotime($_POST['startdate']);
		}elseif($isallday=="" && $isend==1){
			$data['start'] = strtotime($_POST['startdate'].' '.$s_time);

			$data['end'] = strtotime($_POST['enddate'].' '.$e_time);
		}else{
			$data['start'] = strtotime($_POST['startdate'].' '.$s_time);
		}
		if($_POST['finish']==1){
			$data['color']="#CCCCCC";
		}else{
			$data['color']="#999999";
		}
		$data['title']=$_POST['event'];
		$data['finish']=$_POST['finish'];
    	
    	$data['allDay']=$isallday;
		$status=M("demo")->where('id='.$_POST['id'])->save($data);
		if($status){
			echo 1;
		}else{
			echo "更新失败";
		}
    }

    //移动和拉伸操作
    public function drop(){
    	$data['start']=strtotime($_GET['start']);
    	if($_GET['end']!="null"){
    		$data['end']=strtotime($_GET['end']);
    	}
    	$status=M('demo')->where('id='.$_GET['id'])->save($data);
    	if($status){
    		echo 1;
    	}else{
    		echo '移动失败';
    	}
    }



    public function json(){
	$sel=M("demo")->select();
	//echo M("demo")->_sql();exit;
	foreach ($sel as $v) {
		//$v['color']="#ddd";
		if($v['allday']==1){
			$v['start']=date('Y-m-d', $v ['start'] );
			$v['end']=date('Y-m-d', $v ['end'] );
		}else{
			$v['start']=date( 'Y-m-d H:i:s', $v ['start'] );
			$v['end']=date('Y-m-d H:i:s', $v ['end'] );
			$v['allDay']=false;
		}
    	$data[]=$v;
	}
	echo json_encode($data);exit;

    }
}