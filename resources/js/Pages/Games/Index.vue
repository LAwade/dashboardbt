<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted, watch } from 'vue';
import { usePage, router, Head } from '@inertiajs/vue3';
import EditGameModal from '@/Components/EditGameModal.vue';
import GameStatus from '@/Components/GameStatus.vue';

const page = usePage();
const games = ref(page.props.games.data)
const courts = ref(page.props.courts)
const teams = ref(page.props.teams)
const status = ref(page.props.status)

const dataPage = computed(() => page.props.games);

const search = ref('');
const pagination = ref(1);

const editing = ref(false);
const selected = ref(null);

const editModal = (team) => {
  selected.value = team;
  editing.value = true;
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

const findTeams = () => {
  router.get(route('games.index', { id: teams.value[0].championship_id }), {
    page: pagination.value || 1,
    search: search.value || '',
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: (response) => {
      games.value = response.props.games.data;
    },
  });
};

const deleteGame = (id) => {
  const confirmed = window.confirm('Tem certeza que deseja excluir este jogo?');
  if (!confirmed) return;
  router.delete(route('games.destroy', { id }), {
    preserveScroll: true,
  });
};

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

watch([search, pagination], findTeams);

</script>

<template>

  <Head title="Campeonatos" />
  <AuthenticatedLayout>
    <div class="py-12">
      <!-- Table Section -->
      <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
        <!-- Card -->
        <div class="flex flex-col">
          <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
              <div class="bg-white border border-gray-200 rounded-xl shadow-2xs overflow-hidden">
                <!-- Header -->
                <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 ">
                  <div>
                    <h2 class="text-xl font-semibold text-gray-800">
                      Jogos
                    </h2>
                    <h2 class="text-sm text-gray-600">
                      championship
                    </h2>
                  </div>

                  <div>
                    <div class="flex inline-flex gap-x-2">
                      <div class="hs-dropdown [--placement:bottom-right] relative inline-block">
                        <div class="mx-auto px-4">
                          <form>
                            <div class="relative">
                              <input type="text" required v-model="search" minlength="3" maxlength="30"
                                class="block w-full border-gray-400 rounded-md sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                placeholder="Informe o nome do jogador">
                            </div>
                          </form>
                        </div>
                      </div>

                      <EditGameModal :open="editing" @close="editing = false" :courts="courts" :teams="teams"
                        :status="status" />
                      <button type="button" @click="editModal"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 focus:outline-hidden focus:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="lucide lucide-plus-icon lucide-plus">
                          <path d="M5 12h14" />
                          <path d="M12 5v14" />
                        </svg>
                        Adicionar Jogo
                      </button>
                    </div>
                  </div>
                </div>
                <!-- End Header -->

                <!-- Table -->
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th scope="col" class="px-6 py-3 text-start">
                        <span
                          class="group inline-flex items-center gap-x-2 text-xs font-semibold uppercase text-gray-800 hover:text-gray-500 focus:outline-hidden focus:text-gray-500"
                          href="#">
                          Jogadores
                        </span>
                      </th>
                      <th scope="col" class="py-3 text-end"></th>
                    </tr>
                  </thead>

                  <tbody class="divide-y divide-gray-200">
                    <tr v-if="!games || games.length === 0">
                      <td colspan="10" class="text-center py-4 text-gray-500">Nenhum dado
                        encontrado!</td>
                    </tr>
                    <tr v-for="game in games" :key="game.id"
                      class="bg-white hover:bg-gray-50 dark:hover:bg-neutral-200">

                      <td class="size-px whitespace-nowrap">
                        <div class="px-6">
                          <span class="block text-sm font-semibold text-gray-800 dark:text-neutral-700">
                            {{ game.team_one.player_one }} / {{ game.team_one.player_two }}
                          </span>

                          <span class="block text-sm font-semibold text-gray-800">
                            {{ game.team_two.player_one }} / {{ game.team_two.player_two }}
                          </span>
                        </div>
                      </td>

                      <td class="size-px whitespace-nowrap">
                        <div class="flex items-center gap-x-3">
                          <span class="block text-sm font-semibold text-gray-800 ">[{{
                            getSetPlacar(game, 0).teamOne }}]</span>
                          <span v-if="game.set_results?.[1]" class="block text-sm font-semibold text-gray-800">[{{
                            getSetPlacar(game, 1).teamOne }}]</span>
                          <span v-if="game.set_results?.[2]" class="block text-sm font-semibold text-gray-800">[{{
                            getSetPlacar(game, 2).teamOne }}]</span>
                        </div>
                        <div class="flex items-center gap-x-3">
                          <span class="block text-sm font-semibold text-gray-800">[{{
                            getSetPlacar(game, 0).teamTwo }}]</span>
                          <span v-if="game.set_results?.[1]" class="block text-sm font-semibold text-gray-800">[{{
                            getSetPlacar(game, 1).teamTwo }}]</span>
                          <span v-if="game.set_results?.[2]" class="block text-sm font-semibold text-gray-800">[{{
                            getSetPlacar(game, 2).teamTwo }}]</span>
                        </div>
                      </td>

                      <td class="size-px whitespace-nowrap">
                        <GameStatus :key="game.id" :status="game.status_id" />
                      </td>

                      <td class="size-px whitespace-nowrap">
                        <span class="block text-sm text-gray-800 dark:text-neutral-700">
                          {{ game.category }}
                        </span>
                      </td>

                      <td class="size-px whitespace-nowrap">
                        <span class="block text-sm text-gray-800 dark:text-neutral-700">
                          {{ game.round }}
                        </span>
                      </td>

                      <td class="size-px whitespace-nowrap">
                        <span class="block text-sm text-gray-800 dark:text-neutral-700">
                          {{ game.court.name }}
                        </span>
                      </td>

                      <td class="size-px whitespace-nowrap">
                        <span class="block text-sm text-gray-800 dark:text-neutral-700">
                          {{ formatSchedule(game.schedule) }}
                        </span>
                      </td>

                      <td class="size-px whitespace-nowrap">
                        <div class="px-4 gap-x-6 py-2 flex justify-end">
                          <EditGameModal :key="game.id" :open="editing" :game="selected" :courts="courts" :teams="teams"
                            :status="status" @close="editing = false" />
                          <button type="button" @click="editModal(game)"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-blue-500 text-white shadow-2xs hover:bg-blue-400 focus:outline-hidden focus:bg-blue-400 disabled:opacity-50 disabled:pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                              stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil">
                              <path
                                d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                              <path d="m15 5 4 4" />
                            </svg>
                            Editar
                          </button>

                          <button type="button" @click="deleteGame(game.id)"
                            class="py-2 px-3 bg-red-500 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg text-white shadow-2xs hover:bg-red-300 focus:outline-hidden focus:bg-red-400 disabled:opacity-50 disabled:pointer-events-none">
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
                      </td>
                    </tr>
                  </tbody>
                </table>
                <!-- End Table -->

                <!-- Footer -->
                <div
                  class="px-6 py-4 bg-gray-50 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200">
                  <div>
                    <p class="text-sm text-gray-600">
                      <span class="font-semibold">{{ dataPage.total }} resultados -
                        Página {{ dataPage.current_page }} de {{ dataPage.last_page }}</span>
                    </p>
                  </div>

                  <div>
                    <div class="inline-flex gap-x-2">
                      <button type="button"
                        @click="pagination = dataPage.current_page > 1 ? dataPage.current_page - 1 : dataPage.current_page"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round">
                          <path d="m15 18-6-6 6-6" />
                        </svg>
                        Anterior
                      </button>

                      <button type="button"
                        @click="pagination = dataPage.current_page < dataPage.last_page ? dataPage.current_page + 1 : dataPage.current_page"
                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                        Próximo
                        <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                          viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round">
                          <path d="m9 18 6-6-6-6" />
                        </svg>
                      </button>
                    </div>
                  </div>
                </div>
                <!-- End Footer -->
              </div>
            </div>
          </div>
        </div>
        <!-- End Card -->
      </div>
      <!-- End Table Section -->
    </div>
    <!-- End Card Blog -->
  </AuthenticatedLayout>
</template>