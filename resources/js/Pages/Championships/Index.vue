<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GameStatus from '@/Components/GameStatus.vue';
import { ref, watch, computed } from 'vue';
import { usePage, router, Head } from '@inertiajs/vue3';
import EditChampionship from '@/Components/EditChampionship.vue';

const page = usePage();
const championships = ref(page.props.championships);
const status = computed(() => page.props.status);
const modal = ref(false)
const dataSelected = ref(null)
const search = ref('');

function formatSchedule(value) {
    if (!value) return '-';
    const date = new Date(value);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear()).padStart(4, '0');

    return `${day}/${month}/${year}`;
}

const findChampionship = () => {
    router.get(route('championships.index'), {
        search: search.value || ''
    }, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: (response) => {
            championships.value = response.props.championships
        },
    });
};

const deleteChampionship = (id) => {
    const confirmed = window.confirm('Tem certeza que deseja excluir este campeonato?');
    if (!confirmed) return;
    router.delete(route('championships.destroy', { id }), {
        preserveScroll: true,
        preserveState: false,
    });
};

const openModal = (championships) => {
    dataSelected.value = championships;
    modal.value = true;
};

watch([search], findChampionship);

</script>

<template>

    <Head title="Campeonatos" />
    <AuthenticatedLayout>
        <EditChampionship :open="modal" :championship="dataSelected" :status="status" @close="modal = false"
            :key="dataSelected ? dataSelected.id : 'new'" />
        <div class="py-1">
            <div class="max-w-6xl py-2 px-2 sm:px-2 lg:px-2 lg:py-2 mx-auto">
                <div class="max-w-xl text-center mx-auto">
                    <div class="mt-10 max-w-2xl w-full mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="relative">
                            <input type="text" v-model="search" minlength="3" maxlength="30"
                                class="p-3 sm:p-4 block w-full border-gray-300 rounded-xl sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                placeholder="Pesquise o campeonato pelo nome">

                            <div class="absolute top-1/2 end-2 -translate-y-1/2">
                                <a href="#" @click="openModal(null)"
                                    class="size-10 ml-2 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl bg-green-500 border border-transparent text-white hover:text-white hover:bg-green-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus">
                                        <path d="M5 12h14" />
                                        <path d="M12 5v14" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Card Blog -->
            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                <!-- Grid -->
                <div class="grid sm:grid-cols-3 lg:grid-cols-3 gap-6">
                    <!-- Card -->
                    <div v-for="championship in championships"
                        class="group flex flex-col h-full bg-white border border-gray-200 shadow-2xs rounded-xl">
                        <div class="p-4 md:p-6">
                            <span class="block mb-1 text-xs font-semibold uppercase text-gray-600">
                                {{ formatSchedule(championship.date) }}
                            </span>
                            <h3 class="text-xl font-semibold text-blue-600">
                                {{ championship.name }}
                            </h3>
                            <p class="mt-3 text-gray-500 dark:text-neutral-500">
                                {{ championship.description }}
                            </p>
                            <span class="block py-5 mb-1 text-xs font-semibold uppercase text-green-600">
                                <GameStatus :status="championship.status_id" />
                            </span>
                        </div>
                        <div class="mt-auto flex border-t border-gray-300 divide-x divide-gray-300">
                            <button
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-es-xl bg-white text-blue-500 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-500 dark:hover:bg-neutral-200"
                                @click="router.visit(route('team.index', { championshipId: championship.id }))">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                Equipes
                            </button>
                            <button
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium bg-white text-blue-500 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-500 dark:hover:bg-neutral-200 dark:focus:bg-neutral-200"
                                @click="router.visit(route('games.index', { id: championship.id }))">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-volleyball-icon lucide-volleyball">
                                    <path d="M11.1 7.1a16.55 16.55 0 0 1 10.9 4" />
                                    <path d="M12 12a12.6 12.6 0 0 1-8.7 5" />
                                    <path d="M16.8 13.6a16.55 16.55 0 0 1-9 7.5" />
                                    <path d="M20.7 17a12.8 12.8 0 0 0-8.7-5 13.3 13.3 0 0 1 0-10" />
                                    <path d="M6.3 3.8a16.55 16.55 0 0 0 1.9 11.5" />
                                    <circle cx="12" cy="12" r="10" />
                                </svg>
                                Jogos
                            </button>

                            <button
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium bg-white text-blue-500 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-500 dark:hover:bg-neutral-200 dark:focus:bg-neutral-200"
                                @click="openModal(championship)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil">
                                    <path
                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                    <path d="m15 5 4 4" />
                                </svg>
                                Editar
                            </button>
                            <button
                                class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-ee-xl bg-white text-red-500 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-500 dark:hover:bg-neutral-200"
                                @click="deleteChampionship(championship.id)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2">
                                    <path d="M10 11v6" />
                                    <path d="M14 11v6" />
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                    <path d="M3 6h18" />
                                    <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                </svg>
                                Excluir
                            </button>
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
                <!-- End Grid -->
            </div>
        </div>
        <!-- End Card Blog -->
    </AuthenticatedLayout>
</template>