<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Модель оборудования.
 *
 * @property int $id Идентификатор оборудования.
 * @property string $serial_number Серийный номер оборудования.
 * @property string|null $description Описание оборудования.
 * @property int $equipment_type_id Идентификатор типа оборудования.
 * @property \Carbon\Carbon|null $deleted_at Дата и время удаления записи (если применимо).
 * @property-read \App\Models\EquipmentType $equipmentType Тип оборудования.
 */
class Equipment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = false;
    /**
     * Имя таблицы базы данных, связанной с этой моделью.
     *
     * @var string
     */
    protected $table = 'equipment';

    /**
     * Связь с моделью типа оборудования.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function equipmentType()
    {
        return $this->belongsTo(EquipmentType::class);
    }
}
