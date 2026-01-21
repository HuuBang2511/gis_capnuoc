<?php

namespace app\modules\quanly\models\swanbay;

use Yii;

/**
 * This is the model class for table "sb_network_ongphanphoi".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $vatlieu
 * @property string|null $coong
 * @property string|null $chieudai
 * @property float|null $shape_leng
 */
class SbNetworkOngphanphoi extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sb_network_ongphanphoi';
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
            [['shape_leng'], 'number'],
            [['vatlieu', 'chieudai'], 'string', 'max' => 25],
            [['coong'], 'string', 'max' => 20],
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
            'vatlieu' => Yii::t('app', 'Vatlieu'),
            'coong' => Yii::t('app', 'Coong'),
            'chieudai' => Yii::t('app', 'Chieudai'),
            'shape_leng' => Yii::t('app', 'Shape Leng'),
        ];
    }
}
