<script setup>
import {defineEmits, defineProps} from 'vue';
import Cell from './Cell';

defineProps({
    grid: {
        type: Array,
        default: []
    }
});

const emit = defineEmits(['selectCell']);

function selectedCell(x, y) {
    emit('selectCell', x, y);
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
  <div class="shadow bg-white px-4 py-3 flex border-2 border-gray-800 table">
    <div
      v-for="(row, x) in grid"
      :key="x"
      class="w-full m-0 table-row"
    >
      <Cell
        v-for="(cell, y) in row"
        :key="y"
        :cell="cell"
        :x="x"
        :y="y"
        @select-cell="selectedCell"
      />
    </div>
  </div>
</template>

<style scoped>
</style>
