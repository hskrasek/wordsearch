<script setup lang="ts">
import { StarIcon } from "@heroicons/vue/outline";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Layout.vue";
import { computed } from "vue";
import { route } from "ziggy-js";
import { PlayIcon } from "@heroicons/vue/outline";

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
            icon: StarIcon,
            iconForeground: colors.iconForeground,
            iconBackground: colors.iconBackground,
            directions: difficulty.directions,
            stars: difficulty.value + 1,
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
                        'group relative bg-white p-4 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-500',
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
                                v-for="i in action.stars"
                                :key="i"
                                class="h-6 w-6"
                                aria-hidden="true"
                            />
                        </span>
                    </div>
                    <div class="mt-6">
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
                        <ul
                            class="mt-2 columns-2 overflow-x-auto text-sm text-gray-500"
                        >
                            <li
                                v-for="(
                                    direction, directionIdx
                                ) in action.directions"
                                :key="directionIdx"
                            >
                                {{ direction }}
                            </li>
                        </ul>
                    </div>
                    <span
                        class="pointer-events-none absolute top-6 right-6 text-gray-300 group-hover:text-gray-400"
                        aria-hidden="true"
                    >
                        <PlayIcon class="h-6 w-6" />
                    </span>
                </div>
            </div>
        </template>

        <template #sidebar>
            <section>
                <h2 class="text-xl font-semibold dark:text-white">
                    Word Search Games
                </h2>
                <p class="break-words font-normal dark:text-white">
                    Children and adults have tackled these fun, brain-tickling
                    puzzles for over five decades. Maybe you know them as word
                    seeks, word finds, or even mystery words; Whatever the case,
                    we offer an unlimited number of word search puzzles to play.
                </p>
                <h3 class="mt-3 text-lg font-semibold dark:text-white">
                    How do you play?
                </h3>
                <p class="break-words font-normal dark:text-white">
                    Well, it’s pretty simple. You’ll be given a grid of letters
                    and a list of words when solving a puzzle. Your goal is to
                    find each of the words hidden within the grid. Depending on
                    your chosen difficulty, you can find words horizontally,
                    vertically, or even diagonally. On more complex
                    difficulties, you may even find words reversed.
                    <br />
                    <span class="font-bold">
                        Good luck, and happy puzzle solving!
                    </span>
                </p>
            </section>
        </template>
    </Layout>
</template>
