<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Member;
use common\models\MemberInfo;
use common\models\Relationship;
use common\models\Account;
use common\models\MemberIn;
use common\models\Parameter;
/**
* 数据库备份，还原，清空数据
*/
class DataController extends LdBaseController
{
    public $_path = null;
    public $fp;
    public $_delDirPath;

    protected function getPath() {
        if (isset ( $this->_path ))
            $this->_path = $this->_path;
        else
            $this->_path = Yii::$app->basePath . '/_backup/';
        
        if (! file_exists ( $this->_path )) {
            mkdir ( $this->_path );
            chmod ( $this->_path, 0777 );
        }
        return $this->_path;
    }

    protected function getDelDirPath() {
        if (!isset ( $this->_path )) {
            $this->getPath();
        }
        $this->_delDirPath = Yii::$app->basePath . '/_backup_del/';
        if (! file_exists ( $this->_delDirPath )) {
            try {
                mkdir ( $this->_delDirPath );
                chmod ( $this->_delDirPath, 0777 );
            } catch (\Exception $e) {
                return false;
            }
        }
        return $this->_delDirPath;
    }


    // 清空数据
    public function actionWipeData()
    {
        return $this->render('wipe-data');
    }

    public function actionWipeDataAjax()
    {
        if (Yii::$app->request->isPost) {
            $this->_delTableDataWithName('member', 'id>1');
            $this->_delTableDataWithName('account');
            $this->_delTableDataWithName('account_change');
            $this->_delTableDataWithName('account_transfer');
            $this->_delTableDataWithName('address');
            $this->_delTableDataWithName('bankcard');
            $this->_delTableDataWithName('bonus');
            // $this->_delTableDataWithName('cat');
            $this->_delTableDataWithName('fenhong');
            $this->_delTableDataWithName('member_info');
            $this->_delTableDataWithName('shop_car');
            $this->_delTableDataWithName('order');
            $this->_delTableDataWithName('order_goods');
            $this->_delTableDataWithName('relationship');
            $this->_delTableDataWithName('report');
            $this->_delTableDataWithName('report_msg');
            $this->_delTableDataWithName('shop_car');
            $this->_delTableDataWithName('recharge');
			$this->_delTableDataWithName('ap_agent');
            $this->_delTableDataWithName('to_cash');
            $this->_delTableDataWithName('member_in');
			$this->_delTableDataWithName('account_history');
            $this->_SET_AUTO_INCREMENT('member', 2);

            $this->addAccount(1);
            $this->addRelationship(1);
            $this->addMemberInfo(1);
            $this->addMemberIn(1);

            Member::updateAll(['activate'=>time()]);
            Parameter::updateAll(['val'=>0], ['id'=>26]);

            Yii::$app->session->setFlash('success', '清空数据成功');
            return $this->renderJsonSuccess('清空数据成功');
        }
    }


    private function addMemberIn($memberId)
    {
        $model = new MemberIn;
        $model->member_id = $memberId;
        $model->save(false);
    }

    private function addAccount($memberId)
    {
        $model = new Account;
        $model->member_id = $memberId;
        $model->save(false);
    }

    private function addRelationship($memberId)
    {
        $model = new Relationship;
        $model->member_id = $memberId;
        if ($memberId == 1) {
            $model->p_path = ',';
            $model->re_path = ',';
        }
        $model->save(false);
    }

    private function addMemberInfo($memberId)
    {
        $model = new MemberInfo;
        $model->member_id = $memberId;
        $model->save(false);
    }

    

    //清空表数据 参数： 表名， 清除条件
    private function _delTableDataWithName($tableName='',$map='') {
        if (!empty($map)) {
            Yii::$app->db->createCommand('DELETE FROM {{%'.$tableName.'}} WHERE '.$map)->execute();
        }
        else {
            Yii::$app->db->createCommand('TRUNCATE {{%'.$tableName.'}}')->execute();
        }
    }

    private function _SET_AUTO_INCREMENT($tableName='', $num=1) {
        Yii::$app->db->createCommand('ALTER TABLE {{%'.$tableName.'}} AUTO_INCREMENT='.$num)->execute();
    }

    /**
    *
    *
    * 数据库备份
    *
    */
    public function actionBackup()
    {
        $list = $this->getFileList();
        return $this->render('backup', ['list'=>$list]);
    }

    protected function getFileList($ext = 'backup-*-data', $dirname=false) {
        $path = $this->path;
        if ($dirname) {
            $path .= $dirname.'/';
        }
        $dataArray = array();
        $list = array();
        $list_files = glob( $path . $ext );
        if ($list_files) {
            $list = array_map ( 'basename', $list_files );
            sort ( $list );
        }
        return $list;
    }

    public function actionGetDbInfo()
    {
        $randmk = $this->mkD();
        if (!$randmk) {
            return $this->renderJsonError('创建文件夹失败');
        }

        $data['tables'] = $this->getTables();
        // if (!$this->createTable($data['tables'],$randmk)) {
        //     return $this->renderJsonError('创建数据表时失败');
        // }
        $amount = 0;
        foreach ($data['tables'] as $key => $tb) {
            $amount += $this->getTablesListCount($tb);
        }
        $data['amount'] = $amount;
        $data['dir'] = $randmk;
        return $this->renderJsonSuccess('初始化成功',$data);
    }

    public function actionCreateTableData()
    {
        $tableName = Yii::$app->request->post('tb');
        $dir = Yii::$app->request->post('dir');
        $limitBegin = Yii::$app->request->post('index');
        $limit = Yii::$app->request->post('limit');
        if (!$this->createTable($tableName,$dir)) {
            return $this->renderJsonError('创建数据表时失败');
        }
        $create = $this->createFile($dir.'/data_'.$limitBegin.'_to_'.$tableName);
        if (!$create) {
            $this->renderJsonError("文件创建失败");
        }
        $amount = $this->getData($tableName, $limitBegin, $limit);
        $data = array();
        $data['amount'] = $amount;
        return $this->renderJsonSuccess('备份成功', $data);
    }

    public function createFile($dir)
    {
        // 先不做多文件了，实现再说吧
        $ext = '.sql';
        $dir = $this->getPath() . $dir . $ext;
        $this->fp = fopen ( $dir, 'a+' );
        if ($this->fp == null)
        {
            return false;
        }
    }

    public function getData($tableName,$limitBegin, $limitAmount) {
        $sql = 'SELECT * FROM ' . $tableName . ' LIMIT '. $limitBegin . ',' . $limitAmount;
        $cmd = Yii::$app->db->createCommand ( $sql );
        $dataReader = $cmd->query();
        // if ($this->fp) {
        //     $this->writeComment ( 'TABLE DATA ' . $tableName );
        // }
        $i = 0;
        $data_string = '';
        foreach ( $dataReader as $data ) {
            if ($i == 0) {
                $itemNames = array_keys ( $data );
                $itemNames = array_map ( "addslashes", $itemNames );
                $items = join ( '`,`', $itemNames );
                $data_string = "LOCK TABLES `$tableName` WRITE;".PHP_EOL;
                $data_string .= "/*!40000 ALTER TABLE `$tableName` DISABLE KEYS */;".PHP_EOL;
                $data_string .= "INSERT INTO `$tableName` (`$items`) VALUES ";
            }
            $itemValues = array_values($data);
            $valueString = '';
            foreach ($itemValues as $value) {
                if (gettype($value)=='string'){
                    $value= Yii::$app->db->quoteValue($value);
                    $valueString.="$value,";
                }
                elseif (empty($value)) {
                    $valueString.="NULL,";
                }
                else {
                    $valueString.="$value,";
                }
            }
            $valueString=substr($valueString, 0, strlen($valueString)-1);//去除","
            $valueString = "(" . $valueString . "),";
            $values = $valueString;
            if ($values != "") {
                $data_string .= $values;
                $i++;
            }
        }
        if ($data_string != '') {
            $data_string=substr($data_string, 0, strlen($data_string)-1);//去除","
            $data_string .=  ";" . PHP_EOL;
            $data_string .= "/*!40000 ALTER TABLE `$tableName` ENABLE KEYS */;".PHP_EOL;
            $data_string .= "UNLOCK TABLES;".PHP_EOL;

            if ($this->fp) {
                fwrite ( $this->fp, $data_string );
            }
        }
        
        // if ($this->fp) {
        //     $this->writeComment ( 'TABLE DATA ' . $tableName );
        //     $final = PHP_EOL . PHP_EOL . PHP_EOL;
        //     fwrite ( $this->fp, $final );
        // }
        return $i;
    }

    public function createTable($table,$dir)
    {
        $file_name = $this->getPath() . '/' . $dir . '/create_table_'.$table.'.sql';
        $this->fp = fopen ( $file_name, 'w' );
        if ($this->fp == null)
        {
            return false;
        }

        // fwrite ( $this->fp, "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;" . PHP_EOL );
        // fwrite ( $this->fp, "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;" . PHP_EOL );
        // fwrite ( $this->fp, "/*!40101 SET NAMES utf8 */;" . PHP_EOL );
        // fwrite ( $this->fp, "/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;" . PHP_EOL );
        // fwrite ( $this->fp, "/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;" . PHP_EOL );
        // fwrite ( $this->fp, "/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;" . PHP_EOL );



        // foreach ($tables as $key => $tb) {
        //     $this->getColumns($tb);
        // }
        $this->getColumns($table);
        // fwrite ( $this->fp, "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;" . PHP_EOL );
        // fwrite ( $this->fp, "/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;" . PHP_EOL );
        // fwrite ( $this->fp, "/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;" . PHP_EOL );
        // fwrite ( $this->fp, "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;" . PHP_EOL );
        // fwrite ( $this->fp, "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;" . PHP_EOL );
        // fwrite ( $this->fp, "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;" . PHP_EOL );

        fclose ( $this->fp );
        $this->fp = null;
        return true;
    }

    public function writeComment($string) {
        fwrite ( $this->fp, '-- -------------------------------------------' . PHP_EOL );
        fwrite ( $this->fp, '-- ' . $string . PHP_EOL );
        fwrite ( $this->fp, '-- -------------------------------------------' . PHP_EOL );
    }

    public function getColumns($tableName) {
        $sql = 'SHOW CREATE TABLE ' . $tableName;
        $cmd = Yii::$app->db->createCommand ( $sql );
        $table = $cmd->queryOne ();
        
        $create_query = $table ['Create Table'] . ';';
        
        $create_query = preg_replace ( '/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create_query );
        $create_query = preg_replace ( '/AUTO_INCREMENT\s*=\s*([0-9])+/', '', $create_query );
        if ($this->fp) {
            $this->writeComment ( 'TABLE `' . addslashes ( $tableName ) . '`' );
            $final = 'DROP TABLE IF EXISTS `' . addslashes ( $tableName ) . '`;' . PHP_EOL . $create_query . PHP_EOL . PHP_EOL;
            fwrite ( $this->fp, $final );
        } else {
            $this->tables [$tableName] ['create'] = $create_query;
            return $create_query;
        }
    }

    public function mkD()
    {
        $fdirmk = $this->getPath();
        $date=Date('Y-m-d-H-i-s');  //不能用冒号，否则创建文件失败！
        $randmk = 'backup-'.$date.'-'.rand(1000,9999).'-data';
        $dir = $fdirmk.'/'.$randmk;
        if(!is_dir($dir)){
            mkdir($dir, 0777);  //创建文件夹
        }
        else {
            chmod($dir, 0777);  //改变文件模式
        }
        if (is_dir($dir)) {
            return $randmk;
        }
        else {
            return false;
        }
    }

    public function getTables() {
        $sql = 'SHOW TABLES';
        $cmd = Yii::$app->db->createCommand ( $sql );
        $tables = $cmd->queryColumn ();
        return $tables;
    }

    public function getTablesListCount($tableName='')
    {
        $sql = 'SELECT count(*) FROM '.$tableName;
        $cmd = Yii::$app->db->createCommand ( $sql );
        $re = $cmd->queryColumn();
        return $re[0];
    }

    // 数据库还原
    public function actionGetSqlFiles()
    {
        $dir = Yii::$app->request->post('dir');
        $list = $this->getFileList('create_table_*.sql', $dir);
        $datalist = $this->getFileList('data_*_to_*.sql', $dir);
        $count = count($list) + count($datalist);
        return $this->renderJsonSuccess($count, $list);
    }

    public function actionGetDataFiles()
    {
        $dir = Yii::$app->request->post('dir');
        return $this->renderJsonSuccess('获取文件列表成功', $list);
    }

    public function actionExecSql()
    {
        $fdir = Yii::$app->request->post('fdir');
        $f = Yii::$app->request->post('f');
        $index = Yii::$app->request->post('index');
        $path = $this->path;
        $path .= $fdir;
        $path .= '/';
        if ($index == 0) { // 执行第一个内容文件时，要重建表
            $this->execSqlFile($path.$f);
        }
        $subF = substr($f,13);
        $list = array();
        $list_files = glob( $path . 'data_*_to_' . $subF );
        if ($list_files) {
            $list = array_map ( 'basename', $list_files );
            sort ( $list );
        }
        if (isset($list[$index])) {
            $this->execSqlFile($path . $list[$index]);
            return $this->renderJsonSuccess('nex');
        }
        else {
            return $this->renderJsonSuccess('ok');
        }
    }

    public function execSqlFile($sqlFile) {
        $message = "ok";
        if (file_exists ( $sqlFile )) {
            $sqlArray = file_get_contents ( $sqlFile );
            $beginStr = "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;";
            $endStr = "/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;";
            $sqlArray = $beginStr.$sqlArray.$endStr;
            $cmd = Yii::$app->db->createCommand ( $sqlArray );
            try {
                $cmd->execute();
            } catch ( \Exception $e ) {
                $message = $e->getMessage ();
                exit;
            }
        }
        return $sqlArray;
    }

    public function renderJsonObject($info='', $status=1, $data=[]) 
    {
        return json_encode(['info'=>$info, 'status'=>$status, 'data'=>$data]);
    }

    public function renderJsonSuccess($info='', $data=[])
    {
        return $this->renderJsonObject($info,1,$data);
    }

    public function renderJsonError($info='', $data=[])
    {
        return $this->renderJsonObject($info,0,$data);
    }

    public function actionDelBackupDir()
    {
        $dir = Yii::$app->request->post('dir');
        $oldDir = $this->getPath() . $dir;
        if (!is_dir($oldDir)) {
            return $this->renderJsonError('删除失败');
        }
        $delDir = $this->getDelDirPath();
        if (!$delDir) {
            return $this->renderJsonError('删除失败，没有权限');
        }
        $newDir = $this->getDelDirPath() . $dir;
        if (rename($oldDir, $newDir)) {
            return $this->renderJsonSuccess('删除成功');
        }
        else {
            return $this->renderJsonError('删除失败');
        }
        
    }


}