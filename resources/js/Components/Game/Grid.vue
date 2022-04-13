<script setup>
import Cell from './Cell';

defineProps({
    grid: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['selectCell']);

function selectedCell(x, y) {
    emit('selectCell', x, y);
}
</script>

<template>
  <div class="bg-white border-2 border-gray-800 table">
    <div
      v-for="(row, x) in grid"
      :key="x"
      class="table-row"
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
