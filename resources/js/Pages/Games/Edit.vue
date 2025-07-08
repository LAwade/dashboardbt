<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { usePage, router, Head } from '@inertiajs/vue3';

const page = usePage();

</script>

<template>

    <Head title="Profile" />

    <AuthenticatedLayout>
        <div class="py-12">
            <section>
                <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                            <div class="text-4xl font-semibold text-gray-700">Jogos </div>
                        </div>
                        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
                            <div>
                                <InputLabel for="current_password" value="Current Password" />
                                <TextInput id="current_password" ref="currentPasswordInput" type="password"
                                    class="mt-1 block w-full" autocomplete="current-password" />
                                <InputError class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="password" value="New Password" />
                                <TextInput id="password" ref="passwordInput" type="password" class="mt-1 block w-full"
                                    autocomplete="new-password" />
                                <InputError class="mt-2" />
                            </div>

                            <div>
                                <InputLabel for="password_confirmation" value="Confirm Password" />
                                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                                    autocomplete="new-password" />
                                <InputError class="mt-2" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton>Save</PrimaryButton>
                                <Transition enter-active-class="transition ease-in-out" enter-from-class="opacity-0"
                                    leave-active-class="transition ease-in-out" leave-to-class="opacity-0">
                                    <p class="text-sm text-gray-600">
                                        Saved.
                                    </p>
                                </Transition>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>
