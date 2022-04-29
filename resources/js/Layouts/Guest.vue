<script setup lang="ts">
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import { Link } from "@inertiajs/inertia-vue3";
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-gray-100 pt-6 dark:bg-slate-900 sm:pt-0 md:min-h-fit lg:min-h-screen"
    >
        <div>
            <Link href="/">
                <BreezeApplicationLogo
                    class="h-20 w-20 fill-current text-gray-500 dark:text-gray-200"
                />
            </Link>
        </div>

        <div
            class="mt-6 flex max-w-max rounded-lg bg-white px-6 py-4 shadow-md dark:bg-slate-800"
        >
            <slot />
        </div>
    </div>
</template>
