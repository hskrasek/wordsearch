<script setup lang="ts">
import { onMounted, reactive } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import axios from "axios";
import * as dayjs from "dayjs";
import * as calendar from "dayjs/plugin/calendar";
import * as relativeTime from "dayjs/plugin/relativeTime";
import * as ObjectSupport from "dayjs/plugin/objectSupport";

dayjs.extend(calendar);
dayjs.extend(relativeTime);
dayjs.extend(ObjectSupport);

interface Stats {
    took?: number | string;
    started_at?: string;
    first_word_found_at?: string;
    finished_at?: string;
}

const props = defineProps<{
    gameId: string;
}>();

const stats: Stats = reactive({});

onMounted(async () => {
    axios.get(`/api/games/${props.gameId}/stats`).then((response) => {
        Object.assign(stats, response.data);

        let hours = Math.floor((stats.took as number) / 3600);
        let minutes = Math.floor((stats.took as number) / 60);
        let seconds = (stats.took as number) - minutes * 60;

        stats.took = `${hours} hours, ${minutes} minutes, ${seconds} seconds`;
        stats.started_at = dayjs(response.data.started_at).calendar();
        stats.first_word_found_at = dayjs(
            response.data.first_word_found_at
        ).calendar();
        stats.finished_at = dayjs(response.data.finished_at).calendar();
    });
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
        </div>
    </div>
</template>

<style scoped></style>
