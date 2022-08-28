<script setup lang="ts">
import { onMounted, reactive } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Api from "@/api";

// Update Completion screen with modal from Tailwind UI
// Find a new design for the table of stats

const api = new Api();

interface Stats {
    took?: string;
    started_at?: string;
    first_word_found_at?: string;
    finished_at?: string;
    difficulty?: string;
    total_words?: number;
    average_between_words?: number;
}

const props = defineProps<{
    gameId: string;
}>();

const stats = reactive<Stats>({});

onMounted(async () => {
    api.gameStats(props.gameId).then((gameStats) =>
        Object.assign(stats, gameStats)
    );
});
</script>

<template>
    <div
        class="fixed inset-0 flex h-screen w-full items-center justify-center bg-semi-75"
    >
        <div
            class="w-full max-w-xl rounded-lg bg-white p-8 text-center shadow-lg dark:bg-slate-800"
        >
            <h2 class="text-3xl font-bold dark:text-white">Completed!</h2>
            <table
                class="mx-auto table-fixed border-2 border-gray-800 dark:border-gray-200"
            >
                <tr
                    class="border border-black odd:bg-slate-200 even:bg-white dark:odd:bg-slate-600 dark:even:bg-slate-800"
                >
                    <th class="border-r border-black text-left dark:text-white">
                        Difficulty:
                    </th>
                    <td class="dark:text-white">{{ stats.difficulty }}</td>
                </tr>
                <tr
                    class="border border-black odd:bg-slate-200 even:bg-white dark:odd:bg-slate-600 dark:even:bg-slate-800"
                >
                    <th class="border-r border-black text-left dark:text-white">
                        Total Words:
                    </th>
                    <td class="dark:text-white">{{ stats.total_words }}</td>
                </tr>
                <tr
                    class="border border-black odd:bg-slate-200 even:bg-white dark:odd:bg-slate-600 dark:even:bg-slate-800"
                >
                    <th class="border-r border-black text-left dark:text-white">
                        Avg. Time Finding a Word:
                    </th>
                    <td class="dark:text-white">
                        {{ stats.average_between_words }} seconds
                    </td>
                </tr>
                <tr
                    class="border border-black odd:bg-slate-200 even:bg-white dark:odd:bg-slate-600 dark:even:bg-slate-800"
                >
                    <th class="border-r border-black text-left dark:text-white">
                        Time to Complete:
                    </th>
                    <td class="dark:text-white">{{ stats.took }}</td>
                </tr>
                <tr
                    class="border border-black odd:bg-slate-200 even:bg-white dark:odd:bg-slate-600 dark:even:bg-slate-800"
                >
                    <th class="border-r border-black text-left dark:text-white">
                        Started At:
                    </th>
                    <td class="dark:text-white">{{ stats.started_at }}</td>
                </tr>
                <tr
                    class="border border-black odd:bg-slate-200 even:bg-white dark:odd:bg-slate-600 dark:even:bg-slate-800"
                >
                    <th class="border-r border-black text-left dark:text-white">
                        Finished At:
                    </th>
                    <td class="dark:text-white">{{ stats.finished_at }}</td>
                </tr>
            </table>
            <Link
                type="submit"
                class="mt-1.5 inline-block justify-center bg-green-300 px-6 py-2.5 hover:bg-green-400 dark:bg-teal-300 dark:hover:bg-teal-400"
                as="button"
                :href="route('home')"
            >
                <span
                    class="text-lg font-semibold uppercase leading-tight text-white"
                    >New Game</span
                >
            </Link>
            <Link
                type="submit"
                class="mt-1.5 inline-block justify-center bg-green-300 px-4 py-2.5 hover:bg-green-400 dark:bg-teal-300 dark:hover:bg-teal-400"
                as="button"
                method="post"
                :href="route('game.create')"
                :data="{ difficulty: stats.difficulty }"
            >
                <span
                    class="text-lg font-semibold uppercase leading-none text-white"
                >
                    Quick Play
                </span>
            </Link>
        </div>
    </div>
</template>

<style scoped></style>
