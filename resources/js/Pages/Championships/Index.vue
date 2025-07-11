<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GameStatus from '@/Components/GameStatus.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { usePage, router, Head } from '@inertiajs/vue3';

const page = usePage();
const championships = computed(() => page.props.championships);
const rawMessage = computed(() => page.props.message || null);
const message = ref(null);

const form = ref({
    name: ''
});

function formatSchedule(value) {
    if (!value) return '-';

    const date = new Date(value);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear()).padStart(4, '0');

    return `${day}/${month}/${year}`;
}

const findChampionship = () => {
    if (!form.value.name) {
        return;
    }
    router.get(route('championships.show', { name: form.value.name }), {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            console.log(form.value)
        },
    });
};

watch(() => {
    if (rawMessage.value) {
        message.value = rawMessage.value;

        setTimeout(() => {
            message.value = null;
        }, 3000);
    }
});

</script>

<template>

    <Head title="Campeonatos" />
    <AuthenticatedLayout>
        <div class="py-1">
            <div class="max-w-6xl py-2 px-2 sm:px-2 lg:px-2 lg:py-2 mx-auto">
                <div class="max-w-xl text-center mx-auto">
                    <div class="mt-10 max-w-2xl w-full mx-auto px-4 sm:px-6 lg:px-8">
                        <form @submit.prevent="findChampionship">
                            <div class="relative">
                                <input type="text" v-model="form.name" minlength="3" maxlength="30"
                                    class="p-3 sm:p-4 block w-full border-gray-300 rounded-xl sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                    placeholder="Pesquise o campeonato pelo nome">

                                <div class="absolute top-1/2 end-2 -translate-y-1/2">
                                    <button type="submit"
                                        class="size-10 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-400 hover:text-gray-800 focus:outline-hidden focus:text-gray-800 disabled:opacity-50 disabled:pointer-events-none dark:hover:text-white dark:focus:text-white hover:bg-gray-100">
                                        <svg class="size-4 text-gray-400 dark:text-neutral-500"
                                            xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" viewBox="0 0 16 16">
                                            <path
                                                d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                                        </svg>
                                    </button>

                                    <a href="#"
                                        class="size-10 ml-2 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-xl bg-green-500 border border-transparent text-white hover:text-white hover:bg-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="lucide lucide-plus-icon lucide-plus">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="mt-4 max-w-2xl w-full mx-auto px-4 sm:px-6 lg:px-8" v-if="message">
                    <div class="mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                        {{ message }}
                    </div>
                </div>
            </div>
            <!-- Card Blog -->
            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

                <!-- Grid -->
                <div v-for="championship in championships" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Card -->
                    <div class="group flex flex-col h-full bg-gray-100 border border-gray-300 shadow-2xs rounded-xl">

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
                            <a class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-es-xl bg-gray-100 text-blue-500 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-500 dark:hover:bg-neutral-200"
                                @click="router.visit(route('team.index', { championshipId: championship.id }))"
                                href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-users-icon lucide-users">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                                    <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                                    <circle cx="9" cy="7" r="4" />
                                </svg>
                                Equipes
                            </a>
                            <a class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium bg-gray-100 text-blue-500 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-500 dark:hover:bg-neutral-200 dark:focus:bg-neutral-200"
                                href="#">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil">
                                    <path
                                        d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                    <path d="m15 5 4 4" />
                                </svg>
                                Editar
                            </a>
                            <a class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-ee-xl bg-gray-100 text-red-500 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:border-neutral-500 dark:hover:bg-neutral-200"
                                href="#">
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
                            </a>
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