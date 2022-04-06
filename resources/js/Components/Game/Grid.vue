<script setup>
import {computed, inject, reactive} from 'vue';
import {Inertia} from '@inertiajs/inertia';
import {useForm} from '@inertiajs/inertia-vue3'

import Cell from './Cell';

const props = defineProps({
    grid: Array
})

const gameId = inject('game-id');

const size = computed(() => props.grid.length)

let selectedWord = reactive({
    letters: [],
    coordinates: [],
});

const form = useForm({
    word: computed(() => selectedWord.letters.join('')),
    coordinates: computed(() => selectedWord.coordinates)
});

function selectedCell(x, y) {
    selectedWord.letters.push(props.grid[x][y].letter);
    selectedWord.coordinates.push([x, y]);
}

function solve() {
    Inertia.post(`/api/games/${gameId}/solve`, form, {
        preserveScroll: true,
        onSuccess() {
            selectedWord.letters = [];
            selectedWord.coordinates = [];

            form.reset();
        },
        onError() {
            selectedWord.letters = [];
            selectedWord.coordinates = [];

            form.reset();
        },
    });
}

// TODO: Revisit click/touch and "drag"
// function handleDrag(event) {
//     const cell = document.elementFromPoint(event.clientX, event.clientY);
//
//     console.log('Cell value: ');
//     console.log(cell.textContent);
// }
</script>

<template>
    <div class="container">
        <div id="game-grid" class="mx-auto">
            <div v-for="(row, x) in grid" class="row">
                <Cell v-for="(cell, y) in row" :cell="cell" :x="x" :y="y" @selectCell="selectedCell"/>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="mx-auto">
            <form @submit.prevent="solve">
                <input type="hidden" v-model="form.word">
                <input type="hidden" v-model="form.coordinates">

                <button type="submit">Solve</button>
            </form>
        </div>
    </div>
</template>

<style scoped>
#game-grid {
    display: table;
    border: 2px solid black;
    padding: 1vw;
}

.row {
    width: 100%;
    margin: 0 auto;

    display: table-row;
}
</style>
