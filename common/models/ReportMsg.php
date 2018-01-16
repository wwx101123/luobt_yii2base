<?php

namespace common\models;

use Yii;

/**
--
-- 表的结构 `ld_report_msg`
--

CREATE TABLE `ld_report_msg` (
`id` int(11) NOT NULL,
  `report_id` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `create_time` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ld_report_msg`
--
ALTER TABLE `ld_report_msg`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ld_report_msg`
--
ALTER TABLE `ld_report_msg`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
 */
class ReportMsg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%report_msg}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['report_id', 'content', 'name'], 'required'],
            [['report_id', 'create_time'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'report_id' => Yii::t('app', 'Report ID'),
            'content' => Yii::t('app', 'Content'),
            'create_time' => Yii::t('app', 'Create Time'),
            'name' => Yii::t('app', 'Name'),
        ];
    }
    public function UserReport()
    {
        if ($this->save()) {
            Report::updateAll(['is_read'=>0],'id = :ID',[':ID'=>$this->report_id]);
            return true;
        }
    }
    public function AdminReport()
    {
        if ($this->save()) {
            Report::updateAll(['is_read'=>1],'id = :ID',[':ID'=>$this->report_id]);
            return true;
        }
    }

      public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->create_time = time();
            }
            
            return true;
        } else {
            return false;
        }
    }
}
