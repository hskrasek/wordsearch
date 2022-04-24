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
}

const props = defineProps<{
    gameId: string;
}>();

const stats: Stats = reactive({});

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
                <tr
                    v-for="(value, key) in stats"
                    :key="key"
                    class="border border-black odd:bg-slate-200 even:bg-white"
                >
                    <th class="border-r border-black text-left">{{ key }}:</th>
                    <td>{{ value }}</td>
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
