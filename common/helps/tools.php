<?php
namespace common\helps;
use yii;
use yii\web\Response;

/**
 * 
 * 自定义全局工具
 */
class tools
{
    
    public static function jsonSuccess($msg, $data = array())
    {
         Yii::$app->response->format = Response::FORMAT_JSON;
         return ['code' => true, 'msg' => $msg, 'data' => $data];
    }

    public static function jsonError($msg, $data = array())
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['code' => false, 'msg' => $msg, 'data' => $data];
    }

    public static function UrlTo($filename, $http='http://')
    {
        return $http. 'static.ld_sc.com/'. $filename;
    }

    /**
     * 设置缓存数据
     * @param $name
     * @param $value
     * @param null $duration
     * @return bool
     */
    public static function setCache($name, $value, $expirse = 3600 )
    {
        return Yii::$app->getCache()->set($name, $value, $expirse);
    }

    /**
     * 获取缓存数据
     * @param $name
     * @param null $defaultValue
     * @return mixed
     */
    public static function getCache($name)
    {
        return Yii::$app->getCache()->get($name);
    }

    /**
     * 移除缓存数据
     * @param $name
     * @param null $defaultValue
     * @return mixed
     */
    public static function removeCache($name)
    {
        return Yii::$app->getCache()->delete($name);
    }
}