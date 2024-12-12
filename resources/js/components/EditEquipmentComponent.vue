<template>
    <div class="container">
        <h1>Редактирование оборудования</h1>
        <serial-mask-info-component></serial-mask-info-component>
        <form @submit.prevent="updateEquipment">
            <table class="table table-striped w-auto">
                <thead>
                <tr>
                    <th scope="col">Тип оборудования</th>
                    <th scope="col">Серийный номер</th>
                    <th scope="col">Описание</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="w-auto">
                        <select class="ms-2" id="equipmentType" v-model="editedEquipment.equipmentTypeId" required>
                            <option value="" disabled selected>Выберите тип оборудования</option>
                            <option v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}, маска формата: {{ type.sn_mask }}</option>
                        </select>
                    </td>
                    <td>
                        <input class="ms-2" type="text" id="serialNumber" v-model="editedEquipment.serial_number" required>
                    </td>
                    <td>
                        <textarea class="ms-2" id="description" v-model="editedEquipment.description" rows="5"></textarea>
                    </td>
                </tr>
                </tbody>
            </table>
            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Обновить</button>
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
    name: "EditEquipmentComponent",
    components: {SerialMaskInfoComponent},
    data() {
        return {
            editedEquipment: {},
            types: []
        };
    },
    created() {
        this.fetchEquipment();
        this.fetchTypes();
    },
    methods: {
        fetchEquipment() {
            axios.get(`/api/equipment/${this.$route.params.id}`)
                .then(response => {
                    this.editedEquipment = response.data.data;
                })
                .catch(error => console.log(error));
        },
        updateEquipment() {
            const { equipmentTypeId, serial_number, description } = this.editedEquipment;
            axios.put(`/api/equipment/${this.$route.params.id}`, {
                equipment_type_id: equipmentTypeId,
                serial_number: serial_number,
                description: description
            }).then(() => {
                alert("Изменения сохранены!");
                this.$router.push({ name: 'Equipments' });
            }).catch(error => {
                alert("Произошла ошибка при сохранении изменений.");
                console.log(error);
            });
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
