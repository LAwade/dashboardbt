<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow w-full max-w-lg relative">
            <h2 class="text-lg font-semibold mb-4 text-black">Editar Jogo</h2>

            <form @submit.prevent="updateGame" class="p-4 md:p-5">

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Data do Jogo</label>
                    <input type="datetime" v-model="form.schedule"
                        class="w-full border border-gray-200 px-3 py-2 rounded text-gray-700" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Quadra</label>
                    <select v-model="form.court_id"
                        class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700">
                        <option v-for="court in courts" :key="court.id" :value="court.id">{{ court.name }}</option>
                    </select>
                </div>

                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 mb-1">Categoria</label>
                        <input type="text" v-model="form.category"
                            class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 mb-1">Round</label>
                        <input type="text" v-model="form.round"
                            class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700" />
                    </div>
                </div>

                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 mb-1">Data Início</label>
                        <input type="datetime" v-model="form.date_start"
                            class="w-full border border-gray-200 px-3 py-2 rounded text-gray-700" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 mb-1">Data Fim</label>
                        <input type="datetime" v-model="form.date_end"
                            class="w-full border border-gray-200 px-3 py-2 rounded text-gray-700" />
                    </div>
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

const props = defineProps({
    open: Boolean,
    game: Object,
    courts: Array,
    status: Array
});

const emit = defineEmits(['close']);

const form = ref({
    id: null,
    category: '',
    schedule: '',
    status_id: 1,
    date_start: '',
    date_end: '',
    court_id: null,
    round: '',
});

watch(
    () => props.game,
    (val) => {
        if (val) {
            form.value = {
                id: val.id,
                schedule: val.schedule,
                category: val.category,
                status_id: val.status_id,
                date_start: val.date_start ? new Date(val.date_start).toISOString().slice(0, 16) : '',
                date_end: val.date_end ? new Date(val.date_end).toISOString().slice(0, 16) : '',
                court_id: val.court_id,
                round: val.round,
            };
        }
    },
    { immediate: true }
);

const close = () => emit('close');

const updateGame = () => {
    router.put(route('games.update', { id: form.value.id }), form.value, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            close();
           
        }
    });
};
</script>
