<?php

namespace App\Http\Controllers;

use App\Models\EquipmentType;
use App\Services\EquipmentTypeService;
use Illuminate\Http\Request;
use App\Http\Resources\EquipmentTypeResource;
use App\Http\Requests\EquipmentType\StoreRequest;
use App\Http\Requests\EquipmentType\UpdateRequest;

/**
 * Контроллер для управления типами оборудования.
 */
class EquipmentTypeController extends Controller
{
    /**
     * Сервис для работы с типами оборудования.
     *
     * @var \App\Services\EquipmentTypeService
     */
    private EquipmentTypeService $service;

    /**
     * Конструктор контроллера.
     *
     * @param \App\Services\EquipmentTypeService $service Сервис для работы с типами оборудования.
     */
    public function __construct(EquipmentTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * Получение списка типов оборудования с фильтрацией и пагинацией.
     *
     * @param \Illuminate\Http\Request $request Запрос от клиента.
     * @return \App\Http\Resources\EquipmentTypeResource Коллекция ресурсов типов оборудования.
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1); // Текущая страница
        $perPage = $request->get('per_page', 15); // Количество записей на странице
        $q = $request->input('q');

        $types = $this->service->getAllWithFilterAndPagination($page, $perPage, $q);
        return EquipmentTypeResource::collection($types);
    }

    /**
     * Создание нового типа оборудования.
     *
     * @param \App\Http\Requests\EquipmentType\StoreRequest $request Запрос с данными для создания типа оборудования.
     * @return \App\Http\Resources\EquipmentTypeResource Ресурс созданного типа оборудования.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $equipmentType = EquipmentType::create($data);
        return new EquipmentTypeResource($equipmentType);
    }

    /**
     * Отображение информации о конкретном типе оборудования.
     *
     * @param \App\Models\EquipmentType $equipmentType Модель типа оборудования.
     * @return \App\Http\Resources\EquipmentTypeResource Ресурс типа оборудования.
     */
    public function show(EquipmentType $equipmentType)
    {
        return new EquipmentTypeResource($equipmentType);
    }

    /**
     * Обновление существующего типа оборудования.
     *
     * @param \App\Http\Requests\EquipmentType\UpdateRequest $request Запрос с новыми данными для типа оборудования.
     * @param \App\Models\EquipmentType $equipmentType Модель типа оборудования.
     * @return \App\Http\Resources\EquipmentTypeResource Обновлённый ресурс типа оборудования.
     */
    public function update(UpdateRequest $request, EquipmentType $equipmentType)
    {
        $data = $request->validated();
        $equipmentType->update($data);
        return new EquipmentTypeResource($equipmentType);
    }

    /**
     * Удаление типа оборудования.
     *
     * @param \App\Models\EquipmentType $equipmentType Модель типа оборудования.
     * @return \Illuminate\Http\JsonResponse Пустой ответ с кодом 204.
     */
    public function destroy(EquipmentType $equipmentType)
    {
        $equipmentType->delete();
        return response()->json([], 204);
    }
}
