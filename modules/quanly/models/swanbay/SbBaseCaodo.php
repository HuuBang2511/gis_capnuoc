<?php

namespace app\modules\quanly\models\swanbay;

use Yii;

/**
 * This is the model class for table "sb_base_caodo".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property float|null $caodo
 */
class SbBaseCaodo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sb_base_caodo';
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
            [['caodo'], 'number'],
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
            'caodo' => Yii::t('app', 'Caodo'),
        ];
    }
}
