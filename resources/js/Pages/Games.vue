<script setup>

import { usePage, router, Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import GameStatus from '@/Components/GameStatus.vue';

onMounted(() => {
    window.Echo.channel('games')
        .listen('.status.updated', (event) => {
            console.log('📢 Status atualizado:', event);

            // Substitui o jogo correspondente na lista
            const index = games.value.findIndex(g => g.id === event.id);
            if (index !== -1) {
                games.value[index] = {
                    ...games.value[index],
                    ...event,
                };
            }
        });
});

const page = usePage();

const games = ref([...usePage().props.games]);
const waiting = page.props.waiting
const join = page.props.join
const finished = page.props.finished

function getSetPlacar(game, index = 0) {
    const set = game.set_results?.[index];

    if (!set) {
        return { teamOne: 0, teamTwo: 0 };
    }

    if (set.team_win === game.team_one.id) {
        return { teamOne: set.game_win, teamTwo: set.game_lose };
    } else {
        return { teamOne: set.game_lose, teamTwo: set.game_win };
    }
}

function formatSchedule(value) {
    if (!value) return '-';

    const date = new Date(value);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${day}/${month} ${hours}:${minutes}`;
}

</script>

<template>

    <Head title="Jogos" />
    <!-- Card Section -->
    <div class="max-w-[85rem] px-4 py-6 sm:px-6 lg:px-12 lg:py-6 mx-auto">
        <!-- Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Card -->
            <div class="flex flex-col bg-white border border-gray-300 shadow-2xs rounded-xl">
                <div class="p-4 md:p-5 flex gap-x-4">
                    <div class="grow">
                        <div class="flex items-center gap-x-2">
                            <p class="text-xs uppercase text-gray-500">
                                Jogos de hoje
                            </p>
                        </div>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
                                {{ games.length }}
                            </h3>
                            <!-- 
                            <span
                                class="inline-flex items-center gap-x-1 py-0.5 px-2 rounded-full bg-green-100 text-green-900 dark:bg-green-800 dark:text-green-100">
                                <span class="inline-block text-xs font-medium">
                                    12.5%
                                </span>
                            </span>
                            -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div class="flex flex-col bg-white border border-gray-300 shadow-2xs rounded-xl">
                <div class="p-4 md:p-5 flex gap-x-4">
                    <div class="grow">
                        <div class="flex items-center gap-x-2">
                            <p class="text-xs uppercase text-gray-500">
                                Jogando
                            </p>
                        </div>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="text-xl font-medium text-gray-800">
                                {{ join.length }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Card -->

            <!-- Card -->
            <div class="flex flex-col bg-white border border-gray-300 shadow-2xs rounded-xl">
                <div class="p-4 md:p-5 flex gap-x-4">
                    <div class="grow">
                        <div class="flex items-center gap-x-2">
                            <p class="text-xs uppercase text-gray-500">
                                Aguardando
                            </p>
                        </div>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
                                {{ waiting.length }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card -->
            <div class="flex flex-col bg-white border border-gray-300 shadow-2xs rounded-xl">
                <div class="p-4 md:p-5 flex gap-x-4">
                    <div class="grow">
                        <div class="flex items-center gap-x-2">
                            <p class="text-xs uppercase text-gray-500">
                                Finalizados
                            </p>
                        </div>
                        <div class="mt-1 flex items-center gap-x-2">
                            <h3 class="text-xl font-medium text-gray-800">
                                {{ finished.length }}
                            </h3>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Card -->
        </div>
        <!-- End Grid -->
    </div>
    <!-- End Card Section -->

    <!-- Table Section -->
    <div class="max-w-[85rem] px-4  sm:px-6 lg:px-8 mx-auto">
        <!-- Card -->
        <div class="flex flex-col">
            <div class="-m-1.5 overflow-x-auto">
                <div class="p-1.5 min-w-full inline-block align-middle">
                    <div class="bg-white border border-gray-300 rounded-xl shadow-2xs overflow-hidden">
                        <!-- Header -->
                        <div
                            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-300 dark:border-neutral-300">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-800 dark:text-neutral-700">
                                    Jogos | Dia: 28/08
                                </h2>
                            </div>
                            <div>
                                <div class="inline-flex gap-x-2">
                                    <a class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none"
                                        href="#" @click="router.visit(route('home'))">
                                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m15 18-6-6 6-6"></path>
                                        </svg>
                                        Voltar
                                    </a>
                                </div>
                            </div>
                        </div>
                        <!-- End Header -->

                        <!-- Table -->
                        <table class="min-w-full divide-y divide-gray-300 dark:divide-neutral-300">
                            <thead class="bg-white">
                                <tr>
                                    <th scope="col" class="ps-6 py-3 text-start"></th>
                                    <th scope="col" class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-700">
                                                Jogadores
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-700">
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-700">
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-700">
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-700">
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-start">
                                        <div class="flex items-center gap-x-2">
                                            <span
                                                class="text-xs font-semibold uppercase text-gray-800 dark:text-neutral-700">
                                            </span>
                                        </div>
                                    </th>

                                    <th scope="col" class="px-6 py-3 text-end"></th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-200">
                                <tr v-for="game in games">
                                    <td class="size-px whitespace-nowrap">
                                        <div class="ps-6 py-3"></div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="ps-6 lg:ps-3 xl:ps-0 pe-6 py-3">
                                            <div class="flex items-center gap-x-3">
                                                <div class="grow">
                                                    <span
                                                        class="block text-sm font-semibold text-gray-800 dark:text-neutral-700">{{
                                                            game.team_one.player_one }}/{{ game.team_one.player_two
                                                        }}</span>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-x-3">
                                                <div class="grow">
                                                    <span
                                                        class="block text-sm font-semibold text-gray-800 dark:text-neutral-700">{{
                                                            game.team_two.player_one }}/{{ game.team_two.player_two
                                                        }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="flex items-center gap-x-3">
                                            <span
                                                class="block text-sm font-semibold text-gray-800 dark:text-neutral-700">[{{
                                                    getSetPlacar(game, 0).teamOne }}]</span>
                                            <span v-if="game.set_results?.[1]"
                                                class="block text-sm font-semibold text-gray-800 dark:text-neutral-700">[{{
                                                    getSetPlacar(game, 1).teamOne }}]</span>
                                            <span v-if="game.set_results?.[2]"
                                                class="block text-sm font-semibold text-gray-800 dark:text-neutral-700">[{{
                                                    getSetPlacar(game, 2).teamOne }}]</span>
                                        </div>
                                        <div class="flex items-center gap-x-3">
                                            <span
                                                class="block text-sm font-semibold text-gray-800 dark:text-neutral-700">[{{
                                                    getSetPlacar(game, 0).teamTwo }}]</span>
                                            <span v-if="game.set_results?.[1]"
                                                class="block text-sm font-semibold text-gray-800 dark:text-neutral-700">[{{
                                                    getSetPlacar(game, 1).teamTwo }}]</span>
                                            <span v-if="game.set_results?.[2]"
                                                class="block text-sm font-semibold text-gray-800 dark:text-neutral-700">[{{
                                                    getSetPlacar(game, 2).teamTwo }}]</span>

                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-12 py-3">
                                            <div class="flex items-center gap-x-3">
                                                <GameStatus :status="game.status_id" />
                                            </div>
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-12 py-3">
                                            {{ game.category }}
                                        </div>
                                    </td>
                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-12 py-3">
                                            {{ game.court.name }}
                                        </div>
                                    </td>

                                    <td class="size-px whitespace-nowrap">
                                        <div class="px-12 py-3">
                                            {{ formatSchedule(game.schedule) }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- End Table -->
                    </div>
                </div>
            </div>
        </div>
        <!-- End Card -->
    </div>
    <!-- End Table Section -->
</template>
