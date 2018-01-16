<?php

namespace common\widgets;

use Yii;

class LinkPager extends yii\widgets\LinkPager
{
  public $prevPageLabel = '上一页';
  public $firstPageLabel='首页';
  public $nextPageLabel = '下一页';
  public $lastPageLabel='尾页';
  public function init () {
     parent::init();
  }  
}