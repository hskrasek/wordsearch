<script setup lang="ts">
import Layout from "@/Layouts/Layout.vue";
import BreezeValidationErrors from "@/Components/ValidationErrors.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import route from "ziggy";

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Log in" />

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
                            Sign in to your account
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
                        </div>

                        <div>
                            <button
                                type="submit"
                                class="group relative flex w-full justify-center rounded-md border border-transparent bg-cyan-300 py-2 px-4 text-sm font-medium text-white hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2"
                                :disabled="form.processing"
                            >
                                Sign in
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>

        <template #sidebar>
            <section>
                <h2 class="text-xl font-semibold dark:text-white">
                    Lorem ipsum
                </h2>
                <p class="break-words font-normal dark:text-white">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Donec cursus leo quis rutrum luctus. Phasellus blandit lorem
                    et ultrices faucibus. Aenean sapien magna, lacinia a sapien
                    eget, commodo consequat ipsum. Phasellus suscipit tellus
                    erat, quis suscipit nunc tincidunt porttitor. Suspendisse
                    eget lectus sapien. Quisque in quam at lectus ornare
                    volutpat et sed justo. Fusce neque enim, iaculis sed
                    condimentum et, maximus in massa. Nulla eget mauris non nisl
                    auctor suscipit in sit amet urna.
                </p>
                <h3 class="text-md mt-3 font-semibold dark:text-white">
                    Lorem ipsum
                </h3>
                <p class="break-words font-normal dark:text-white">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                    Donec cursus leo quis rutrum luctus. Phasellus blandit lorem
                    et ultrices faucibus. Aenean sapien magna, lacinia a sapien
                    eget, commodo consequat ipsum. Phasellus suscipit tellus
                    erat, quis suscipit nunc tincidunt porttitor. Suspendisse
                    eget lectus sapien. Quisque in quam at lectus ornare
                    volutpat et sed justo. Fusce neque enim, iaculis sed
                    condimentum et, maximus in massa. Nulla eget mauris non nisl
                    auctor suscipit in sit amet urna.
                </p>
            </section>
        </template>
    </Layout>
</template>
