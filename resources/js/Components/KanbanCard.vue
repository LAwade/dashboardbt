<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 cursor-move hover:shadow-md transition-shadow">
    <div class="flex justify-between items-start">
      <h4 class="font-medium text-gray-800">{{ card.title }}</h4>
      <div class="flex space-x-2">
        <button @click.stop="$emit('edit', card)" class="text-gray-400 hover:text-gray-600">
          editar
        </button>
        <button @click.stop="$emit('delete', card.id)" class="text-gray-400 hover:text-red-500">
          deletar
        </button>
      </div>
    </div>
    <p class="mt-2 text-sm text-gray-600">{{ card.description }}</p>
    <div v-if="card.dueDate" class="mt-3 flex items-center text-xs text-gray-500">
      {{ formatDate(card.dueDate) }}
    </div>
  </div>
</template>

<script setup>
//import { PencilIcon, TrashIcon } from '@heroicons/vue/outline'

const props = defineProps({
  card: {
    type: Object,
    required: true
  }
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('pt-BR')
}
</script>