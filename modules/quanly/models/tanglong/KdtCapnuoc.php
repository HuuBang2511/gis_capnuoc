<?php

namespace app\modules\quanly\models\tanglong;

use Yii;

/**
 * This is the model class for table "kdt_capnuoc".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $FID_
 * @property string|null $Entity
 * @property string|null $Layer
 * @property int|null $Color
 * @property string|null $Linetype
 * @property float|null $Elevation
 * @property int|null $LineWt
 * @property string|null $RefName
 */
class KdtCapnuoc extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kdt_capnuoc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['FID_', 'Color', 'LineWt'], 'default', 'value' => null],
            [['FID_', 'Color', 'LineWt'], 'integer'],
            [['Elevation'], 'number'],
            [['Entity'], 'string', 'max' => 16],
            [['Layer', 'Linetype', 'RefName'], 'string', 'max' => 254],
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
            'FID_' => Yii::t('app', 'Fid'),
            'Entity' => Yii::t('app', 'Entity'),
            'Layer' => Yii::t('app', 'Layer'),
            'Color' => Yii::t('app', 'Color'),
            'Linetype' => Yii::t('app', 'Linetype'),
            'Elevation' => Yii::t('app', 'Elevation'),
            'LineWt' => Yii::t('app', 'Line Wt'),
            'RefName' => Yii::t('app', 'Ref Name'),
        ];
    }
}
