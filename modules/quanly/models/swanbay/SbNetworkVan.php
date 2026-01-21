<?php

namespace app\modules\quanly\models\swanbay;

use Yii;

/**
 * This is the model class for table "sb_network_van".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $vatlieu
 * @property string|null $covan
 */
class SbNetworkVan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sb_network_van';
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
            [['vatlieu', 'covan'], 'string', 'max' => 20],
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
            'covan' => Yii::t('app', 'Covan'),
        ];
    }
}
