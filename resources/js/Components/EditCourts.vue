<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow w-full max-w-lg relative">
            <h2 class="text-lg font-semibold mb-4 text-black">{{ courts ? "Editar Quadra" : "Adicionar Quadra" }}</h2>

            <form @submit.prevent="save" class="p-1 md:p-1">
                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Nome</label>
                    <input type="text" v-model="form.name"
                        class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Número</label>
                    <input type="text" v-model="form.number"
                        class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Status</label>
                    <Checkbox v-model:checked="form.enable"/>
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
import Checkbox from './Checkbox.vue';

const props = defineProps({
    open: Boolean,
    courts: Object
});

const emit = defineEmits(['close']);

const form = ref({
    id: null,
    name: '',
    number: null,
    enable: null
});

watch(
    () => props.courts,
    (val) => {
        if (val) {
            form.value = {
                id: val.id,
                name: val.name,
                number: val.number,
                enable: val.enable
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
    router.post(route('courts.store'), form.value, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            close();
        }
    });
};

const update = () => {
    router.put(route('courts.update', { id: form.value.id }), form.value, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            close();
        }
    });
};
</script>
