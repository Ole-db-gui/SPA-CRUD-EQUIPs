<template>
    <div class="container">
        <h1>Список оборудования</h1>
        <div class="row">
            <div class="col-md-12">
                <input class="w-50" type="text" placeholder="Поиск по типу оборудования/серийному номеру/описанию" v-model="searchQuery" />
                <span class="m-lg-3"><router-link :to="{ name: 'CreateEquipment'}" class="btn btn-primary">Добавить</router-link></span>
                <span class="m-lg-3"><router-link :to="{ name: 'EquipmentTypes'}" class="btn btn-info">Список типов оборудования</router-link></span>
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
                        <td>{{ equipment.equipment_type.name }}</td>
                        <td>{{ equipment.serial_number }}</td>
                        <td>{{ equipment.description }}</td>
                        <td>{{ this.formatDate(equipment.created_at) }}</td>
                        <td>{{ this.formatDate(equipment.updated_at) }}</td>
                        <td>
                            <router-link :to="{ name: 'EditEquipment', params: { id: equipment.id }}" class="btn btn-primary">Редактировать</router-link>
                            <button class="btn btn-danger ml-2" @click="deleteEquipment(equipment.id)">Удалить</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Пагинация -->
        <div class="pagination mt-3">
            <button class="btn btn-outline-primary mr-2" :disabled="currentPage === 1" @click="prevPage">Предыдущая</button>
            <button class="btn btn-outline-primary" :disabled="currentPage === pagesCount" @click="nextPage">Следующая</button>
            <span v-for="n in pagesCount" :key="n">
                <button class="btn btn-outline-primary mr-2" :class="{ active: n === currentPage }" @click="goToPage(n)">{{ n }}</button>
            </span>
        </div>
        <div class="ms-0 w-25">
            <label for="perPageSelect">Количество записей на странице:</label>
            <select id="perPageSelect" class="form-control" v-model.number="perPage" @change="fetchData(1, perPage)">
                <option value="1">1</option>
                <option value="5">5</option>
                <option value="20">20</option>
            </select>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: "EquipmentComponent",
    data() {
        return {
            equipments: [],
            searchQuery: '',
            totalItems: 0,
            currentPage: 1,
            perPage: 10
        };
    },
    computed: {
        filteredEquipments() {
            return this.equipments.filter(equipment => {
                return equipment.serial_number.toLowerCase().includes(this.searchQuery.toLowerCase())
                    || (equipment.description && equipment.description.toLowerCase().includes(this.searchQuery.toLowerCase()))
                    || equipment.equipment_type.name.toLowerCase().includes(this.searchQuery.toLowerCase());
            });
        },
        pagesCount() {
            return Math.ceil(this.totalItems / this.perPage);
        }
    },
    created() {
        this.fetchData();
    },
    methods: {
        formatDate(value) {
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric' };
            return new Date(value).toLocaleString("ru-RU", options);
        },
        fetchData(page = 1, perPage = this.perPage) {
            axios.get('/api/equipment', {
                params: {
                    page,
                    per_page: perPage
                }
            }).then(response => {
                this.equipments = response.data.data;
                this.totalItems = response.data.meta.total;
                this.currentPage = page;
                this.perPage = perPage;
            }).catch(error => console.log(error));
        },
        deleteEquipment(id) {
            if (confirm("Вы уверены, что хотите удалить это оборудование?")) {
                axios.delete(`/api/equipment/${id}`)
                    .then(() => {
                        this.fetchData(this.currentPage, this.perPage);
                    })
                    .catch(error => console.log(error));
            }
        },
        nextPage() {
            if (this.currentPage < Math.ceil(this.totalItems / this.perPage)) {
                this.currentPage++;
                this.fetchData(this.currentPage, this.perPage);
            }
        },
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchData(this.currentPage, this.perPage);
            }
        },
        goToPage(page) {
            if (page >= 1 && page <= Math.ceil(this.totalItems / this.perPage)) {
                this.currentPage = page;
                this.fetchData(page, this.perPage);
            }
        }
    },
};
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
}
.pagination button.active {
    background-color: #007bff;
    color: white;
}
</style>
