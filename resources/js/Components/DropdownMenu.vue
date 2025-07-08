<script setup>
import { ref, reactive, nextTick, onMounted, onBeforeUnmount } from 'vue';
import EditGameModal from '@/Components/EditGameModal.vue';
import EditResultGameModal from '@/Components/EditResultGameModal.vue';

const props = defineProps({
    game: Object,
    status: Object,
    courts: Object,
});

const emit = defineEmits(['edit', 'delete']);

// Controle do dropdown
const open = ref(false);
const buttonRef = ref(null);
const style = reactive({ top: '0px', left: '0px' });

// Controle dos modais
const editing = ref(false);
const selectedGame = ref(null);
const editingResult = ref(false);
const selectedResult = ref(null);

// Abrir dropdown e calcular posição com base no botão
const toggle = async () => {
    open.value = !open.value;
    if (open.value) {
        await nextTick();
        const rect = buttonRef.value.getBoundingClientRect();
        style.top = `${rect.bottom + window.scrollY}px`;
        style.left = `${rect.right - 144}px`; // largura do menu
    }
};

const editGame = (game) => {
    selectedGame.value = game;
    editing.value = true;
    open.value = false;
};

const editResult = (game) => {
    selectedResult.value = game;
    editingResult.value = true;
    open.value = false;
};

// Fecha dropdown ao clicar fora
const handleClickOutside = (e) => {
    if (!buttonRef.value?.contains(e.target)) {
        open.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div ref="buttonRef" class="inline-block text-left relative">
        <!-- Botão dos três pontos -->
        <button @click="toggle"
            class="mx-1 px-5 py-2 text-sm font-medium text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-300">
            ⋮
        </button>

        <!-- Dropdown renderizado fora do fluxo da tabela -->
        <Teleport to="body">
            <div v-if="open" :style="style"
                class="absolute z-50 w-36 bg-white shadow-lg rounded-md ring-1 ring-black ring-opacity-5">
                <div class="py-1 text-sm text-gray-700">
                    <button @click="editGame(game)" class="block w-full text-left px-4 py-2 hover:bg-gray-100">
                        ✏️ Editar
                    </button>
                    <button @click="editResult(game)"
                        class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-gray-100">
                        📊 Resultado
                    </button>
                </div>
            </div>
        </Teleport>

        <!-- Modais -->
        <EditGameModal :open="editing" :game="selectedGame" :status="status" :courts="courts" @close="editing = false" />
        <EditResultGameModal :open="editingResult" :game="selectedResult" @close="editingResult = false" />
    </div>
</template>
