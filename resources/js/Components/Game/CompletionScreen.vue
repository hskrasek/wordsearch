<script setup lang="ts">
import { onMounted, reactive } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Api from "@/api";

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
            class="w-full max-w-xl rounded-lg bg-white p-8 text-center shadow-lg"
        >
            <h2 class="text-3xl font-bold">Completed!</h2>
            <table class="mx-auto table-fixed border-2 border-gray-800">
                <tr class="border border-black odd:bg-slate-200 even:bg-white">
                    <th class="border-r border-black text-left">Difficulty:</th>
                    <td>{{ stats.difficulty }}</td>
                </tr>
                <tr class="border border-black odd:bg-slate-200 even:bg-white">
                    <th class="border-r border-black text-left">
                        Total Words:
                    </th>
                    <td>{{ stats.total_words }}</td>
                </tr>
                <tr class="border border-black odd:bg-slate-200 even:bg-white">
                    <th class="border-r border-black text-left">
                        Avg. Time Finding a Word:
                    </th>
                    <td>{{ stats.average_between_words }} seconds</td>
                </tr>
                <tr class="border border-black odd:bg-slate-200 even:bg-white">
                    <th class="border-r border-black text-left">
                        Time to Complete:
                    </th>
                    <td>{{ stats.took }}</td>
                </tr>
                <tr class="border border-black odd:bg-slate-200 even:bg-white">
                    <th class="border-r border-black text-left">Started At:</th>
                    <td>{{ stats.started_at }}</td>
                </tr>
                <tr class="border border-black odd:bg-slate-200 even:bg-white">
                    <th class="border-r border-black text-left">
                        Finished At:
                    </th>
                    <td>{{ stats.finished_at }}</td>
                </tr>
            </table>
            <Link
                type="submit"
                class="mt-1.5 inline-block justify-center bg-green-300 px-6 py-2.5 text-lg font-semibold uppercase leading-tight text-white hover:bg-green-400"
                as="button"
                :href="route('home')"
            >
                New Game
            </Link>
            <Link
                type="submit"
                class="mt-1.5 inline-block justify-center bg-green-300 px-6 py-2.5 text-lg font-semibold uppercase leading-tight text-white hover:bg-green-400"
                as="button"
                method="post"
                :href="route('game.create')"
                :data="{ difficulty: stats.difficulty }"
            >
                Quick Play
            </Link>
        </div>
    </div>
</template>

<style scoped></style>
