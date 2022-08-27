<script setup lang="ts">
import { PlayIcon } from "@heroicons/vue/outline";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Layout.vue";
import { computed } from "vue";
import route from "ziggy";

const props = defineProps<{
    difficulties: Array<{
        value: number;
        name: string;
        directions: Array<string>;
    }>;
}>();

const difficultyColorMap = {
    Easy: {
        iconForeground: "text-lime-700",
        iconBackground: "bg-lime-50",
    },
    Medium: {
        iconForeground: "text-teal-700",
        iconBackground: "bg-teal-50",
    },
    Hard: {
        iconForeground: "text-pink-700",
        iconBackground: "bg-pink-50",
    },
    Insane: {
        iconForeground: "text-rose-700",
        iconBackground: "bg-rose-50",
    },
};

const actions = computed(() => {
    return props.difficulties.map((difficulty) => {
        let colors = difficultyColorMap[difficulty.name];

        return {
            title: difficulty.name,
            href: route("game.create"),
            icon: PlayIcon,
            iconForeground: colors.iconForeground,
            iconBackground: colors.iconBackground,
            directions: difficulty.directions,
        };
    });
});
</script>

<template>
    <Head>
        <title>Start Game</title>
    </Head>

    <Layout>
        <template #content>
            <div
                class="divide-y divide-gray-200 overflow-hidden rounded-lg bg-gray-200 shadow sm:grid sm:grid-cols-2 sm:gap-px sm:divide-y-0"
            >
                <div
                    v-for="(action, actionIdx) in actions"
                    :key="action.title"
                    :class="[
                        actionIdx === 0
                            ? 'rounded-tl-lg rounded-tr-lg sm:rounded-tr-none'
                            : '',
                        actionIdx === 1 ? 'sm:rounded-tr-lg' : '',
                        actionIdx === actions.length - 2
                            ? 'sm:rounded-bl-lg'
                            : '',
                        actionIdx === actions.length - 1
                            ? 'rounded-bl-lg rounded-br-lg sm:rounded-bl-none'
                            : '',
                        'group relative bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500',
                    ]"
                >
                    <div>
                        <span
                            :class="[
                                action.iconBackground,
                                action.iconForeground,
                                'inline-flex rounded-lg p-3 ring-4 ring-white',
                            ]"
                        >
                            <component
                                :is="action.icon"
                                class="h-6 w-6"
                                aria-hidden="true"
                            />
                        </span>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-lg font-medium">
                            <Link
                                :href="action.href"
                                class="focus:outline-none"
                                as="button"
                                method="post"
                                :data="{ difficulty: action.title }"
                            >
                                <!-- Extend touch target to entire panel -->
                                <span
                                    class="absolute inset-0"
                                    aria-hidden="true"
                                />
                                {{ action.title }}
                            </Link>
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">Directions:</p>
                        <ul class="mt-2 text-sm text-gray-500">
                            <li
                                v-for="direction in action.directions"
                                :key="direction"
                            >
                                {{ direction }}
                            </li>
                        </ul>
                    </div>
                    <span
                        class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                        aria-hidden="true"
                    >
                        <svg
                            class="h-6 w-6"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M20 4h1a1 1 0 00-1-1v1zm-1 12a1 1 0 102 0h-2zM8 3a1 1 0 000 2V3zM3.293 19.293a1 1 0 101.414 1.414l-1.414-1.414zM19 4v12h2V4h-2zm1-1H8v2h12V3zm-.707.293l-16 16 1.414 1.414 16-16-1.414-1.414z"
                            />
                        </svg>
                    </span>
                </div>
            </div>
        </template>

        <template #sidebar>
            <h2 class="mt-6 text-xl font-semibold dark:text-white">
                A word from the author,
            </h2>
            <p class="break-words font-normal dark:text-white">
                I hope you enjoy solving word search puzzles on my little side
                side project. I want to keep this project active and improve it
                along the way as I learn more. A nicer design, mobile and more
                is on the horizon, but I wanted to get this open as soon as
                possible. Pardon my dust while I keep making improvements.
            </p>
            <p class="font-thin dark:text-white">- Hunter Skrasek</p>
        </template>
    </Layout>
</template>
