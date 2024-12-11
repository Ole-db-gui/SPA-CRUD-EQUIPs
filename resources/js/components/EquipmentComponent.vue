<template>
    <div class="container">
        <h1>Список оборудования</h1>
        <div class="row">
            <div class="col-md-12">
                <input type="text" placeholder="Поиск по серийному номеру или описанию" v-model="searchQuery" />
                <span class="m-lg-3"><router-link :to="{ name: 'addEquipment'}" class="btn btn-primary">Добавить</router-link></span>
            </div>
        </div>
        <br />
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Тип оборудования</th>
                        <th scope="col">Серийный номер</th>
                        <th scope="col">Описание</th>
                        <th scope="col">Дата создания</th>
                        <th scope="col">Дата обновления</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="equipment in filteredEquipments" :key="equipment.id">
                        <td>{{ equipment.id }}</td>
                        <td>{{ equipment.equipmentType.name }}</td>
                        <td>{{ equipment.serial_number }}</td>
                        <td>{{ equipment.description }}</td>
                        <td>{{ equipment.created_at | formatDate }}</td>
                        <td>{{ equipment.updated_at | formatDate }}</td>
                        <td>
                            <router-link :to="{ name: 'editEquipment', params: { id: equipment.id }}" class="btn btn-primary">Редактировать</router-link>
                            <button class="btn btn-danger ml-2" @click="deleteEquipment(equipment.id)">Удалить</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import CreateEquipmentTypeComponent from "@/components/CreateEquipmentTypeComponent.vue";
export default {
    name: "EquipmentComponent",
    data() {
        return {
            equipments: [
                {
                    id: 44,
                    equipmentType: {name: 'makrelikha'},
                    serial_number: 'HEHEkomock12345',
                    description: 'ti komochek',

                }
            ],
            searchQuery: ''
        };
    },
    computed: {
        filteredEquipments() {
            return this.equipments.filter(equipment => {
                return equipment.serial_number.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    equipment.description.toLowerCase().includes(this.searchQuery.toLowerCase());
            });
        }
    },
    created() {
        this.fetchData();
    },
    methods: {
        fetchData() {
            axios.get('http://localhost:8000/api/equipments')
                .then(response => {
                    this.equipments = response.data;
                })
                .catch(error => console.log(error));
        },
        deleteEquipment(id) {
            if (confirm("Вы уверены, что хотите удалить это оборудование?")) {
                axios.delete(`http://localhost:8000/api/equipments/${id}`)
                    .then(() => {
                        this.fetchData();
                    })
                    .catch(error => console.log(error));
            }
        }
    },
    components: {
        CreateEquipmentTypeComponent
    }
};
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
}
</style>
