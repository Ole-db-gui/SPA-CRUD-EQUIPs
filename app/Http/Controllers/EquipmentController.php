<?php

namespace App\Http\Controllers;

use App\Http\Requests\Equipment\StoreRequest;
use App\Http\Requests\Equipment\UpdateRequest;
use App\Http\Requests\Equipment\IndexRequest;
use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use App\Models\EquipmentType;
use App\Services\EquipmentService;
use Illuminate\Http\Request;

/**
 * Контроллер для управления оборудованием.
 */
class EquipmentController extends Controller
{
    /**
     * Сервис для работы с оборудованием.
     *
     * @var \App\Services\EquipmentService
     */
    private EquipmentService $service;

    /**
     * Конструктор контроллера.
     *
     * @param \App\Services\EquipmentService $service Сервис для работы с оборудованием.
     */
    public function __construct(EquipmentService $service)
    {
        $this->service = $service;
    }

    /**
     * Получение списка оборудования с фильтрацией и пагинацией.
     *
     * @param \Illuminate\Http\Request $request Запрос от клиента.
     * @return \App\Http\Resources\EquipmentResource Коллекция ресурсов оборудования.
     */
    public function index(IndexRequest $request)
    {
        $page = $request->get('page', 1); // Текущая страница
        $perPage = $request->get('per_page', 15); // Количество записей на странице
        $q = $request->input('q'); // Фильтр

        $equipments = $this->service->getAllWithFilterAndPagination($page, $perPage, $q);
        return EquipmentResource::collection($equipments);
    }

    /**
     * Создание нового оборудования.
     *
     * @param \App\Http\Requests\Equipment\StoreRequest $request Запрос с данными для создания оборудования.
     * @return \App\Http\Resources\EquipmentResource Ресурс созданного оборудования.
     */
    public function store(StoreRequest $request) {
        $response = $this->service->createEquipments($request->validated());
        return response()->json($response);
    }

    /**
     * Отображение информации об оборудовании.
     *
     * @param \App\Models\Equipment $equipment Модель оборудования.
     * @return \App\Http\Resources\EquipmentResource Ресурс оборудования.
     */
    public function show(Equipment $equipment) {
        $equipment->load('equipmentType');
        return new EquipmentResource($equipment);
    }

    /**
     * Обновление существующего оборудования.
     *
     * @param \App\Http\Requests\Equipment\UpdateRequest $request Запрос с новыми данными для оборудования.
     * @param \App\Models\Equipment $equipment Модель оборудования.
     * @return \App\Http\Resources\EquipmentResource Обновлённый ресурс оборудования.
     */
    public function update(UpdateRequest $request, Equipment $equipment) {
        $preValidated = $request->all();
        if (!$this->service->validateSerialNumber($preValidated['serial_number'], $preValidated['equipment_type_id'])) {
            return response()->json(['errors' => ['Неверный формат серийного номера, либо оборудование с таким серийным номером и типом оборудования уже есть']], 422);
        }
        $data = $request->validated();
        $equipment->update($data);
        return new EquipmentResource($equipment);
    }

    /**
     * Удаление оборудования.
     *
     * @param \App\Models\Equipment $equipment Модель оборудования.
     * @return \Illuminate\Http\JsonResponse Пустой ответ с кодом 204.
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return response()->json([], 204);
    }
}
