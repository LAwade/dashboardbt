<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { usePage, router, Head } from '@inertiajs/vue3';

const page = usePage();
const courts = ref(page.props.courts);

</script>

<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-[85rem] px-4 sm:px-6 lg:px-8 mx-auto">
        <div class="space-y-4">
          <div class="text-xl font-semibold text-gray-700">Quadras</div>
          <!-- Grid Container -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Card de Painel -->
            <div v-for="court in courts" :key="court.id" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
              <div class="p-5">
                <h3 class="text-xl font-bold text-gray-800">{{ court.name }}</h3>
                
                <div class="flex space-x-6 mt-4">
                  <div class="text-center">
                    <span class="block text-2xl font-bold text-gray-800"> {{ court.total_games }}</span>
                    <span class="text-xs text-gray-500">Jogo(s)</span>
                  </div>

                  <div class="text-center">
                    <span class="block text-2xl font-bold text-gray-800">{{ court.pending }}</span>
                    <span class="text-xs text-gray-500">Pendentes</span>
                  </div>

                  <div class="text-center">
                    <span class="block text-2xl font-bold text-gray-800">{{ court.finished }}</span>
                    <span class="text-xs text-gray-500">Finalizados</span>
                  </div>
                </div>

                <div class="flex space-x-3 mt-6">
                  <div v-if="court.total_games > 0">
                    <button @click="router.visit(route('courts.show', court.id))"
                      class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                      Acessar
                    </button>
                  </div>
                  <!-- <button
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Gerenciar
                  </button> -->
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>