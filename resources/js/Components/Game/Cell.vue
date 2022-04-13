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
  <div class="w-8 h-8 table-cell text-center hover:bg-sky-300">
    <button
      class="w-full h-full text-base font-semibold align-middle p-0 outline-0 border-none"
      :class="{'bg-green-300': cell.selected, 'bg-green-400': cell.found}"
      @click="selectCell"
    >
      {{ cell.letter }}
    </button>
  </div>
</template>

<style scoped>
.cell {
    margin: 0 auto;
    height: 1.5vw;
    width: 1.5vw;
}

/*.cell:hover {*/
/*    background-color: #2563eb;*/
/*}*/
</style>
