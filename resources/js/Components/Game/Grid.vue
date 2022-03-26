<script setup>
import { computed } from 'vue';
import Cell from './Cell';

const props = defineProps({
    grid: Array
})

const size = computed(() => props.grid.length)

function selectedCell(letter, x, y) {
    console.log(letter, x, y);
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
                <Cell v-for="(letter, y) in row" :letter="letter" :x="x" :y="y" @selectCell="selectedCell"/>
            </div>
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
