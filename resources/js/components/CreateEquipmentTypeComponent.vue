<template>
    <div class="container">
        <h1>Добавление нового типа оборудования</h1>
        <serial-mask-info-component></serial-mask-info-component>
        <form @submit.prevent="onSubmit">
            <div class="row">
                <div class="col-md-6">
                    <label for="name">Название типа оборудования:</label>
                    <input class="ms-2" type="text" id="name" v-model="form.name" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label for="sn_mask">Маска серийного номера:</label>
                    <input class="ms-2" type="text" id="sn_mask" v-model="form.sn_mask" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Добавить</button>
                    <button type="button" class="btn btn-secondary ms-2" @click="$router.push({ name: 'EquipmentTypes' })">Отмена</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
import SerialMaskInfoComponent from "@/components/SerialMaskInfoComponent.vue";
export default {
    name: "CreateEquipmentTypeComponent",
    components: {SerialMaskInfoComponent},
    data() {
        return {
            form: {
                name: '',
                sn_mask: ''
            }
        };
    },
    methods: {
        onSubmit() {
            axios.post('/api/equipment-type', this.form)
                .then((response) => {
                    alert("Тип оборудования успешно добавлен!");
                    this.$router.push({ name: 'EquipmentTypes' });
                })
                .catch((error) => {
                    alert("Произошла ошибка при добавлении типа оборудования.");
                    console.log(error);
                });
        }
    },
};
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
}
</style>
