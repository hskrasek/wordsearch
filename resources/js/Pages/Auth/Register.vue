<script setup lang="ts">
import BreezeButton from "@/Components/Button.vue";
import BreezeInput from "@/Components/Input.vue";
import BreezeLabel from "@/Components/Label.vue";
import BreezeValidationErrors from "@/Components/ValidationErrors.vue";
import { Head, Link, useForm } from "@inertiajs/inertia-vue3";
import route from "@/ziggy";

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

    <form @submit.prevent="submit">
        <div>
            <BreezeLabel
                for="username"
                value="Username"
                class="dark:text-white"
            />
            <BreezeInput
                id="username"
                v-model="form.username"
                type="text"
                class="mt-1 block w-full"
                required
                autofocus
                autocomplete="username"
            />
        </div>

        <div class="mt-4">
            <BreezeLabel for="email" value="Email" class="dark:text-white" />
            <BreezeInput
                id="email"
                v-model="form.email"
                type="email"
                class="mt-1 block w-full"
                required
                autocomplete="email"
            />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <Link
                :href="route('login')"
                class="text-sm text-gray-600 underline hover:text-gray-900 dark:text-white"
            >
                Already registered?
            </Link>

            <BreezeButton
                class="ml-4"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
            >
                Register
            </BreezeButton>
        </div>
    </form>
</template>
