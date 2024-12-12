<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Модель типа оборудования.
 *
 * @property int $id Идентификатор типа оборудования.
 * @property string $name Название типа оборудования.
 * @property string $sn_mask Маска для проверки серийных номеров оборудования.
 * @property-read \App\Models\Equipment $equipment Оборудование данного типа.
 */
class EquipmentType extends Model
{
    use HasFactory;
    protected $guarded = false;
    /**
     * Имя таблицы базы данных, связанной с этой моделью.
     *
     * @var string
     */
    protected $table = 'equipment_types';

    /**
     * Связь с моделью оборудования.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function equipment()
    {
        return $this->hasOne(Equipment::class);
    }
}
