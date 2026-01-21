<?php

namespace app\modules\quanly\models\swanbay;

use Yii;

/**
 * This is the model class for table "sb_network_trucuuhoa".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $loaitru
 * @property string|null $vatlieu
 * @property string|null $cotru
 */
class SbNetworkTrucuuhoa extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sb_network_trucuuhoa';
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
            [['loaitru'], 'string', 'max' => 25],
            [['vatlieu', 'cotru'], 'string', 'max' => 20],
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
            'loaitru' => Yii::t('app', 'Loaitru'),
            'vatlieu' => Yii::t('app', 'Vatlieu'),
            'cotru' => Yii::t('app', 'Cotru'),
        ];
    }
}
