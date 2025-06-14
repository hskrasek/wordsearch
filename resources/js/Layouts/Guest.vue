<script setup lang="ts">
import BreezeApplicationLogo from "@/Components/ApplicationLogo.vue";
import BreezeDropdown from "@/Components/Dropdown.vue";
import BreezeDropdownLink from "@/Components/DropdownLink.vue";
import BreezeNavLink from "@/Components/NavLink.vue";
import BreezeResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { computed, ref } from "vue";

const props = defineProps<{
    uuid: string;
    auth: {
        user: {
            username: string;
            is_anonymous: boolean;
        };
    };
    status: string;
}>();

const showingNavigationDropdown = ref(false);
const user = computed(() => props.auth.user);
const status = computed(() => props.status);
</script>

<template>
    <nav
        class="border-b border-gray-100 bg-white dark:border-white dark:bg-slate-900"
    >
        <!-- Primary Navigation Menu -->
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex shrink-0 items-center">
                        <Link :href="route('home')">
                            <BreezeApplicationLogo
                                class="block h-9 w-auto fill-current text-gray-500 dark:text-gray-200"
                            />
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <BreezeNavLink
                            v-if="user.is_anonymous"
                            :href="route('login')"
                        >
                            Login
                        </BreezeNavLink>
                        <BreezeNavLink
                            v-if="user.is_anonymous"
                            :href="route('register')"
                        >
                            Register
                        </BreezeNavLink>
                    </div>
                </div>

                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <!-- Settings Dropdown -->
                    <div class="relative ml-3">
                        <BreezeDropdown align="right" width="48">
                            <template #trigger>
                                <span class="inline-flex rounded-md">
                                    <button
                                        type="button"
                                        class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-slate-900"
                                    >
                                        <!--                                        <Link-->
                                        <!--                                            v-if="!user.is_anonymous"-->
                                        <!--                                            :href="-->
                                        <!--                                                route(-->
                                        <!--                                                    'user.profile',-->
                                        <!--                                                    user.username-->
                                        <!--                                                )-->
                                        <!--                                            "-->
                                        <!--                                            disabled-->
                                        <!--                                        >-->
                                        {{ user.username }}
                                        <!--                                        </Link>-->

                                        <svg
                                            v-if="!user.is_anonymous"
                                            class="ml-2 -mr-0.5 h-4 w-4"
                                            xmlns="http://www.w3.org/2000/svg"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                clip-rule="evenodd"
                                            />
                                        </svg>
                                    </button>
                                </span>
                            </template>

                            <template #content>
                                <BreezeDropdownLink
                                    v-if="true"
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    Logout
                                </BreezeDropdownLink>
                            </template>
                        </BreezeDropdown>
                    </div>
                </div>

                <!-- Hamburger -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button
                        class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none"
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                    >
                        <svg
                            class="h-6 w-6"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                :class="{
                                    hidden: showingNavigationDropdown,
                                    'inline-flex': !showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"
                            />
                            <path
                                :class="{
                                    hidden: !showingNavigationDropdown,
                                    'inline-flex': showingNavigationDropdown,
                                }"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{
                block: showingNavigationDropdown,
                hidden: !showingNavigationDropdown,
            }"
            class="sm:hidden"
        >
            <div class="space-y-1 pt-2 pb-3">
                <!--                <BreezeResponsiveNavLink-->
                <!--                    :href="route('dashboard')"-->
                <!--                    :active="route().current('dashboard')"-->
                <!--                >-->
                <!--                    Dashboard-->
                <!--                </BreezeResponsiveNavLink>-->
            </div>

            <!-- Responsive Settings Options -->
            <div class="border-t border-gray-200 pt-4 pb-1">
                <div class="px-4">
                    <div class="text-base font-medium text-gray-800">
                        <!--                {{ $page.props.auth.user.name }}-->
                    </div>
                    <div class="text-sm font-medium text-gray-500">
                        <!--                {{ $page.props.auth.user.email }}-->
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <BreezeResponsiveNavLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >
                        Log Out
                    </BreezeResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>
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

        <main
            class="mt-6 flex max-w-max rounded-lg bg-white px-6 py-4 shadow-md dark:bg-slate-800"
        >
            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>
            <slot />
        </main>
    </div>
</template>
