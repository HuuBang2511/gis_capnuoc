<?php

namespace app\modules\quanly\models\swanbay;

use Yii;

/**
 * This is the model class for table "sb_base_giaothong".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property float|null $shape_leng
 * @property float|null $shape_area
 * @property string|null $loaimat
 */
class SbBaseGiaothong extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sb_base_giaothong';
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
            [['loaimat'], 'string', 'max' => 10],
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
            'loaimat' => Yii::t('app', 'Loaimat'),
        ];
    }
}
