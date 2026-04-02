<?php

namespace app\modules\quanly\models\tanglong;

use Yii;

/**
 * This is the model class for table "kdt_hathe".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $OBJECTID
 * @property string|null $ma
 */
class KdtHathe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kdt_hathe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['OBJECTID'], 'default', 'value' => null],
            [['OBJECTID'], 'integer'],
            [['ma'], 'string', 'max' => 200],
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
            'OBJECTID' => Yii::t('app', 'Objectid'),
            'ma' => Yii::t('app', 'Ma'),
        ];
    }
}
