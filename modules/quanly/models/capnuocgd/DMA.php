<?php

namespace app\modules\quanly\models\capnuocgd;

use app\modules\quanly\base\QuanlyBaseModel;
use Yii;

/**
 * This is the model class for table "v2_4326_DMA".
 *
 * @property int $id
 * @property string|null $geom
 * @property int|null $objectid
 * @property string|null $madma
 * @property int|null $sodaunoi
 * @property int|null $sometong
 * @property int|null $sovan
 * @property int|null $sotru
 */
class DMA extends QuanlyBaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'v2_4326_DMA';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['geom'], 'string'],
            [['objectid', 'sodaunoi', 'sometong', 'sovan', 'sotru'], 'default', 'value' => null],
            [['objectid', 'sodaunoi', 'sometong', 'sovan', 'sotru'], 'integer'],
            [['madma'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'geom' => 'Geom',
            'objectid' => 'Objectid',
            'madma' => 'Mã DMA',
            'sodaunoi' => 'Số đầu nối',
            'sometong' => 'Số mét tổng',
            'sovan' => 'Số van',
            'sotru' => 'Số trụ',
        ];
    }
}
