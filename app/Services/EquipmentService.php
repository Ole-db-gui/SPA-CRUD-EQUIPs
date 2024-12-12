<?php

namespace App\Services;

use App\Models\Equipment;
use App\Models\EquipmentType;
use Illuminate\Support\Facades\DB;

/**
 * Сервис для работы с оборудованием.
 */
class EquipmentService
{
    /**
     * Получение списка оборудования с фильтрацией и пагинацией.
     *
     * @param int $page Номер текущей страницы.
     * @param int $perPage Количество записей на странице.
     * @param string|null $q Строка поиска (по умолчанию null).
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator Список оборудования с учётом фильтрации и пагинации.
     */
    public function getAllWithFilterAndPagination(int $page, int $perPage, string $q = null)
    {
        $query = Equipment::query()->with('equipmentType');

        if (!empty($q)) {
            $query->where(function ($subQuery) use ($q) {
                $subQuery->where('serial_number', 'LIKE', '%' . $q . '%')
                    ->orWhere('description', 'LIKE', '%' . $q . '%');
            });

            $query->join('equipment_types', 'equipment.equipment_type_id', '=', 'equipment_types.id')
                ->where('equipment_types.name', 'LIKE', '%' . $q . '%');
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Проверка корректности формата серийного номера оборудования.
     *
     * @param string $serialNumber Серийный номер оборудования.
     * @param int $equipmentTypeId Идентификатор типа оборудования.
     * @return bool Результат проверки: true, если формат верен, иначе false.
     */
    public function validateSerialNumber($serialNumber, $equipmentTypeId)
    {
        $equipmentType = EquipmentType::findOrFail($equipmentTypeId);
        $pattern = '/^' . str_replace(
                ['N', 'A', 'a', 'X', 'Q'],
                ['[0-9]', '[A-Z]', '[a-z]', '[0-9A-Za-z]', '[-_@]'],
                $equipmentType->sn_mask
            ) . '$/i';

        return preg_match($pattern, $serialNumber);
    }
}
