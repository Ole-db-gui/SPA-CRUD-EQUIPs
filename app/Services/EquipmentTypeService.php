<?php

namespace App\Services;

use App\Models\EquipmentType;
use Illuminate\Support\Facades\DB;

/**
 * Сервис для работы с типами оборудования.
 */
class EquipmentTypeService
{
    /**
     * Получение списка типов оборудования с фильтрацией и пагинацией.
     *
     * @param int $page Номер текущей страницы.
     * @param int $perPage Количество записей на странице.
     * @param string|null $q Строка поиска (по умолчанию null).
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator Список типов оборудования с учётом фильтрации и пагинации.
     */
    public function getAllWithFilterAndPagination(int $page, int $perPage, string $q = null)
    {
        $query = EquipmentType::query();

        if (!empty($q)) {
            $query->where('name', 'like', "%$q%");
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
}
