<script setup>
import Grid from '../Components/Game/Grid';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {computed, defineProps} from "vue";

const props = defineProps({
    difficulty: String,
    words: Array,
    grid: Array,
    uuid: String,
    id: Number,
});

// Need a better name for this
const trackingGrid = computed(() => {
    return props.grid.map(row => row.map(cell => Object.assign(cell, {selected: false, wrong: false})));
})

const form = useForm({
    word: new Map,
    coordinates: new Map
});

function selectLetter(x, y) {
    trackingGrid.value[x][y].selected = !trackingGrid.value[x][y].selected;

    const key = `${x},${y}`;

    if (form.word.has(key) && form.coordinates.has(key)) {
        form.word.delete(key);
        form.coordinates.delete(key);

        return;
    }

    form.word.set(key, props.grid[x][y].letter);
    form.coordinates.set(key, [x, y]);
}

function solve() {
    form
        .transform((data) => ({
            coordinates: Array.from(data.coordinates.values()),
            word: Array.from(data.word.values()).join('')
        }))
        .post(`/game/${props.uuid}/solve`, {
            preserveScroll: true,
            onSuccess() {
                form.reset();
            },
            onError() {
                form.reset('word');
                form.coordinates.forEach((coordinate) => {
                    const [x, y] = coordinate;

                    trackingGrid.value[x][y].wrong = true;
                });
                setTimeout(() => {
                    form.coordinates.forEach((coordinate) => {
                        const [x, y] = coordinate;
                        trackingGrid.value[x][y].wrong = false;
                    });
                    form.reset();
                }, 1000);
            },
        });
}
</script>

<template>
  <Head :title="'Game ' + uuid" />

  <div class="w-full grid grid-cols-2 gap-4">
    <Grid
      :grid="trackingGrid"
      class="col-span-1"
      @select-cell="selectLetter"
    />
    <div class="col-span-1 border-2 border-gray-800 p-1.5">
      <h1 class="text-lg font-bold">
        Difficulty: <span class="font-medium">{{ difficulty }}</span>
      </h1>
      <hr>
      <h1 class="text-lg font-bold">
        Words:
      </h1>
      <div class="columns-2">
        <ul>
          <li
            v-for="(word, i) in words"
            :key="i"
            :class="{'line-through decoration-solid': word.found}"
          >
            {{ word.text }}
          </li>
        </ul>
      </div>
      <div class="container">
        <form @submit.prevent="solve">
          <label
            for="word"
            class="block text-sm font-medium text-gray-700"
          > Word </label>
          <div class="mt-1 flex rounded-md shadow-sm">
            <input
              id="word"
              :value="Array.from(form.word.values()).join('')"
              type="text"
              disabled
              name="word"
              class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
            >
          </div>
          <div
            v-if="form.errors.word"
            class="text-red-500"
          >
            {{ form.errors.word }}
          </div>
          <input
            v-model="form.coordinates"
            disabled
            type="hidden"
          >
          <div
            v-if="form.errors.coordinates"
            class="text-red-500"
          >
            {{ form.errors.coordinates }}
          </div>
          <button
            type="submit"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            :disabled="form.processing"
          >
            Solve
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
