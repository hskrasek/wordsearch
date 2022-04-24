<script setup lang="ts">
import { Head, useForm } from "@inertiajs/inertia-vue3";

defineProps<{
    difficulties: Array<{ value: number; name: string }>;
}>();

const newGame = useForm({
    difficulty: "",
});

function startGame() {
    newGame.post("/game");
}
</script>

<template>
    <Head title="Start Game" />

    <div class="grid grid-cols-1 items-center">
        <div class="max-w-lg sm:px-6 lg:px-8">
            <h1 class="text-center text-3xl font-bold">New Game</h1>
            <h2 class="mt-3 text-center text-2xl font-bold">
                Choose Difficulty:
            </h2>
            <form @submit.prevent="startGame">
                <div class="w-full">
                    <button
                        v-for="(difficulty, i) in difficulties"
                        :key="i"
                        type="button"
                        :value="difficulty.name"
                        class="inline-block bg-blue-500 px-[1.640rem] py-2.5 text-lg font-semibold uppercase leading-tight text-white transition duration-150 ease-in-out hover:bg-blue-600 focus:bg-blue-600 focus:outline-none focus:ring-0 active:bg-blue-800"
                        :class="{
                            'rounded-l': i === 0,
                            'rounded-r': i === difficulties.length - 1,
                        }"
                        @click="newGame.difficulty = difficulty.name"
                    >
                        {{ difficulty.name }}
                    </button>
                </div>
                <div class="w-full">
                    <button
                        type="submit"
                        class="mt-1.5 inline-block w-full bg-green-300 px-6 py-2.5 text-lg font-semibold uppercase leading-tight text-white hover:bg-green-400"
                        :disabled="newGame.processing"
                    >
                        Start Game
                    </button>
                </div>
            </form>
            <h2 class="mt-6 text-xl font-semibold">A word from the author,</h2>
            <p class="break-words font-normal">
                I hope you enjoy solving word search puzzles on my little side
                project. I want to keep this project active and improve it along
                the way as I learn more. A nicer design, mobile friendliness,
                and more is on the horizon, but I wanted to get this out in the
                open as soon as possible. Pardon my dust while I keep making
                improvements.
            </p>
            <p class="font-thin">- Hunter Skrasek</p>
        </div>
    </div>
</template>
