<script setup>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted } from 'vue';
import { usePage, router, Head } from '@inertiajs/vue3';

const page = usePage();

</script>


<template>

  <Head title="Dashboard" />

  <AuthenticatedLayout>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <p class="text-4xl font-semibold text-gray-700">Jogos</p>
      </div>
    </div>

  </AuthenticatedLayout>

</template>