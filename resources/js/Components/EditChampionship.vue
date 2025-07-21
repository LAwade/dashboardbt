<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow w-full max-w-lg relative">
            <h2 class="text-lg font-semibold mb-4 text-black">{{ championship ? "Editar Campeonato" : "Adicionar Campeonato" }}</h2>

            <form @submit.prevent="save" class="p-1 md:p-1">
                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Nome</label>
                    <input type="text" v-model="form.name"
                        class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Descrição</label>
                    <input type="text" v-model="form.description"
                        class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Data</label>
                    <Datepicker v-model="form.date" :format="'dd/MM/yyyy HH:mm:ss'" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Status</label>
                    <select v-model="form.status_id"
                        class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700">
                        <option v-for="sts in status" :key="sts.id" :value="sts.id"> {{ sts.name }}</option>
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="close"
                        class="px-4 py-2 bg-gray-300 rounded text-black">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const props = defineProps({
    open: Boolean,
    championship: Object,
    status: Array
});

const emit = defineEmits(['saved', 'close']);

const form = ref({
    id: null,
    name: '',
    description: '',
    date: null,
    status_id: null
});

watch(
    () => props.championship,
    (val) => {
        if (val) {
            form.value = {
                id: val.id,
                name: val.name,
                description: val.description,
                status_id: val.status_id,
                date: val.date
            };
        }
    },
    { immediate: true }
);

const close = () => emit('close');

const save = () => {
    if (form.value.id) {
        update();
    } else {
        create();
    }
};

const create = () => {
    router.post(route('championships.store'), form.value, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            close();
        }
    });
};

const update = () => {
    router.put(route('championships.update', { id: form.value.id }), form.value, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            close();
        }
    });
};
</script>
