import { createRouter, createWebHistory } from 'vue-router';
import EquipmentComponent from './components/EquipmentComponent.vue';
import CreateEquipmentComponent from './components/CreateEquipmentComponent.vue';
import EditEquipmentComponent from './components/EditEquipmentComponent.vue';
import EquipmentTypesComponent from './components/EquipmentTypeComponent.vue';
import EditEquipmentTypeComponent from './components/EditEquipmentTypeComponent.vue';
import CreateEquipmentTypeComponent from "./components/CreateEquipmentTypeComponent.vue";

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/equipments',
            name: 'Equipments',
            component: EquipmentComponent
        },
        {
            path: '/add-equipment',
            name: 'CreateEquipment',
            component: CreateEquipmentComponent
        },
        {
            path: '/edit-equipment/:id',
            name: 'EditEquipment',
            component: EditEquipmentComponent
        },
        {
            path: '/edit-equipment-type/:id',
            name: 'EditEquipmentType',
            component: EditEquipmentTypeComponent
        },
        {
            path: '/equipment-types',
            name: 'EquipmentTypes',
            component: EquipmentTypesComponent
        },
        {
            path: '/add-equipment-type',
            name: 'CreateEquipmentType',
            component: CreateEquipmentTypeComponent
        },
    ],
});
export default router;
