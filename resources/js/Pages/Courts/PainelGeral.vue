<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { usePage, router, Head } from '@inertiajs/vue3';

import EditResultGameModal from '@/Components/EditResultGameModal.vue';
import GameStatus from '@/Components/GameStatus.vue';
import DropdownMenu from '@/Components/DropdownMenu.vue';

const page = usePage();
const games = ref([]);
const infos = ref({});
const isLoading = ref(true);
const userPermission = usePage().props.auth.permission

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

const findCourts = async () => {
  const pathParts = window.location.pathname.split('/');
  const uuid = pathParts[pathParts.length - 1];
  try {
    const response = await axios.get(`/api/data/court/${uuid}`);
    games.value = response.data.data.games;
    infos.value = {
      court: response.data.data.court,
      first_game: response.data.data.first_game,
      courts: response.data.data.courts,
      status: response.data.data.status
    }
    return response.data.data.games
  } catch (error) {
    console.error(error)
  }
}

onMounted(async () => {
  await findCourts().then(() => {
    isLoading.value = false;
  });

  if (flash.success || flash.error) {
    setTimeout(() => {
      flash.success = null;
      flash.error = null;
    }, 3000);
  }

  window.Echo.channel('games')
    .listen('.updated.event', async (event) => {
      const updatedGame = await findCourts()
      const index = games.value.findIndex(g => g.id === updatedGame.id);
      if (index !== -1) {
        games.value.splice(index, 1, updatedGame);
      }
    });
});

</script>

<template>

  <Head title="Dashboard" />
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto">
        <div v-if="!isLoading && (!infos.first_game || !games.length)" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-red-400 shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
              <h2 class="text-xl font-semibold">Não encontramos nenhum jogo para essa quadra!</h2>
              <p class="text-sm text-red-800">Cadastre um jogo nesta quadra para poder gerenciar!</p>
              <button type="button" @click="router.visit(route('dashboard'))"
                class="px-7 py-2 text-sm font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center">Voltar</button>
            </div>
          </div>
        </div>

        <div v-else-if="!isLoading && infos && games" class="mx-auto max-w-7xl sm:px-6 lg:px-8">
          <div class="space-y-4">
            <div class="text-4xl font-semibold text-gray-700">{{ infos.court.name }}</div>

            <div
              class="hidden md:block w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm sm:p-8 dark:bg-white-800 dark:border-gray-200">
              <p class="text-base text-gray-500 sm:text-lg dark:text-gray-500">{{ infos.first_game.championship.name }}
              </p>
              <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-gray-800">{{
                infos.first_game.team_one.player_one
              }} /
                {{ infos.first_game.team_one.player_two }} &nbsp;
                {{ getSetPlacar(infos.first_game, 0).teamOne }} X {{ getSetPlacar(infos.first_game, 0).teamTwo }}

                <template v-if="infos.first_game.set_results?.[1]">
                  | {{ getSetPlacar(infos.first_game, 1).teamOne }} X {{ getSetPlacar(infos.first_game, 1).teamTwo }}
                </template>

                <!-- Tie-break (condicional e com label) -->
                <template v-if="infos.first_game.set_results?.[2]">
                  | {{ getSetPlacar(infos.first_game, 2).teamOne }} X {{ getSetPlacar(infos.first_game, 2).teamTwo }}
                  <span v-if="infos.first_game.set_results[2].tie_break" class="text-xs text-yellow-500">
                    (tie-break)</span>
                </template>

                &nbsp; {{ infos.first_game.team_two.player_one }} / {{ infos.first_game.team_two.player_two }}
              </h5>
              <p class="text-base text-gray-500 sm:text-lg dark:text-gray-500">Jogo: {{ infos.first_game.id }} - {{
                infos.first_game.category }} - {{ formatSchedule(infos.first_game.schedule) }}</p>
            </div>

            <div
              class="block md:hidden w-full p-4 text-center bg-white border border-gray-200 rounded-lg shadow-sm sm:p-8 dark:bg-white-800 dark:border-gray-200">
              <p class="text-base text-gray-500 sm:text-lg dark:text-gray-500">{{ infos.first_game.championship.name }}
              </p>
              <h5 class="mb-2 text-xl font-bold text-gray-900 dark:text-gray-800">
                {{ infos.first_game.team_one.player_one }} / {{ infos.first_game.team_one.player_two }}
              </h5>

              <h5 class="mb-2 text-lg text-gray-900 dark:text-gray-800">
                {{ getSetPlacar(infos.first_game, 0).teamOne }} X {{ getSetPlacar(infos.first_game, 0).teamTwo }}
                <template v-if="infos.first_game.set_results?.[1]">
                  | {{ getSetPlacar(infos.first_game, 1).teamOne }} X {{ getSetPlacar(infos.first_game, 1).teamTwo }}
                </template>
                <!-- Tie-break (condicional e com label) -->
                <template v-if="infos.first_game.set_results?.[2]">
                  | {{ getSetPlacar(infos.first_game, 2).teamOne }} X {{ getSetPlacar(infos.first_game, 2).teamTwo }}
                  <span v-if="infos.first_game.set_results[2].tie_break" class="text-xs text-yellow-500">
                    (tie-break)</span>
                </template>
              </h5>

              <h5 class="mb-2 text-xl font-bold text-gray-900 dark:text-gray-800">
                {{ infos.first_game.team_two.player_one }} / {{ infos.first_game.team_two.player_two }}
              </h5>

              <p class="text-base text-gray-500 sm:text-lg dark:text-gray-500">Jogo: {{ infos.first_game.id }} - {{
                infos.first_game.category }} - {{ formatSchedule(infos.first_game.schedule) }}</p>
            </div>

            <div class="text-2xl font-semibold text-gray-700">Próximos jogos</div>
            <div class="grid mb-8 border border-gray-200 rounded-lg shadow-xs md:mb-12 "
              :class="games.length > 1 ? 'md:grid-cols-2' : 'md:grid-cols-1'">

              <figure v-for="game in games" :key="game.id"
                class="flex flex-col items-center justify-center text-center bg-gray-300 border-b border-gray-300 rounded-t-lg md:rounded-t-none md:rounded-ss-lg md:border-e dark:bg-gray-50 dark:border-gray-200">
                <blockquote class="mx-auto text-gray-500 dark:text-gray-400">
                  <p class="my-4 text-gray-500">JOGO: {{ game.id }} | {{ formatSchedule(game.schedule) }} | {{
                    game.category }}</p>
                  <GameStatus :key="game.id" :status="game.status_id" />

                  <h3 class="text-2xl p-2 font-semibold text-gray-900 dark:text-gray-600">
                    <i>{{ game.team_one.player_one }}</i> / <i>{{ game.team_one.player_two }}</i>
                  </h3>
                  <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-700">
                    {{ getSetPlacar(game, 0).teamOne }} X {{ getSetPlacar(game, 0).teamTwo }}
                    <!-- Set 2 (condicional) -->
                    <template v-if="game.set_results?.[1]">
                      | {{ getSetPlacar(game, 1).teamOne }} X {{ getSetPlacar(game, 1).teamTwo }}
                    </template>

                    <!-- Tie-break (condicional e com label) -->
                    <template v-if="game.set_results?.[2]">
                      | {{ getSetPlacar(game, 2).teamOne }} X {{ getSetPlacar(game, 2).teamTwo }}
                      <span v-if="game.set_results[2].tie_break" class="text-xs text-gray-500"> (Tie-break)</span>
                    </template>
                  </h3>

                  <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-600">
                    <i>{{ game.team_two.player_one }}</i> / <i>{{ game.team_two.player_two }}</i>
                  </h3>

                  <div class="flex items-center p-4">
                    <div class="inline-flex rounded-md shadow-xs mx-auto" role="group">
                      <div v-if="game.status_id === 1">
                        <button type="button" @click="router.visit(route('games.status', { id: game.id, status: 2 }))"
                          class="mx-1 px-5 py-2 text-sm font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 rounded-lg text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Iniciar</button>
                      </div>

                      <div v-if="game.status_id === 2">
                        <EditResultGameModal v-if="userPermission == 1 || userPermission == 2" :key="game.id"
                          :open="editingResult" :game="game" @close="editingResult = false" />
                        <button type="button" @click="editResult(game)"
                          class="mx-1 px-5 py-2 text-sm font-medium text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 rounded-lg text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">Finalizar</button>
                      </div>

                      <div>
                        <DropdownMenu v-if="userPermission == 1 || userPermission == 2" :key="game.id" :game="game"
                          :status="infos.status" :courts="infos.courts" @edit="editGame" @result="resultGame" />
                      </div>
                    </div>
                  </div>

                </blockquote>
              </figure>
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
    </div>

  </AuthenticatedLayout>
</template>