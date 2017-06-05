<?php

namespace weixin\controllers;


use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use weixin\models\Express;
use weixin\models\ExpressLog;
/**
 * Site controller
 */
class ExpressController extends BaseController
{
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        echo 'this is express/index action';exit;
    }

    /**
     * Input Express Information
     * @param string $order_id 快递单号
     * @param $_FILE ? string/json ? $img 快递图片
     * @param string/json $receives 收件人id 多个
     * @param int / string $input_id 录入人id/unionId
     * @return json
     */

    public function actionEntering(){
        //接收参数
        $form = Yii::$app->request->post();
        if ($form) {
            $flag = true;
            //快递单号校验
            if($form['form']['order_id'] == ''){
                $flag = false;
                $err['ret'] = -1;
                $err['warning_code'] = '请填写快递单号';
            }
            //收件人校验
            if($form['form']['receives'] == ''){
                $flag = false;
                $err['ret'] = -1;
                $err['warning_code'] = '请添加收件人';   
            }
            //录入人信息校验
            if($form['form']['input_id'] == ''){
                $flag = false;
                $err['ret'] = -1;
                $err['warning_code'] = '录入有误';   
            }
            if($flag){//校验通过，添加入库
                $express_model = new Express();
                $express_model->order_id = $form['form']['order_id'];
                $express_model->img = $form['form']['img'];
                $express_model->receives = $form['form']['receives'];
                $express_model->input_id = $form['form']['input_id'];
                if(isset($form['form']['receiver_name'])){
                    $express_model->receiver_name = $form['form']['receiver_name'];
                }
                $express_model->create_time = date('Y-m-d H:i:s');
                $express_model->save();
                return $this->redirect(['express/ExpressInfo/'.$express_model->id]);
            }else{//校验失败，返回错误提示
                return json_encode($err);exit;
            }

        } else {//返回登录页面
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Query Single Express Information
     * @param string $order_id 快递单号
     * @return view  '' -- 查询失败  1 -- 参数有误
     */
    public function actionExpressInfo(){
        $params = $this->_getParams();
        if ($params) {
            $order_id = $params['order_id'];
            //查询订单信息
            $order_info = Express::find()->where(['order_id'=>$order_id])->one();
            $expressinfo = $order_info ? $order_info :'';
            return $this->render('expressinfo',[
                'expressinfo' => $expressinfo,
                ]);
        }else{//参数有误
            return $this->render('expressinfo',[
                'expressinfo' => 1,
                ]);
        }
    }



    /**
     * Close An Express For Wrong Operation
     * @param Int $id 快递单号所在编号id
     * @return json
     */
    public function actionCloseExpress(){
        $params = $this->_getParams();
        $express_id = $params['id'];
        $express_model = Express::find()->where(['id'=>$params['id']])->one();
        $express_model->enabled = 0;
        $express_model->save();
        if($express_model->enabled ==0){
            $err['ret'] = 1;
            $err['msg'] = '关闭成功'; 
        }else{
            $err['ret'] = -1;
            $err['msg'] = '关闭发生错误'; 
        }
        return json_encode($err);
    }


    /**
     * Add Note Information & Send A Message To Express Receiver
     * @param Int $id 快递单号所在编号id
     * @param Int $order_id 快递单号id
     * @param Str $note 快递领取备注说明
     * @return json
     */
    public function actionMessage(){
        $params = $this->_getParams();
        if($params){
                $transaction = Yii::$app->db->beginTransaction();
                try{
                //add note information
                $model = Express::find()->where(['id'=>$params['id']])->one();
                $model->note = $params['note'];
                $note_res = $model->save();

                //Send A Message To Express Receiver
                $res = $this->SendMessage($data);
                //Add Send Log 
                $express_log_model = new ExpressLog();
                $express_log_model->order_id = $params['order_id'];
                $express_log_model->note = $params['note'];
                $express_log_model->note_type = 1;
                $note_log_res = $express_log_model->save();
                if($note_res && $res && $note_log_res){
                    $transaction->commit();
                    $res_data['ret'] = 1;
                    $res_data['message'] = 'ok';
                }
            }catch(\Exception $e){
                $transaction->roolBack();
                $res_data['ret'] = -1;
                $res_data['message'] = 'fail';
            }
        }else{
            $res_data['ret'] = -3;
            $res_data['message'] = 'params error';
        }
        return json_encode($res_data);
    }

    /**
     * Recevier Confirm An Express
     * @param Int $id 快递单号所在编号id
     * @return json
     */
    public function actionConfirm(){
        $params = $this->_getParams();
        if ($params) {
            $express_model = Express::find()->where(['id'=>$params['id']])->one();
            $express_model->status = 1;
            $res = $express_model->save();
            if($res){
                $res_data['ret'] = 1;
                $res_data['message'] = 'ok';
            }else{
                $res_data['ret'] = -1;
                $res_data['message'] = 'failed';
            }
        }else{
            $res_data['ret'] = -3;
            $res_data['message'] = 'params error';
        }
        return json_encode($res_data);
    }


    /**
     * My Express Uncomfired
     * @param Str $unionId 用户unionId
     * @return json
     */
    public function actionConfirmList(){
        $params = $this->_getParams();
        $express_list = Express::find()
        ->where(['receiver_id'=>$params['receiver_id'],'enabled'=>1,'status'=>0])
        ->orderBy('create_time desc')->all();

        return $this->render('expresslist',[
                'confirmlist'=>$express_list ? $express_list : ''
            ]);
    }


    /**
     * My Express comfired
     * @param Str $unionId 用户unionId
     * @return json
     */
    public function actionUnconfirmList(){
        $params = $this->_getParams();
        $express_list = Express::find()
        ->where(['receiver_id'=>$params['receiver_id'],'enabled'=>1,'status'=>1])
        ->orderBy('create_time desc')->all();

        return $this->render('expresslist',[
                'unconfirmlist'=>$express_list ? $express_list : ''
            ]);
    }



    /**
     * Search sb Receive Express For Original Person
     * @param Str $order_id 快递编号Id
     * @return json or object
     */
    public function actionSearch(){
        $params = $this->_getParams();
        if ($params) {
            $search_res = Express::find()->where(['order_id'=>$params['order_id']])->asArray()->all();
            if($user_ticket){
                return $this->render('search',[
                    'search'=>$search_res ? $search_res : ''
                ]);
            }
        }else{
            return $this->render('search');
        }
    }





    /**
     * Search sb Receive Express For Original Person
     * @param Str $unionId 用户unionId
     * @param Str $order_id 快递编号Id
     * @return view
     */
    public function actionSbConfirm(){
        $params = $this->_getParams();
        //get information of sb
        $sb_info = $this->sb_info($params['unionId']);

        $express_model = Express::find()->where(['order_id'=>$params['order_id']])->one();
            $express_model->is_instead = 1;
            $express_model->instead_receiver_id = $params['unionId'];
            $res = $express_model->save();
        if ($res) {
            $this->redirect(['Express/UnconfirmList'.$params['unionId']]);
        }
    }


    /**
     * Search sb Receive Express For Original Person
     * @param Str $unionId 用户unionId
     * @return json
     */
    private function sb_info($unionId){

    }

    /**
     * Send A Message To Express Receiver
     * @param Int $id 快递单号所在编号id
     * @param Str $unionId 收件人unionId
     * @param Int $note_type 消息类型
     * @param Str $note 快递领取备注说明
     * @return json
     */    
    private function SendMessage($data){

    }



    private function _getParams(){
        $params = Yii::$app->request->post();
        if(!$params || count($params)<0){
            $params = Yii::$app->request->get();
            if($params && count($params)>0){
                return $params;
            }else{
                return false;
            }
        }else{
            return $params;
        }
    }
}
