<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow w-full max-w-lg relative">
            <h2 class="text-lg font-semibold mb-4 text-black">Criar Team/Editar Team</h2>

            <form @submit.prevent="save()" class="p-4 md:p-5">
                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Nome do jogador 1</label>
                    <input type="text" v-model="form.player_one"
                        class="w-full border border-gray-200 px-3 py-2 rounded text-gray-700" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Nome do jogador 2</label>
                    <input type="text" v-model="form.player_two"
                        class="w-full border border-gray-200 px-3 py-2 rounded text-gray-700" />
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="close"
                        class="px-4 py-2 bg-gray-300 rounded text-black">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    open: Boolean,
    team: Object,
    championship: Object,
});

const championship = ref(props.championship);
const emit = defineEmits(['close']);

const form = ref({
    id: null,
    player_one: '',
    player_two: '',
    championship_id: championship.value.id,
});

watch(
    () => props.team,
    (val) => {
        if (val) {
            form.value = {
                id: val.id,
                player_one: val.player_one,
                player_two: val.player_two,
                championship_id: val.championship_id ? val.championship_id : championship.value.id,
            };
        }
    },
    { immediate: true }
);

const close = () => emit('close');

const saveTeam = () => {
    router.post(route('team.store'), form.value, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            close();
        }
    });
};

const updatedTeam = () => {
    router.put(route('team.update', { id: form.value.id }), form.value, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            close();
        }
    });
};

const save = () => {
    if(form.value.id){
        updatedTeam();
    } else {
        saveTeam()
    }
}

</script>
