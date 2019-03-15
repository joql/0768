<?php
namespace app\admin\controller;
use think\Db;
use think\Env;
use think\facade\Request;
class Company extends Common
{
    protected $dao;
    function initialize()
    {
        parent::initialize();
    }
    public function index(){
        if(Request::isAjax()) {
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $sort_by = input('sort_order') ? input('sort_by') : 'id';
            $sort_order = input('sort_order') ? input('sort_order') : 'asc';
            $list = db('company')->order($sort_by.' '.$sort_order)
                ->paginate($pageSize)
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }else{
            return $this->fetch();
        }
    }
    public function province(){
        if(Request::isAjax()) {
            $pageSize =input('limit')?input('limit'):config('pageSize');
            $sort_by = input('sort_order') ? input('sort_by') : 'id';
            $sort_order = input('sort_order') ? input('sort_order') : 'asc';
            $list = db('province')->order($sort_by.' '.$sort_order)
                ->paginate($pageSize)
                ->toArray();
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list['data'],'count'=>$list['total'],'rel'=>1];
        }else{
            return $this->fetch();
        }
    }
    public function provinceadd(){
        if(request()->isPost()){

            $name = input('post.name');
            if(db('province')->where('name',$name)->find()){
                $result['code'] = 0;
                $result['info'] = '省份已存在！';
                return $result;
            }
            if(db('province')->insert(['name'=>$name])){
                $result['code'] = 1;
                $result['msg'] = '添加成功！';
                $result['url'] = url('province');
                return $result;
            }
            $result['code'] = 0;
            $result['info'] = '添加失败！';
            return $result;
        }else{

            return $this->fetch('provinceform');
        }
    }
    public function provincedel() {
        $id =input('param.id');
        $r = db('province')->find($id);
        if(!empty($r)){
            $m = db('province')->delete($id);
            return ['code'=>1,'msg'=>'删除成功！'];
        }
        return ['code'=>0,'msg'=>'删除成功！'];

    }

    public function edit(){
        if(request()->isPost()){

            if(\db('company')->where('id',input('post.id'))->update([
                'remark'=>input('post.remark'),
                    'num' => input('post.num'),
                    'price' => input('post.price'),
                ])!==false){
                return array('code'=>1,'url'=>url('index'),'msg'=>'修改成功!');
            }else{
                return array('code'=>0,'url'=>url('index'),'msg'=>'修改失败!');
            }
        }else{
            $map['id'] = input('param.id');
            $info = db('company')->where($map)->find();
            $this->assign('info', $info);
            return $this->fetch('edit');
        }
    }
    public function add(){

        if(request()->isPost()){
            $insert = [];
            $insert = [];
            $filedir =\think\facade\Env::get('root_path') . 'public/' .input('post.companys');
            $lines = (array)explode("\n", $this->auto_iconv(file_get_contents($filedir)));
            foreach($lines as $item){
                //$item=trim(iconv("GB2312", "UTF-8", $item));
                if(!empty($item)){
                    $tmp = explode('----',$item);
                    $insert[] = [
                        'name' => $tmp[0],
                        'num' => $tmp[1],
                        'price' => $tmp[2],
                        'remark' => $tmp[3],
                    ];
                }
            }
            if(\db('company')->insertAll($insert)){
                $result['code'] = 1;
                $result['msg'] = '添加成功！';
                $result['url'] = url('company');
                return $result;
            }
            $result['code'] = 0;
            $result['info'] = '添加失败！';
            return $result;
        }else{
            $province = \db('province')->select();
            $this->assign('province', $province);
            return $this->fetch('form');
        }
    }
    //模型状态
    public function moduleState(){
        $id=input('post.id');
        $status=input('post.status');
        if(db('module')->where('id='.$id)->update(['status'=>$status])!==false){
            return ['status'=>1,'msg'=>'设置成功!'];
        }else{
            return ['status'=>0,'msg'=>'设置失败!'];
        }
    }
    //删除模型
    function del() {
        $id =input('param.id');
        $r = db('company')->find($id);
        if(!empty($r)){
            $m = db('company')->delete($id);
            return ['code'=>1,'msg'=>'删除成功！'];
        }
        return ['code'=>0,'msg'=>'删除成功！'];

    }
    function delmore() {
        $ids =input('param.ids');

        $r = db('company')->where('id','in',$ids)->delete();
        if(!empty($r)){
            return ['code'=>1,'msg'=>'删除成功！'];
        }
        return ['code'=>0,'msg'=>'删除成功！'];

    }

    /****************************模型字段******************************/
    public function field(){
        if(request()->isPost()){
            $nodostatus = array('catid','title','status','createtime');
            $sysfield = array('catid','userid','username','title','thumb','keywords','description','posid','status','createtime','url','template');

            $list = db('field')->where("moduleid=".input('param.id'))->order('sort asc,id asc')->select();
            foreach ($list as $k=>$v){
                if($v['status']==1){
                    if(in_array($v['field'],$nodostatus)){
                        $list[$k]['disable']=2;
                    }else{
                        $list[$k]['disable']=0;
                    }
                }else{
                    $list[$k]['disable']=1;
                }

                if(in_array($v['field'],$sysfield)){
                    $list[$k]['delStatus']=1;
                }else{
                    $list[$k]['delStatus']=0;
                }
            }
            $this->assign('list', $list);
            return $result = ['code'=>0,'msg'=>'获取成功!','data'=>$list,'rel'=>1];
        }else{
            return $this->fetch();
        }
    }
    //修改状态
    public function fieldStatus(){
        $map['id']=input('post.id');
        //判断当前状态情况
        $field = db('field');
        $status=$field->where($map)->value('status');
        if($status==1){
            $data['status'] = 0;
        }else{
            $data['status'] = 1;
        }
        $field->where($map)->setField($data);
        return $data;
    }
    //添加字段
    public function fieldAdd(){
        if(Request::isAjax()){
            if(input('isajax')) {
                $this->assign(input('get.'));
                $this->assign(input('post.'));
                $name = db('module')->where(array('id' => input('moduleid')))->value('name');
                if (input('name')) {
                    $files = Db::getTableFields(config('database.prefix') . $name);
                    if(isset($files['type'][input('name')])){
                        $fieldtype = $files['type'][input('name')];
                    }else{
                        $fieldtype = '';
                    }
                    $this->assign('fieldtype', $fieldtype);
                    return view('fieldType');
                } else {
                    return view('fieldAddType');
                }
            }else{
                $data = input('post.');
                $fieldName=$data['field'];
                $prefix=config('database.prefix');
                $name = db('module')->where(array('id'=>$data['moduleid']))->value('name');
                $tablename=$prefix.$name;
                $Fields=Db::getTableFields($tablename);
                if(in_array($fieldName,$Fields)){
                    $result['msg'] = '字段名已经存在！';
                    $result['code'] = 0;
                    return $result;
                }
                $addfieldsql =$this->get_tablesql($data,'add');
                if(isset($data['setup'])) {
                    $data['setup'] = array2string($data['setup']);
                }
                $data['status'] =1;
                if($data['pattern']=='?'){
                    $data['pattern'] = 'defaul';
                }else{
                    $pattern= explode(':',$data['pattern']);
                    $data['pattern'] = $pattern[1];
                }
                if(empty($data['class'])){
                    $data['class'] = $data['field'];
                }
                $model = db('field');
                if ($model->insert($data) !==false) {
                    savecache('Field',$data['moduleid']);
                    if(is_array($addfieldsql)){
                        foreach($addfieldsql as $sql){
                            $model->execute($sql);
                        }
                    }else{
                        $model->execute($addfieldsql);
                    }
                    $result['msg'] = '添加成功！';
                    $result['code'] = 1;
                    $result['url'] = url('field',array('id'=>input('post.moduleid')));
                    return $result;
                } else {
                    $result['msg'] = '添加失败！';
                    $result['code'] = 0;
                    return $result;
                }
            }
        }else{
            $moduleid =input('moduleid');
            $this->assign('moduleid',$moduleid);
            $this->assign('title',lang('add').lang('field'));
            $this->assign('info','null');
            return $this->fetch('fieldForm');
        }
    }
    //编辑字段
    public function fieldEdit(){
        if(Request::isAjax()){
            $data = Request::except('oldfield');
            $oldfield = input('oldfield');
            $fieldName=$data['field'];
            $name = db('module')->where(array('id'=>$data['moduleid']))->value('name');
            $prefix=config('database.prefix');
            if($this->_iset_field($prefix.$name,$fieldName) && $oldfield!=$fieldName){
                $result['msg'] = '字段名重复！';
                $result['code'] = 0;
                return $result;
            }

            $editfieldsql =$this->get_tablesql(input('post.'),'edit');
            if($data['setup']){
                $data['setup']=array2string($data['setup']);
            }
            if(!empty($data['unpostgroup'])){
                $data['setup'] = implode(',',$data['unpostgroup']);
            }
            if($data['pattern']=='?'){
                $data['pattern'] = 'defaul';
            }else{
                $pattern= explode(':',$data['pattern']);
                $data['pattern'] = $pattern[1];
            }
            if(empty($data['class'])){
                $data['class'] = $data['field'];
            }


            $model = db('field');
            if (false !== $model->update($data)) {
                savecache('Field',$data['moduleid']);
                if(is_array($editfieldsql)){
                    foreach($editfieldsql as $sql){
                        $model->execute($sql);
                    }
                }else{
                    $editfieldsql;
                    $model->execute($editfieldsql);
                }
                $result['msg'] = '修改成功！';
                $result['code'] = 1;
                $result['url'] = url('field',array('id'=>input('post.moduleid')));
                return $result;
            } else {
                $result['msg'] = '修改失败！';
                $result['code'] = 0;
                return $result;
            }
        }else{
            $model = db('field');
            $id = input('param.id');
            if(empty($id)){
                $result['msg'] = '缺少必要的参数！';
                $result['code'] = 0;
                return $result;
            }
            $fieldInfo = $model->where(array('id'=>$id))->find();
            if($fieldInfo['setup']) $fieldInfo['setup']=string2array($fieldInfo['setup']);
            $this->assign('info',json_encode($fieldInfo,true));
            $this->assign('title',lang('edit').lang('field'));
            $this->assign('moduleid', input('param.moduleid'));
            return $this->fetch('fieldForm');
        }
    }
    //字段排序
    public function listOrder(){
        $model =db('field');
        $data = input('post.');
        if($model->update($data)!==false){
            return $result = ['msg' => '操作成功！','url'=>url('field',array('id'=>input('post.moduleid'))), 'code' => 1];
        }else{
            return $result = ['code'=>0,'msg'=>'操作失败！'];
        }
    }

    function fieldDel() {
        $id=input('id');
        $r = db('field')->find($id);
        db('field')->delete($id);

        $moduleid = $r['moduleid'];

        $field = $r['field'];

        $prefix=config('database.prefix');
        $name = db('module')->where(array('id'=>$moduleid))->value('name');
        $tablename=$prefix.$name;

        db('field')->execute("ALTER TABLE `$tablename` DROP `$field`");

        return ['code'=>1,'msg'=>'删除成功！'];
    }


    public function get_tablesql($info,$do){
        $comment = $info['name'];
        $fieldtype = $info['type'];
        if(isset($info['setup']['fieldtype'])){
            $fieldtype=$info['setup']['fieldtype'];
        }
        $moduleid = $info['moduleid'];
        $default = '';
        if(isset($info['setup']['default'])){
            $default=$info['setup']['default'];
        }
        $field = $info['field'];
        $prefix = config('database.prefix');
        $name = db('module')->where(array('id'=>$moduleid))->value('name');
        $tablename=$prefix.$name;
        $maxlength = intval($info['maxlength']);
        $minlength = intval($info['minlength']);
        $numbertype = '';
        if(isset($info['setup']['numbertype'])){
            $numbertype=$info['setup']['numbertype'];
        }

        $isnull = $info['required']==0?'NULL':"NOT NULL";
        if($do=='add'){
            $do = ' ADD ';
        }else{
            $oldfield = $info['oldfield'];
            $do =  " CHANGE `".$oldfield."` ";
        }
        switch($fieldtype) {
            case 'varchar':
                if(!$maxlength){$maxlength = 255;}
                $maxlength = min($maxlength, 255);
                $sql = "ALTER TABLE `$tablename` $do `$field` VARCHAR( $maxlength ) $isnull DEFAULT '$default' COMMENT '$comment'";
                break;
            case 'title':
                $thumb = $info['setup']['thumb'];
                $style = $info['setup']['style'];
                if(!$maxlength){$maxlength = 255;}
                $maxlength = min($maxlength, 255);
                $sql[] = "ALTER TABLE `$tablename` $do `$field` VARCHAR( $maxlength ) $isnull DEFAULT '$default' COMMENT '$comment'";


                if(!$this->_iset_field($tablename,'thumb')){
                    if($thumb==1) {
                        $sql[] = "ALTER TABLE `$tablename` ADD `thumb` VARCHAR( 100 ) $isnull DEFAULT '' COMMENT '缩略图'";
                    }
                }else{
                    if($thumb==0) {
                        $sql[] = "ALTER TABLE `$tablename` drop column `thumb`";
                    }
                }

                if(!$this->_iset_field($tablename,'title_style')){
                    if($style==1) {
                        $sql[] = "ALTER TABLE `$tablename` ADD `title_style` VARCHAR( 100 )$isnull DEFAULT '' COMMENT '标题样式'";
                    }
                }else{
                    if($style==0) {
                        $sql[] = "ALTER TABLE `$tablename` drop column `title_style`";
                    }
                }
                break;
            case 'catid':
                $sql = "ALTER TABLE `$tablename` $do `$field` SMALLINT(5) UNSIGNED $isnull DEFAULT '0' COMMENT '$comment'";
                break;

            case 'number':
                $decimaldigits = $info['setup']['decimaldigits'];
                $default = $decimaldigits == 0 ? intval($default) : floatval($default);
                $sql = "ALTER TABLE `$tablename` $do `$field` ".($decimaldigits == 0 ? 'INT' : 'decimal( 10,'.$decimaldigits.' )')." ".($numbertype ==1 ? 'UNSIGNED' : '')."  $isnull DEFAULT '$default'  COMMENT '$comment'";
                break;

            case 'tinyint':
                if(!$maxlength) $maxlength = 3;
                $maxlength = min($maxlength,3);
                $default = intval($default);
                $sql = "ALTER TABLE `$tablename` $do `$field` TINYINT( $maxlength ) ".($numbertype ==1 ? 'UNSIGNED' : '')." $isnull DEFAULT '$default'  COMMENT '$comment'";
                break;


            case 'smallint':
                $default = intval($default);
                $sql = "ALTER TABLE `$tablename` $do `$field` SMALLINT ".($numbertype ==1 ? 'UNSIGNED' : '')." $isnull DEFAULT '$default' COMMENT '$comment'";
                break;

            case 'int':
                $default = intval($default);
                $sql = "ALTER TABLE `$tablename` $do `$field` INT ".($numbertype ==1 ? 'UNSIGNED' : '')." $isnull DEFAULT '$default' COMMENT '$comment'";
                break;

            case 'mediumint':
                $default = intval($default);
                $sql = "ALTER TABLE `$tablename` $do `$field` INT ".($numbertype ==1 ? 'UNSIGNED' : '')." $isnull DEFAULT '$default' COMMENT '$comment'";
                break;

            case 'mediumtext':
                $sql = "ALTER TABLE `$tablename` $do `$field` MEDIUMTEXT $isnull COMMENT '$comment'";
                break;

            case 'text':
                $sql = "ALTER TABLE `$tablename` $do `$field` TEXT $isnull COMMENT '$comment'";
                break;

            case 'posid':
                $sql = "ALTER TABLE `$tablename` $do `$field` TINYINT(2) UNSIGNED $isnull DEFAULT '0' COMMENT '$comment'";
                break;

            //case 'typeid':
            //$sql = "ALTER TABLE `$tablename` $do `$field` SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0'";
            //break;

            case 'datetime':
                $sql = "ALTER TABLE `$tablename` $do `$field` INT(11) UNSIGNED $isnull DEFAULT '0' COMMENT '$comment'";
                break;

            case 'editor':
                $sql = "ALTER TABLE `$tablename` $do `$field` TEXT $isnull COMMENT '$comment'";
                break;

            case 'image':
                $sql = "ALTER TABLE `$tablename` $do `$field` VARCHAR( 80 ) $isnull DEFAULT '' COMMENT '$comment'";
                break;

            case 'images':
                $sql = "ALTER TABLE `$tablename` $do `$field` MEDIUMTEXT $isnull COMMENT '$comment'";
                break;

            case 'file':
                $sql = "ALTER TABLE `$tablename` $do `$field` VARCHAR( 80 ) $isnull DEFAULT '' COMMENT '$comment'";
                break;

            case 'files':
                $sql = "ALTER TABLE `$tablename` $do `$field` MEDIUMTEXT $isnull COMMENT '$comment'";
                break;
            case 'template':
                $sql = "ALTER TABLE `$tablename` $do `$field` VARCHAR( 80 ) $isnull DEFAULT '' COMMENT '$comment'";
                break;
            case 'linkage':
                $sql = "ALTER TABLE `$tablename` $do `$field` VARCHAR( 80 ) $isnull DEFAULT '' COMMENT '$comment'";
                break;
        }
        return $sql;
    }
    protected function _iset_field($table,$field){
        $fields = Db::getTableFields($table);
        return array_search($field,$fields);
    }

    private function auto_iconv($str, $toencode='UTF-8'){
        $encode = mb_detect_encoding($str,
            array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP')
        );
        $str_encode = mb_convert_encoding($str,$toencode , $encode);
        return $str_encode;
    }

}