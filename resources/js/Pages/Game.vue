<script setup>
import Grid from '../Components/Game/Grid';
import {Head, useForm} from '@inertiajs/inertia-vue3';
import {defineProps} from "vue";

const props = defineProps({
    difficulty: String,
    words: Array,
    grid: Array,
    uuid: String,
    id: Number,
});

const form = useForm({
    word: [],
    coordinates: []
});

function selectLetter(x, y) {
    console.log(x, y);
    form.word.push(props.grid[x][y].letter);
    form.coordinates.push([x, y]);
}

function solve() {
    form
        .transform((data) => ({
            ...data,
            word: data.word.join('')
        }))
        .post(`/api/games/${props.uuid}/solve`, {
        preserveScroll: true,
        onSuccess() {
            form.reset();
        },
        onError() {
            form.reset();
        },
    });
}
</script>

<template>
  <Head :title="'Game ' + uuid" />

  <div class="container mx-auto gap-2 columns-2">
    <Grid
      :grid="grid"
      class="break-after-column"
      @select-cell="selectLetter"
    />
    <div class="border-2 border-gray-800 w-96 p-1.5">
      <h1 class="text-lg font-medium">
        Difficulty: {{ difficulty }}
      </h1>
      <div class="columns-2">
        <ul>
          <li
            v-for="(word, i) in words"
            :key="i"
            :class="{'line-through': word.found}"
          >
            {{ word.text }}
          </li>
        </ul>
      </div>
      <div class="container">
        <p>{{ form.word.join('') }}</p>
        <form @submit.prevent="solve">
          <label
            for="word"
            class="block text-sm font-medium text-gray-700"
          > Word </label>
          <div class="mt-1 flex rounded-md shadow-sm">
            <input
              id="word"
              v-model="form.word"
              type="text"
              disabled
              name="word"
              class="focus:ring-indigo-500 focus:border-indigo-500 flex-1 block w-full rounded-none rounded-r-md sm:text-sm border-gray-300"
            >
          </div>
          <div v-if="form.errors.word">
            {{ form.errors.word }}
          </div>
          <input
            v-model="form.coordinates"
            disabled
            type="hidden"
          >
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
