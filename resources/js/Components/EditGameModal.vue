<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow w-full max-w-lg relative">
            <h2 class="text-lg font-semibold mb-4 text-black">{{ teams ? "Adicionar Jogo" : "Editar Jogo" }}</h2>

            <form @submit.prevent="saveGame" class="p-1 md:p-1">
                <div v-if="teams">
                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 mb-1">Jogadores 1</label>
                        <select v-model="form.team_one"
                            class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700">
                            <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.player_one }} & {{
                                team.player_two }}</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 mb-1">Jogadores 2</label>
                        <select v-model="form.team_two"
                            class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700">
                            <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.player_one }} & {{
                                team.player_two }}</option>
                        </select>
                    </div>
                </div>
                <div v-else>
                    <span class="block text-md font-semibold text-gray-700 mb-1">
                        {{ game.team_one.player_one }} & {{ game.team_one.player_two }}
                    </span>
                    <span class="block text-md font-semibold text-gray-700 mb-6">
                        {{ game.team_two.player_one }} & {{ game.team_two.player_two }}
                    </span>
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Data do Jogo</label>
                    <Datepicker v-model="form.schedule" :format="'dd/MM/yyyy HH:mm:ss'" />
                    
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
                        <Datepicker v-model="form.date_start" :format="'dd/MM/yyyy HH:mm:ss'" />
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm text-gray-700 mb-1">Data Fim</label>
                        <Datepicker v-model="form.date_end" :format="'dd/MM/yyyy HH:mm:ss'" />
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
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

const date = ref(null)

const props = defineProps({
    open: Boolean,
    game: Object,
    teams: Object,
    courts: Array,
    status: Array
});

const emit = defineEmits(['saved', 'close']);

const form = ref({
    id: null,
    category: '',
    schedule: '',
    status_id: 1,
    date_start: '',
    date_end: '',
    court_id: null,
    round: '',
    team_one: null,
    team_two: null,
    championship_id: props.teams?.[0]?.championship_id ?? null
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
                championship_id: val.championship_id ? val.championship_id : props.teams[0].championship_id,
                team_one: val.team_one?.id,
                team_two: val.team_two?.id,
            };
        }
    },
    { immediate: true }
);

const close = () => emit('close');

const saveGame = () => {
    if (form.value.id) {
        updateGame();
    } else {
        createGame();
    }
};

const createGame = () => {
    router.post(route('games.store'), form.value, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            close();
        }
    });
};

const updateGame = () => {
    router.put(route('games.update', { id: form.value.id }), form.value, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            close();
        }
    });
};

function formatSchedule(value) {
    if (!value) return '-';
    const date = new Date(value);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear()).padStart(4, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    return `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;
}
</script>
