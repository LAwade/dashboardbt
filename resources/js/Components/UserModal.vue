<template>
    <div v-if="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white p-6 rounded shadow w-full max-w-lg relative">
            <h2 class="text-lg font-semibold mb-4 text-black">{{ user ? "Editar" : "Criar" }} Usuário</h2>

            <form @submit.prevent="save()" class="p-4 md:p-5">
                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Nome</label>
                    <input type="text" v-model="form.name"
                        class="w-full border border-gray-200 px-3 py-2 rounded text-gray-700" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Email</label>
                    <input type="email" v-model="form.email"
                        class="w-full border border-gray-200 px-3 py-2 rounded text-gray-700" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Permissão</label>
                    <select v-model="form.permission_id"
                        class="w-full border px-3 py-2 rounded border-gray-200 text-gray-700">
                        <option v-for="permission in permissions" :key="permission.id" :value="permission.id">{{ permission.name }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Senha</label>
                    <input type="password" v-model="form.password"
                        class="w-full border border-gray-200 px-3 py-2 rounded text-gray-700" />
                </div>

                <div class="mb-4">
                    <label class="block text-sm text-gray-700 mb-1">Confirmar Senha</label>
                    <input type="password" v-model="form.password_confirmation"
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
    user: Object,
    permissions: Object,
});

const emit = defineEmits(['close']);

const form = ref({
    id: null,
    name: '',
    email: '',
    permission_id: 4,
    password: '',
    password_confirmation: ''
});

watch(
    () => props.user,
    (val) => {
        if (val) {
            form.value = {
                id: val.id,
                name: val.name,
                email: val.email,
                permission_id: val.permission_id,
                password: val.password,
                password_confirmation: val.password_confirmation
            };
        }
    },
    { immediate: true }
);

const close = () => emit('close');

const save = () => {
    if(form.value.id){
        update()
    } else {
        create()
    }
}

const create = () => {
    router.post(route('users.store'), form.value, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            close();
        }
    });
};

const update = () => {
    router.put(route('users.update', { id: form.value.id }), form.value, {
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => {
            close();
        }
    });
};
</script>
