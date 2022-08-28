<script setup lang="ts">
import {
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
    Popover,
    PopoverButton,
    PopoverOverlay,
    PopoverPanel,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import {
    BellIcon,
    MenuIcon,
    UserCircleIcon,
    XIcon,
} from "@heroicons/vue/outline";
import { Link } from "@inertiajs/inertia-vue3";
import { computed } from "vue";
import route from "ziggy";

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

const user = computed(() => props.auth.user);
// const status = computed(() => props.status);

const navigation = [
    { name: "Home", href: route("home"), current: route().current("home") },
];
// Update this navigation to be built from anonymous user or not
const userNavigation = [
    { name: "Your Profile", href: "#", method: "get" },
    { name: "Settings", href: "#", method: "get" },
    { name: "Sign out", href: route("logout"), method: "post" },
];
</script>

<template>
    <div class="min-h-full">
        <Popover v-slot="{ open }" as="header" class="bg-cyan-600 pb-24">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <div
                    class="relative flex items-center justify-center py-5 lg:justify-between"
                >
                    <!-- Logo -->
                    <div class="absolute left-0 flex-shrink-0 lg:static">
                        <Link :href="route('home')">
                            <span class="sr-only">Home</span>
                            <img
                                class="h-8 w-auto"
                                src="https://tailwindui.com/img/logos/workflow-mark.svg?color=indigo&shade=300"
                                alt="Workflow"
                            />
                        </Link>
                    </div>

                    <!-- Right section on desktop -->
                    <div
                        class="hidden lg:ml-4 lg:flex lg:items-center lg:pr-0.5"
                    >
                        <button
                            type="button"
                            class="flex-shrink-0 rounded-full p-1 text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                        >
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                        </button>

                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative ml-4 flex-shrink-0">
                            <div>
                                <!--                                    class="flex rounded-full bg-white text-sm ring-2 ring-white ring-opacity-20 focus:outline-none focus:ring-opacity-100"-->
                                <MenuButton
                                    class="flex-shrink-0 rounded-full p-1 text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                                >
                                    <span class="sr-only">Open user menu</span>
                                    <UserCircleIcon class="h-8 w-8" />
                                </MenuButton>
                            </div>
                            <transition
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems
                                    class="absolute -right-2 z-40 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                >
                                    <MenuItem
                                        v-for="item in userNavigation"
                                        :key="item.name"
                                        v-slot="{ active }"
                                    >
                                        <Link
                                            :href="item.href"
                                            :class="[
                                                active ? 'bg-gray-100' : '',
                                                'block w-full px-4 py-2 text-left text-sm text-gray-700',
                                            ]"
                                            as="button"
                                            :method="item.method"
                                        >
                                            {{ item.name }}
                                        </Link>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>

                    <!-- Menu button -->
                    <div class="absolute right-0 flex-shrink-0 lg:hidden">
                        <!-- Mobile menu button -->
                        <PopoverButton
                            class="inline-flex items-center justify-center rounded-md bg-transparent p-2 text-indigo-200 hover:bg-white hover:bg-opacity-10 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                        >
                            <span class="sr-only">Open main menu</span>
                            <MenuIcon
                                v-if="!open"
                                class="block h-6 w-6"
                                aria-hidden="true"
                            />
                            <XIcon
                                v-else
                                class="block h-6 w-6"
                                aria-hidden="true"
                            />
                        </PopoverButton>
                    </div>
                </div>
                <div
                    class="hidden border-t border-white border-opacity-20 py-5 lg:block"
                >
                    <div class="grid grid-cols-3 items-center gap-8">
                        <div class="col-span-2">
                            <nav class="flex space-x-4">
                                <a
                                    v-for="item in navigation"
                                    :key="item.name"
                                    :href="item.href"
                                    :class="[
                                        item.current
                                            ? 'text-white'
                                            : 'text-indigo-100',
                                        'rounded-md bg-white bg-opacity-0 px-3 py-2 text-sm font-medium hover:bg-opacity-10',
                                    ]"
                                    :aria-current="
                                        item.current ? 'page' : undefined
                                    "
                                >
                                    {{ item.name }}
                                </a>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <TransitionRoot as="template" :show="open">
                <div class="lg:hidden">
                    <TransitionChild
                        as="template"
                        enter="duration-150 ease-out"
                        enter-from="opacity-0"
                        enter-to="opacity-100"
                        leave="duration-150 ease-in"
                        leave-from="opacity-100"
                        leave-to="opacity-0"
                    >
                        <PopoverOverlay
                            class="fixed inset-0 z-20 bg-black bg-opacity-25"
                        />
                    </TransitionChild>

                    <TransitionChild
                        as="template"
                        enter="duration-150 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-150 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <PopoverPanel
                            focus
                            class="absolute inset-x-0 top-0 z-30 mx-auto w-full max-w-3xl origin-top transform p-2 transition"
                        >
                            <div
                                class="divide-y divide-gray-200 rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5"
                            >
                                <div class="pt-3 pb-2">
                                    <div
                                        class="flex items-center justify-between px-4"
                                    >
                                        <div>
                                            <img
                                                class="h-8 w-auto"
                                                src="https://tailwindui.com/img/logos/workflow-mark.svg?color=indigo&shade=600"
                                                alt="Workflow"
                                            />
                                        </div>
                                        <div class="-mr-2">
                                            <PopoverButton
                                                class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                                            >
                                                <span class="sr-only"
                                                    >Close menu</span
                                                >
                                                <XIcon
                                                    class="h-6 w-6"
                                                    aria-hidden="true"
                                                />
                                            </PopoverButton>
                                        </div>
                                    </div>
                                    <div class="mt-3 space-y-1 px-2">
                                        <a
                                            href="#"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800"
                                            >Home</a
                                        >
                                        <a
                                            href="#"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800"
                                            >Profile</a
                                        >
                                        <a
                                            href="#"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800"
                                            >Resources</a
                                        >
                                        <a
                                            href="#"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800"
                                            >Company Directory</a
                                        >
                                        <a
                                            href="#"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800"
                                            >Openings</a
                                        >
                                    </div>
                                </div>
                                <div class="pt-4 pb-2">
                                    <div class="flex items-center px-5">
                                        <div class="flex-shrink-0">
                                            <img
                                                class="h-10 w-10 rounded-full"
                                                :src="user.imageUrl"
                                                alt=""
                                            />
                                        </div>
                                        <div class="ml-3 min-w-0 flex-1">
                                            <div
                                                class="truncate text-base font-medium text-gray-800"
                                            >
                                                {{ user.name }}
                                            </div>
                                            <div
                                                class="truncate text-sm font-medium text-gray-500"
                                            >
                                                {{ user.email }}
                                            </div>
                                        </div>
                                        <button
                                            type="button"
                                            class="ml-auto flex-shrink-0 rounded-full bg-white p-1 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            <span class="sr-only"
                                                >View notifications</span
                                            >
                                            <BellIcon
                                                class="h-6 w-6"
                                                aria-hidden="true"
                                            />
                                        </button>
                                    </div>
                                    <div class="mt-3 space-y-1 px-2">
                                        <a
                                            v-for="item in userNavigation"
                                            :key="item.name"
                                            :href="item.href"
                                            class="block rounded-md px-3 py-2 text-base font-medium text-gray-900 hover:bg-gray-100 hover:text-gray-800"
                                            >{{ item.name }}</a
                                        >
                                    </div>
                                </div>
                            </div>
                        </PopoverPanel>
                    </TransitionChild>
                </div>
            </TransitionRoot>
        </Popover>
        <main class="-mt-24 pb-8">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <h1 class="sr-only">Page title</h1>
                <!-- Main 3 column grid -->
                <div
                    class="grid grid-cols-1 items-start gap-4 lg:grid-cols-3 lg:gap-8"
                >
                    <!-- Left column -->
                    <div class="grid grid-cols-1 gap-4 lg:col-span-2">
                        <section aria-labelledby="section-1-title">
                            <h2 id="section-1-title" class="sr-only">
                                Section title
                            </h2>
                            <div
                                class="overflow-hidden rounded-lg bg-white shadow"
                            >
                                <div class="px-8 py-4">
                                    <slot name="content" />
                                </div>
                            </div>
                        </section>
                    </div>

                    <!-- Right column -->
                    <div class="grid grid-cols-1 gap-4">
                        <section aria-labelledby="section-2-title">
                            <h2 id="section-2-title" class="sr-only">
                                Section title
                            </h2>
                            <div
                                class="overflow-hidden rounded-lg bg-white shadow"
                            >
                                <div class="p-6">
                                    <slot name="sidebar" />
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <div
                    class="border-t border-gray-200 py-8 text-center text-sm text-gray-500 sm:text-left"
                >
                    <span class="block sm:inline"
                        >&copy; 2022 Hunter Skrasek.</span
                    >
                    <span class="block sm:inline">All rights reserved.</span>
                </div>
            </div>
        </footer>
    </div>
</template>
