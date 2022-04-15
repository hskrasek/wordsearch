<script setup>
const props = defineProps({
    x: Number,
    y: Number,
    cell: Object,
});

const emit = defineEmits(['selectCell']);

function selectCell() {
    emit('selectCell', props.x, props.y);
}
</script>

<template>
  <div class="w-8 h-8 table-cell text-center">
    <button
      class="w-full h-full text-base font-semibold align-middle p-0 outline-0 border-none hover:bg-sky-300 transition-colors duration-300 ease-in-out bg-white"
      :class="{'bg-green-300': cell.selected, 'bg-green-400': cell.found, 'bg-red-500': !cell.found && cell.wrong}"
      @click="selectCell"
    >
      {{ cell.letter }}
    </button>
  </div>
</template>

<style scoped>
</style>
