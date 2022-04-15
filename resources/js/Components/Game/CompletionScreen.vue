<script setup>
import {onMounted, reactive} from "vue";
import { Link } from '@inertiajs/inertia-vue3';
import axios from 'axios';
import dayjs from 'dayjs';
import calendar from 'dayjs/plugin/calendar';
import relativeTime from 'dayjs/plugin/relativeTime';
import ObjectSupport from 'dayjs/plugin/objectSupport';

dayjs.extend(calendar)
dayjs.extend(relativeTime);
dayjs.extend(ObjectSupport);

const props = defineProps({
    gameId: String
});

const stats = reactive({});

onMounted(async () => {
    axios.get(`/api/games/${props.gameId}/stats`)
        .then((response) => {
            Object.assign(stats, response.data);

            let hours = Math.floor(stats.took / 3600);
            let minutes = Math.floor(stats.took / 60);
            let seconds = stats.took - minutes * 60;

            stats.took = `${hours} hours, ${minutes} minutes, ${seconds} seconds`;
            stats.started_at = dayjs(response.data.started_at).calendar();
            stats.first_word_found_at = dayjs(response.data.first_word_found_at).calendar();
            stats.finished_at = dayjs(response.data.finished_at).calendar();
        });
});
</script>

<template>
  <div
    class="fixed inset-0 w-full h-screen flex items-center justify-center bg-semi-75"
  >
    <div class="w-full max-w-xl bg-white shadow-lg rounded-lg p-8 text-center">
      <h2 class="text-3xl font-bold">
        Completed!
      </h2>
      <table class="table-fixed border-2 border-gray-800 mx-auto">
        <tr
          v-for="(value, key) in stats"
          :key="key"
          class="even:bg-white odd:bg-slate-200 border border-black"
        >
          <th class="text-left border-r border-black">
            {{ key }}:
          </th>
          <td>{{ value }}</td>
        </tr>
      </table>
      <Link
        type="submit"
        class="inline-block px-6 py-2.5 mt-1.5 bg-green-300 text-white font-semibold text-lg leading-tight uppercase hover:bg-green-400 justify-center"
        as="button"
        :href="route('home')"
      >
        New Game
      </Link>
    </div>
  </div>
</template>

<style scoped>

</style>
