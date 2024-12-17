<?php

namespace App\Services;

use App\Http\Requests\Equipment\StoreRequest;
use App\Models\Equipment;
use App\Models\EquipmentType;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\EquipmentResource;

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

        if ($serialNumber = request()->input('serial_number')) {
            $query->where('serial_number', '=', $serialNumber);
        }

        if ($description = request()->input('description')) {
            $query->where('description', 'LIKE', '%' . $description . '%');
        }

        if ($equipmentTypeId = request()->input('equipment_type_id')) {
            $query->where('equipment_type_id', '=', $equipmentTypeId);
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

        $existingEquipment = Equipment::where('serial_number', $serialNumber)
            ->where('equipment_type_id', $equipmentTypeId)
            ->first();

        if ($existingEquipment !== null) {
            return false; // Серийный номер уже занят для данного типа оборудования
        }

        $pattern = '/^' . str_replace(
                ['N', 'A', 'a', 'X', 'Q'],
                ['[0-9]', '[A-Z]', '[a-z]', '[0-9A-Za-z]', '[-_@]'],
                $equipmentType->sn_mask
            ) . '$/i';

        return preg_match($pattern, $serialNumber);
    }

    /**
     * Создает новое оборудование на основе переданных данных.
     *
     * @param array $data Массив данных для создания оборудования. Должен содержать ключ 'equipment', который является массивом данных о каждом оборудовании.
     * @return array Массив с результатами обработки. Содержит ключи 'errors' и 'success'.
     */
    public function createEquipments(array $data) {
        $response = [
            'errors' => [],
            'success' => []
        ];

        foreach ($data['equipment'] as $key => $item) {

            if (!$this->validateSerialNumber($item['serial_number'], $item['equipment_type_id'])) {
                $response['errors'][$key][] = "Неверный формат серийного номера, либо оборудование с таким серийным номером и типом оборудования уже есть";
                continue;
            }

            try {
                $equipment = Equipment::create($item);
                $response['success'][$key][] = new EquipmentResource($equipment->load('equipmentType'));
            } catch (QueryException $e) {
                $response['errors'][$key][] = "Произошла ошибка при сохранении оборудования.";
                continue;
            }
        }

        return $response;
    }
}
