<template>
    <div class="container">
        <h1>Добавление нового оборудования</h1>
        <serial-mask-info-component></serial-mask-info-component>
        <form @submit.prevent="addEquipments">
            <!-- Таблица для добавления оборудования -->
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Тип оборудования</th>
                    <th scope="col">Серийный номер</th>
                    <th scope="col">Описание</th>
                    <th scope="col">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(equipment, index) in equipments" :key="index">
                    <td>
                        <select v-model="equipment.equipment_type_id" required>
                            <option value="" disabled selected>Выберите тип оборудования</option>
                            <option v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}, маска SN: {{ type.sn_mask }}</option>
                        </select>
                    </td>
                    <td>
                        <input type="text" v-model="equipment.serial_number" required>
                    </td>
                    <td>
                        <textarea v-model="equipment.description" rows="2"></textarea>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" @click="removeEquipment(index)">Удалить</button>
                    </td>
                </tr>
                </tbody>
            </table>

            <!-- Кнопки управления -->
            <div class="d-flex justify-content-between align-items-center">
                <button type="button" class="btn btn-success" @click="addNewRow">Добавить строку</button>
                <div>
                    <button type="submit" class="btn btn-primary">Добавить оборудование</button>
                    <button type="button" class="btn btn-secondary ms-2" @click="$router.push({ name: 'Equipments' })">Отмена</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import SerialMaskInfoComponent from "@/components/SerialMaskInfoComponent.vue";
export default {
    name: "AddEquipmentComponent",
    components: {SerialMaskInfoComponent},
    data() {
        return {
            equipments: [
                {
                    equipment_type_id: '',
                    serial_number: '',
                    description: ''
                }
            ],
            types: []
        };
    },
    created() {
        this.fetchTypes();
    },
    methods: {
        async addEquipments() {
            const data = {
                equipment: this.equipments
            };

            try {
                const response = await axios.post('/api/equipment', data);

                if (response.data.errors && response.data.errors.length > 0) {
                    let errorMessages = [];

                    // Проверяем, является ли значение массивом
                    if (Array.isArray(response.data.errors)) {
                        // Если это массив, проверяем его длину
                        if (response.data.errors.length > 0) {
                            // Сначала проверяем, является ли первый элемент массива объектом
                            if (typeof response.data.errors[0] === 'object') {
                                // Проходимся по каждому объекту в массиве ошибок
                                response.data.errors.forEach((errorObj, index) => {
                                    // Получаем ключ объекта (номер строки)
                                    const rowNumber = Object.keys(errorObj)[0];

                                    // Добавляем сообщение об ошибке к общему списку
                                    errorMessages.push(`Ошибка для строки ${rowNumber}: ${errorObj[rowNumber]}`);
                                });
                            } else {
                                // Если ошибки пришли в виде простого массива строк
                                response.data.errors.forEach((errorMessage, index) => {
                                    errorMessages.push(`Ошибка для строки ${index + 1}: ${errorMessage}`);
                                });
                            }
                        }
                    } else {
                        // Если это объект, проходимся по его свойствам
                        for (let key in response.data.errors) {
                            errorMessages.push(`${key}: ${response.data.errors[key]}`);
                        }
                    }

                    if (errorMessages.length > 0) {
                        alert(errorMessages.join('\n'));
                    }
                } else {
                    alert('Оборудование успешно добавлено!');
                    this.equipments = [{ equipment_type_id: '', serial_number: '', description: '' }];
                    this.$router.push({ name: 'Equipments' });
                }
            } catch (error) {
                alert('Произошла ошибка при добавлении оборудования.');
                console.log(error);
            }
        },
        addNewRow() {
            this.equipments.push({
                equipment_type_id: '',
                serial_number: '',
                description: ''
            });
        },
        removeEquipment(index) {
            this.equipments.splice(index, 1);
        },
        fetchTypes() {
            axios.get('/api/equipment-type')
                .then(response => {
                    this.types = response.data.data;
                })
                .catch(error => console.log(error));
        }
    }
};
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
}
</style>
