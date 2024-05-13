<script setup lang="ts">
import BreezeValidationErrors from "@/Components/ValidationErrors.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Layout.vue";
import { route } from "ziggy-js";

const form = useForm({
    username: "",
    email: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Register" />

    <BreezeValidationErrors class="mb-4" />

    <Layout>
        <template #content>
            <div
                class="flex min-h-full items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
            >
                <div class="w-full max-w-md space-y-8">
                    <div>
                        <h2
                            class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900"
                        >
                            Register now
                        </h2>
                    </div>
                    <form
                        class="mt-8 space-y-6"
                        method="POST"
                        @submit.prevent="submit"
                    >
                        <input type="hidden" name="remember" value="true" />
                        <div class="-space-y-px rounded-md shadow-sm">
                            <div>
                                <label for="email-address" class="sr-only"
                                    >Email address</label
                                >
                                <input
                                    id="email-address"
                                    v-model="form.email"
                                    name="email"
                                    type="email"
                                    autocomplete="email"
                                    required=""
                                    class="relative block w-full appearance-none rounded-none rounded-t-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Email address"
                                />
                            </div>
                            <div>
                                <label for="username" class="sr-only"
                                    >Username</label
                                >
                                <input
                                    id="username"
                                    v-model.trim="form.username"
                                    name="username"
                                    type="text"
                                    autocomplete="username"
                                    required=""
                                    class="relative block w-full appearance-none rounded-none rounded-b-md border border-gray-300 px-3 py-2 text-gray-900 placeholder-gray-500 focus:z-10 focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Username"
                                />
                            </div>
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="group relative flex w-full justify-center rounded-md border border-transparent bg-cyan-300 py-2 px-4 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                            >
                                Register
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <template #sidebar>
            <section>
                <h2 class="text-xl font-semibold dark:text-white">
                    Why register?
                </h2>
                <p class="break-words font-normal dark:text-white">
                    You'll never be required to register to play games here at
                    WordSearch.Games, but if you register, you'll be able to
                    play games and track your progress across devices.
                </p>
                <h3 class="text-md mt-3 font-semibold dark:text-white">
                    What about my password?
                </h3>
                <p class="break-words font-normal dark:text-white">
                    You won't need one on WordSearch.Games. We use a
                    passwordless authentication system referred to as "magic
                    links." As long as you know your email password, you know
                    your password to WordSearch.Games.
                </p>
            </section>
        </template>
    </Layout>
</template>
