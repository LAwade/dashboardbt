<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const games = ['', 0, 1, 2, 3, 4, 5, 6, 7]

const props = defineProps({
    open: Boolean,
    game: Object,
});

const emit = defineEmits(['close']);

const form = ref({
    id: null,
    team_one: '',
    game_team_one_1: '',
    game_team_one_2: '',
    game_team_one_tie: '',
    team_two: '',
    game_team_two_1: '',
    game_team_two_2: '',
    game_team_two_tie: '',
    walkover: '',
});


watch(
    () => props.game,
    (val) => {
        if (val) {
            // Dados básicos
            form.value.id = val.id;
            form.value.team_one = val.team_one;
            form.value.team_two = val.team_two;
            form.value.walkover = val.walkover ?? false;

            // Zera os sets antes de preencher
            form.value.game_team_one_1 = null;
            form.value.game_team_two_1 = null;
            form.value.game_team_one_2 = null;
            form.value.game_team_two_2 = null;
            form.value.game_team_one_tie = null;
            form.value.game_team_two_tie = null;

            // Verifica e preenche os resultados dos sets
            const sets = val.set_results || [];

            if (sets[0]) {
                if (val.team_one.id === sets[0].team_win) {
                    form.value.game_team_one_1 = sets[0].game_win;
                    form.value.game_team_two_1 = sets[0].game_lose;
                } else {
                    form.value.game_team_one_1 = sets[0].game_lose;
                    form.value.game_team_two_1 = sets[0].game_win;
                }
            }

            if (sets[1]) {
                if (val.team_one.id === sets[1].team_win) {
                    form.value.game_team_one_2 = sets[1].game_win;
                    form.value.game_team_two_2 = sets[1].game_lose;
                } else {
                    form.value.game_team_one_2 = sets[1].game_lose;
                    form.value.game_team_two_2 = sets[1].game_win;
                }
            }

            if (sets[2]) {
                if (val.team_one.id === sets[2].team_win) {
                    form.value.game_team_one_tie = sets[2].game_win;
                    form.value.game_team_two_tie = sets[2].game_lose;
                } else {
                    form.value.game_team_one_tie = sets[2].game_lose;
                    form.value.game_team_two_tie = sets[2].game_win;
                }
            }
        }
    },
    { immediate: true }
);

const close = () => emit('close');

const updateResult = () => {
    router.post(route('result.create', { id: props.game.id }), form.value, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            close();
            toast.success('Jogo atualizado com sucesso!');
        }
    });
};
</script>

<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow w-full max-w-lg relative">
            <h2 class="text-lg font-semibold mb-4 text-black">Resultado</h2>

            <form @submit.prevent="updateResult" class="p-4 md:p-5">
                <div class="mb-4">
                    <label class="block text-lg text-gray-700 mb-1">{{ form.team_one.player_one }} / {{
                        form.team_one.player_two }}</label>
                    <div class="grid gap-4 mb-4 grid-cols-3">
                        <div class="mb-4">
                            <label class="block text-xsm text-gray-700 mb-1">1 SET</label>
                            <select class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700"
                                v-model="form.game_team_one_1">
                                <option v-for="game in games" :value="game">{{ game }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-gray-700 mb-1">2 SET</label>
                            <select class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700"
                                v-model="form.game_team_one_2">
                                <option v-for="game in games" :value="game">{{ game }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm text-gray-700 mb-1">Tiebreak</label>
                            <input type="number" v-model="form.game_team_one_tie"
                                class="w-full border border-gray-200 px-3 py-2 rounded text-gray-700" />
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="grid gap-4 mb-4 grid-cols-3">
                        <div class="mb-4">
                            <select class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700"
                                v-model="form.game_team_two_1">
                                <option v-for="game in games" :value="game">{{ game }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <select class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700"
                                v-model="form.game_team_two_2">
                                <option v-for="game in games" :value="game">{{ game }}</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <input type="number" v-model="form.game_team_two_tie"
                                class="w-full border border-gray-200 px-3 py-2 rounded text-gray-700" />
                        </div>
                    </div>
                    <label class="block text-lg text-gray-700 mb-1">{{ form.team_two.player_one }} / {{
                        form.team_two.player_two }}</label>
                </div>

                <div class="flex items-center">
                    <input id="checked-checkbox" type="checkbox" v-model="form.walkover"
                        class="w-4 h-4 text-black border-gray-300 rounded focus:ring-gray-500 dark:focus:ring-gray-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-400 dark:border-gray-400">
                    <label for="checked-checkbox"
                        class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-700">W.O</label>
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


