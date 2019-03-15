<?php
namespace app\home\controller;
use think\Db;
use clt\Lunar;
use think\facade\Env;
class Index extends Common{
    public function initialize(){
        parent::initialize();
    }
    public function index(){
        $province = \db('province')->select();
        $order = input('get.order_type') ?: 3;
        $page = input('get.pno') ?: 1;
        $page_num = 20;
        $limit = ($page - 1)*$page_num .' '.$page_num;
        $where = [];

        switch ($order){
            case '1':
                $order = 'num asc';
                break;
            case '2':
                $order = 'num desc';
                break;
            case '3':
                $order = 'price asc';
                break;
            case '4':
                $order = 'price desc';
                break;
        }
        if(input('get.key')){
            $where = [
                [
                    'a.name|a.area|a.do|a.remark|a.ctime',
                    'like',
                    '%'.input('get.key').'%'
                ]
            ];
        }
        if(input('get.province_id')){
            $where = [
                'a.province_id' =>input('get.province_id')
            ];
        }
        $querysql = \db('company')
            ->field('a.*')
            ->alias('a')
            ->buildSql();

        $list = (array)Db::table($querysql.' a')->where($where)->order($order)->limit($limit)->select();
        $total = (array)Db::table($querysql.' a')->where($where)->order($order)->select();
        $inc = ($page -1)*$page_num;
        foreach ($list as &$v){
            $inc ++ ;
            $v['inc'] = $inc;
        }
        //var_dump($list);die;
        $this->assign('company', $list);
        $this->assign('total', count($total));
        $this->assign('page', ceil(count($total)/$page_num)));
        $this->assign('province', $province);
        return $this->fetch();
    }

    public function detail(){

        $session = 'QCCSESSID=h58psdumuhrvg2lgop7rfa1n15;';
        $info = [];
        $id = input('get.id');
        $id && $company = \db('company')->where('id',$id)->find();
        if($id && $company){
            $data = $this->curl([
                'url' => "https://www.qichacha.com/search?key=".urlencode($company['name']),
                'header' => array(
                    "Cookie: $session",
                    "cache-control: no-cache"
                )
            ]);

            preg_match('/\/firm(.*?)\.html\"/', $data, $match);
            if($match[1]){
                $data = $this->curl([
                    'url' => "https://www.qichacha.com/firm".$match[1].'.html#base',
                    'header' => [
                        "Cookie: $session",
                        "Referer: https://www.qichacha.com/firm_8ea75821e4b1000604549632548e5c43.html",
                        "cache-control: no-cache"
                    ]
                ]);
                preg_match('/法定代表人信息[\s\S]*?partnerslist/', $data, $match);

                $data = $match[0];
                preg_match('/20\">(.*?)<\/h2/',$data, $faren);
                $info['fa_info'] = $faren[1] ;
                preg_match_all('/<td[\s\S]*?<\/td>/', $data, $match);
                $match = array_map(function ($item){
                    return trim(strip_tags(str_replace('&nbsp;','',$item)));
                }, $match[0]);
                //print_r($match);

                //if(!empty($match) && $company['name'] === $match[29]){
                if(!empty($match)){
                    $info['company_name'] = $company['name'] ;
                    $info['crad_no'] = $match[14] ;
                    $info['has_price'] = $match[8] ;
                    $info['ctime'] = $match[12] ;
                    $info['htime'] = $match[26] ;
                    $info['area'] = $match[30] ;
                    $info['djjg'] = $match[28] ;
                    $info['company_area'] = $match[42] ;
                    $info['do'] = $match[44] ;
                }else{
                    unset($info);
                }
            }
            $info['num'] = $company['num'] ;
            $info['price'] = $company['price'] ;
        }

        $this->assign('data', $info);
        unset($info['company_name']);
        \db('company')->where('id',$company['id'])->update($info);
        return $this->fetch();
    }

    private function curl($data){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $data['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => $data['header'],
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

}