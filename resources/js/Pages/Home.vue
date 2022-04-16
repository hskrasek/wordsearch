<script setup lang="ts">
import { Head, useForm } from "@inertiajs/inertia-vue3";

defineProps<{
    difficulties: Array<{ value: number; name: string }>;
}>();

const newGame = useForm({
    difficulty: 0,
});

function startGame() {
    newGame.post("/game");
}
</script>

<template>
    <Head title="Start Game" />

    <div class="grid grid-cols-1 items-center justify-center">
        <div class="mx-auto max-w-6xl sm:px-6 lg:px-8">
            <h1 class="text-center text-3xl font-bold">Choose Difficulty</h1>
            <form @submit.prevent="startGame">
                <div role="group">
                    <button
                        v-for="(difficulty, i) in difficulties"
                        :key="i"
                        type="button"
                        :value="difficulty.value"
                        class="inline-block bg-blue-500 px-6 py-2.5 text-lg font-semibold uppercase leading-tight text-white transition duration-150 ease-in-out hover:bg-blue-600 focus:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 active:bg-blue-800"
                        :class="{
                            'rounded-l': i === 0,
                            'rounded-r': i === difficulties.length - 1,
                        }"
                        @click="newGame.difficulty = difficulty.value"
                    >
                        {{ difficulty.name }}
                    </button>
                </div>
                <div>
                    <button
                        type="submit"
                        class="inline-block justify-center bg-green-300 px-6 py-2.5 text-lg font-semibold uppercase leading-tight text-white hover:bg-green-400"
                        :disabled="newGame.processing"
                    >
                        Start Game
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
