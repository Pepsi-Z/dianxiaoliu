<?php
/**
 * 控制器基类
 *
 * @author andery
 */
require_once './app/Common/JsApi.php';
class baseAction extends Action
{
    protected function _initialize() {

        //消除所有的magic_quotes_gpc转义
        Input::noGPC();
        //初始化网站配置
        if (false === $setting = F('setting')) {
            $setting = D('setting')->setting_cache();
        }
        C($setting);
        //p(F('setting'));
        //发送邮件
        $this->assign('async_sendmail', session('async_sendmail'));

    }
    public function _lable(){
        $label = array(
            '0'=>'美食',
            '1'=>'洗车',
            '2'=>'KTV',
            '3'=>'同城速递',
            '4'=>'小六商城',
            '5'=>'小六折扣',
            '6'=>'小六套餐',
            '7'=>'电影',
            '8'=>'车辆保养保险',
            '9'=>'娱乐',

        );
        return $label;
    }

    public function _teg(){
        $teg = array(
            '0'=>'元起送',
            '1'=>'元',
            '2'=>'米',
            '3'=>'份',
            '4'=>'元/次',
            '5'=>'元/半天',
            '6'=>'元/天',
            '8'=>'元/小时',
            '9'=>'元/月',
            '10'=>'元/周',
            '11'=>'元/场',
            '12'=>'元/次/公里',
            '13'=>'起',
            '14'=>'元起含5公里,每超3公里内加5元',
        );
        return $teg;
    }

    public function _empty() {
        $this->_404();
    }
    
    protected function _404($url = '') {
        if ($url) {
            redirect($url);
        } else {
            send_http_status(404);
            $this->display(TMPL_PATH . '404.html');
            exit;
        }
    }

    /**
     * 添加邮件到队列
     */
    protected function _mail_queue($to, $subject, $body, $priority = 1) {
        $to_emails = is_array($to) ? $to : array($to);
        $mails = array();
        $time = time();
        foreach ($to_emails as $_email) {
            $mails[] = array(
                'mail_to' => $_email,
                'mail_subject' => $subject,
                'mail_body' => $body,
                'priority' => $priority,
                'add_time' => $time,
                'lock_expiry' => $time,
            );
        }
        M('mail_queue')->addAll($mails);

        //异步发送邮件
        $this->send_mail(false);
    }

    /**
     * 发送邮件
     */
    public function send_mail($is_sync = true) {
        if (!$is_sync) {
            //异步
            session('async_sendmail', true);
            return true;
        } else {
            //同步
            session('async_sendmail', null);
            return D('mail_queue')->send();
        }
    }

    /**
     * 上传文件默认规则定义
     */
    protected function _upload_init($upload) {
        $allow_max = C('pin_attr_allow_size'); //读取配置
        $allow_exts = explode(',', C('pin_attr_allow_exts')); //读取配置
        $allow_max && $upload->maxSize = $allow_max * 1024;   //文件大小限制
        $allow_exts && $upload->allowExts = $allow_exts;  //文件类型限制
        $upload->saveRule = 'uniqid';
        return $upload;
    }

    /**
     * 上传文件
     */
    protected function _upload($file, $dir = '', $thumb = array(), $save_rule='uniqid') {
        $upload = new UploadFile();
        if ($dir) {
            $upload_path = C('pin_attach_path') . $dir . '/';
            $upload->savePath = $upload_path;
        }
        if ($thumb) {
            $upload->thumb = true;
            $upload->thumbMaxWidth = $thumb['width'];
            $upload->thumbMaxHeight = $thumb['height'];
            $upload->thumbPrefix = '';
            $upload->thumbSuffix = isset($thumb['suffix']) ? $thumb['suffix'] : '_thumb';
            $upload->thumbExt = isset($thumb['ext']) ? $thumb['ext'] : '';
            $upload->thumbRemoveOrigin = isset($thumb['remove_origin']) ? true : false;
        }
        //自定义上传规则
        $upload = $this->_upload_init($upload);
        if( $save_rule!='uniqid' ){
            $upload->saveRule = $save_rule;
        }

        if ($result = $upload->uploadOne($file)) {
            return array('error'=>0, 'info'=>$result);
        } else {
            return array('error'=>1, 'info'=>$upload->getErrorMsg());
        }
    }

    /**
     * AJAX返回数据标准
     *
     * @param int $status
     * @param string $msg
     * @param mixed $data
     * @param string $dialog
     */
    protected function ajaxReturn($status=1, $msg='', $data='', $dialog='') {
        parent::ajaxReturn(array(
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'dialog' => $dialog,
        ));
    }


    /***
     * ajax 上传图片 base64
     */
    public function ajax_upload() {
        $img = I('post.img','','');
        $imgId = I('post.id',0,'intval');

        list($type_data, $data) = explode(',', $img);
        list($type_2, $no) = explode(';', $type_data);
        list($no, $type) = explode(':', $type_2);
        $mime_types = array (
            'image/jpeg' => '.jpg',
            'image/gif' => '.gif',
            'image/png' => '.png',
        );
        $ext = $mime_types[$type] ? $mime_types[$type] : '.jpg';

        $Path_dir = './data/upload/supply/';
        $filePath = date('Ymd');
        if(!is_dir($Path_dir.$filePath)){
            mkdir($Path_dir.$filePath,0777,true);
        }
        if($imgId){
            $supply_img = D('supply_img')->where(array( 'id' => $imgId))->getField('url');
            $filename = $supply_img ? substr($supply_img, strrpos($supply_img, '/')+1) : (md5(rand(100000,999999)).$ext);

        }else{
            $filename = md5(rand(100000,999999)).$ext;
        }
        $filePath = $filePath.'/'.$filename;
        if (file_put_contents($Path_dir.$filePath,base64_decode($data))!==false) {
            if(empty($imgId)){
                $img_id = D('supply_img')->add(array( 'supply_id' => 0,  'url' => $filePath ));
            }else{
                D('supply_img')->where(array( 'id' => $imgId ))->save(array( 'url' => $filePath));
                $img_id = $imgId;
            }
            $this->ajaxReturn(1,'上传成功','./data/upload/supply/'.$filePath.'?v='.time(),$img_id);
        }else{
            $this->ajaxReturn(0,'上传失败');
        }
    }

    //根据经纬度获取城市名称
    public function getCityByJWd($jd,$wd) {

        $url = "http://api.map.baidu.com/geocoder?location={$wd},{$jd}&output=json&key=sGMeILH7H8UCi9sKLMPAXKAz";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        curl_close ( $ch );
        $result = json_decode($result,true);
        if($result['status'] == 'OK'){
            $address =  str_replace(array(
                $result['result']['addressComponent']['province'],
                $result['result']['addressComponent']['city']
            ),array(
                '',
                ''
            ),$result['result']['formatted_address']);
        }
//        $j = simplexml_load_string($result);

//        $adr['city'] = (string)$j->result->addressComponent->city;
        $adr['city'] = $result['result']['addressComponent']['city'];
//        $adr['address'] = (string)$j->result->formatted_address;
        $adr['address'] = $address;
//        $adr['city'] = str_replace("市", "", $adr['city']);
        $adr['city'] = str_replace("市", "", $adr['city']);

        return $adr;

    }

    /**
     *求两个已知经纬度之间的距离,单位为米
     *@param lng1,lng2 经度
     *@param lat1,lat2 纬度
     *@return float 距离，单位米
     *@author www.Alixixi.com
     **/
    function getdistance($lng1,$lat1,$lng2,$lat2){
        //将角度转为狐度

        $radLat1=deg2rad($lat1);//deg2rad()函数将角度转换为弧度
        $radLat2=deg2rad($lat2);
        $radLng1=deg2rad($lng1);
        $radLng2=deg2rad($lng2);
        $a=$radLat1-$radLat2;
        $b=$radLng1-$radLng2;
        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378.137*1000;
        $s1 = round($s);
        if($s1 > 300){
            $s1 = $s1 - 150;
        }
        return $s1;
    }
    // 根据经纬度计算周围1000米寻找店铺  **$distance = 0.5 代表0.5千米
    public function returnSquarePoint($lng, $lat,$distance = ''){
        $EARTH_RADIUS = 6371;
        //$lat 已知点的纬度
        $dlng =  4 * asin(sin($distance / (2 * $EARTH_RADIUS)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);//转换弧度

        $dlat = $distance/$EARTH_RADIUS;//EARTH_RADIUS地球半径
        $dlat = rad2deg($dlat);//转换弧度

        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );
    }

    public function get_openids(){
        if(!$_SESSION['openid']){
            $tools = new JsApi();
            $_SESSION['openid'] = $tools->GetOpenid();
        }

        return $_SESSION['openid'];

    }
}