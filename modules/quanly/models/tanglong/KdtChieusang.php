<?php

namespace app\modules\quanly\models\tanglong;

use Yii;

/**
 * This is the model class for table "kdt_chieusang".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $FID_
 * @property string|null $loai_den
 * @property string|null $tinh_trang
 */
class KdtChieusang extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kdt_chieusang';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['FID_'], 'default', 'value' => null],
            [['FID_'], 'integer'],
            [['loai_den'], 'string', 'max' => 50],
            [['tinh_trang'], 'string', 'max' => 200],
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
            'loai_den' => Yii::t('app', 'Loai Den'),
            'tinh_trang' => Yii::t('app', 'Tinh Trang'),
        ];
    }
}
