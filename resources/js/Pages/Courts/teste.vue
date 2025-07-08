<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { usePage, router, Head } from '@inertiajs/vue3';

import EditResultGameModal from '@/Components/EditResultGameModal.vue';

import DropdownMenu from '@/Components/DropdownMenu.vue';

const page = usePage();

const games = ref(page.props.games);
const court = ref(page.props.court);
const firstGame = ref(page.props.firstGame);
const courts = ref(page.props.courts);
const status = ref(page.props.status);

/** RESULTADO */
const editingResult = ref(false);
const selectedResult = ref(null);

const editResult = (game) => {
  selectedResult.value = game;
  editingResult.value = true;
};

const flash = usePage().props.flash;

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

onMounted(() => {
  if (flash.success || flash.error) {
    setTimeout(() => {
      flash.success = null;
      flash.error = null;
    }, 3000);
  }
});

</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="space-y-4">
          <div class="text-4xl font-semibold text-gray-700">{{ court.name }}</div>
          <div
            class="w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm sm:p-8 dark:bg-white-800 dark:border-gray-200">
            <p class="text-base text-gray-500 sm:text-lg dark:text-gray-500">{{ firstGame.championship.name }}</p>
            <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-gray-800">{{ firstGame.team_one.player_one }} /
              {{ firstGame.team_one.player_two }} &nbsp;
              {{ getSetPlacar(firstGame, 0).teamOne }} X {{ getSetPlacar(firstGame, 0).teamTwo }}

              <template v-if="firstGame.set_results?.[1]">
                | {{ getSetPlacar(firstGame, 1).teamOne }} X {{ getSetPlacar(firstGame, 1).teamTwo }}
              </template>

              <!-- Tie-break (condicional e com label) -->
              <template v-if="firstGame.set_results?.[2]">
                | {{ getSetPlacar(firstGame, 2).teamOne }} X {{ getSetPlacar(firstGame, 2).teamTwo }}
                <span v-if="firstGame.set_results[2].tie_break" class="text-xs text-yellow-500"> (tie-break)</span>
              </template>

              &nbsp; {{ firstGame.team_two.player_one }} / {{ firstGame.team_two.player_two }}
            </h5>
            <p class="text-base text-gray-500 sm:text-lg dark:text-gray-500">Jogo: {{ firstGame.id }} - {{
              firstGame.category }} - {{ formatSchedule(firstGame.schedule) }}</p>
          </div>

          <!-- Grid Container -->
          <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
              <tbody>
                <tr v-for="game in games" :key="game.id"
                  class="bg-whiteborder-b dark:bg-white dark:border-gray-700 border-gray-200 hover:bg-gray-50 dark:hover:bg-gray-200"
                  :class="{ 'dark:bg-green-800': game.status_id === 2, 'dark:bg-gray-700': game.status_id === 5 }">

                  <td class="px-4">
                    <div class="flex items-center text-gray-500">
                      {{ game.id }}
                    </div>
                  </td>

                  <td class="py-4">
                    <div class="flex items-center text-gray-700">
                      {{ formatSchedule(game.schedule) }}
                    </div>
                  </td>

                  <td scope="row" class="py-4 text-gray-800">
                    <div class="ps-3">
                      <div class="text-base font-semibold">
                        <i>{{ game.team_one.player_one }}</i> / <i>{{ game.team_one.player_two }}</i> &nbsp;
                        {{ getSetPlacar(game, 0).teamOne }} X {{ getSetPlacar(game, 0).teamTwo }}
                        <!-- Set 2 (condicional) -->
                        <template v-if="game.set_results?.[1]">
                          | {{ getSetPlacar(game, 1).teamOne }} X {{ getSetPlacar(game, 1).teamTwo }}
                        </template>

                        <!-- Tie-break (condicional e com label) -->
                        <template v-if="game.set_results?.[2]">
                          | {{ getSetPlacar(game, 2).teamOne }} X {{ getSetPlacar(game, 2).teamTwo }}
                          <span v-if="game.set_results[2].tie_break" class="text-xs text-yellow-500"> (tie-break)</span>
                        </template>
                        &nbsp; <i>{{ game.team_two.player_one }}</i> / <i>{{ game.team_two.player_two }}</i>
                      </div>
                    </div>
                  </td>

                  <td class="px-1 py-4">
                    <div class="flex items-center text-gray-700">
                      {{ game.category }}
                    </div>
                  </td>

                  <td class="px-1 py-4">
                    <div class="flex items-center">
                      <span class="text-xs font-medium me-2 px-2.5 py-0.5 rounded-sm dark:bg-white border " :class="{
                        'bg-yellow-100 text-yellow-500 dark:text-yellow-500 border-yellow-500': game.status_id === 1,
                        'bg-green-100 text-green-800 dark:text-green-500 border-green-500': game.status_id === 2,
                        'bg-red-100 text-red-800 dark:text-red-500 border-red-500': game.status_id === 5
                      }">
                        {{ game.status.name }}
                      </span>
                    </div>
                  </td>

                  <td class="px-1 py-4">
                    <div class="flex items-center">
                      <div class="inline-flex rounded-md shadow-xs mx-auto" role="group">
                        <div v-if="game.status_id === 1">
                          <button type="button" @click="router.visit(route('games.status', { id: game.id, status: 2 }))"
                            class="mx-1 px-5 py-2 text-sm font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 rounded-lg text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Iniciar</button>
                        </div>

                        <div v-if="game.status_id === 2">
                          <EditResultGameModal :open="editingResult" :game="game" @close="editingResult = false" />
                          <button type="button" @click="editResult(game)"
                            class="mx-1 px-5 py-2 text-sm font-medium text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Finalizar</button>
                        </div>
                      </div>
                    </div>
                  </td>

                  <td class="px-1 py-4">
                    <div class="flex items-center">
                      <div class="inline-flex rounded-md shadow-xs mx-auto" role="group">
                        <DropdownMenu :game="game" :status="status" :courts="courts" @edit="editGame"
                          @result="resultGame" />
                      </div>
                    </div>
                  </td>

                </tr>
              </tbody>
            </table>
          </div>

          <div class="bg-green-400 text-green-800 p-6 rounded mb-4" v-if="flash.success">
            ✅ {{ flash.success }}
          </div>

          <div class="bg-red-400 text-red-800 p-6 rounded mb-4" v-if="flash.error">
            ❌ {{ flash.error }}
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>