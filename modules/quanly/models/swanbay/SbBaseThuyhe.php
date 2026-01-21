<?php

namespace app\modules\quanly\models\swanbay;

use Yii;

/**
 * This is the model class for table "sb_base_thuyhe".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property float|null $shape_leng
 * @property float|null $shape_area
 */
class SbBaseThuyhe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sb_base_thuyhe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id', 'objectid'], 'default', 'value' => null],
            [['id', 'objectid'], 'integer'],
            [['geom'], 'string'],
            [['shape_leng', 'shape_area'], 'number'],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'geom' => Yii::t('app', 'Geom'),
            'objectid' => Yii::t('app', 'Objectid'),
            'shape_leng' => Yii::t('app', 'Shape Leng'),
            'shape_area' => Yii::t('app', 'Shape Area'),
        ];
    }
}
