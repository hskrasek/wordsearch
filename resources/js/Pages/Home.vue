<script setup>
import {Head, useForm} from '@inertiajs/inertia-vue3';

defineProps({
    difficulties: Array
});

const newGame = useForm({
    difficulty: ''
});

function startGame() {
    newGame.post('/game')
}
</script>

<template>
  <Head title="Start Game" />

  <div class="grid grid-cols-1 items-center justify-center">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold text-center">
        Choose Difficulty
      </h1>
      <form @submit.prevent="startGame">
        <div role="group">
          <button
            v-for="(difficulty, i) in difficulties"
            :key="i"
            type="button"
            :value="difficulty.value"
            class="inline-block px-6 py-2.5 bg-blue-500 text-white font-semibold text-lg leading-tight uppercase hover:bg-blue-600 focus:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 active:bg-blue-800 transition duration-150 ease-in-out"
            :class="{'rounded-l': i === 0, 'rounded-r': i === (difficulties.length - 1)}"
            @click="newGame.difficulty = difficulty.value"
          >
            {{ difficulty.name }}
          </button>
        </div>
        <div>
          <button
            type="submit"
            class="inline-block px-6 py-2.5 bg-green-300 text-white font-semibold text-lg leading-tight uppercase hover:bg-green-400 justify-center"
            :disabled="newGame.processing"
          >
            Start Game
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
