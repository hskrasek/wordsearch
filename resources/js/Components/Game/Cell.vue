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
  <div
    class="hover:bg-sky-300 cell text-base text-center font-semibold align-middle cursor-default"
    :class="{'bg-green-300': cell.selected, 'bg-green-400': cell.found}"
  >
    <button @click="selectCell">
      {{ cell.letter }}
    </button>
  </div>
</template>

<style scoped>
.cell {
    margin: 0 auto;
    border: none;
    display: table-cell;
    height: 1.5vw;
    width: 1.5vw;
    padding: 0;
    outline: 0;

    cursor: default;
    -moz-user-select: none;
    -ms-user-select: none;
    -webkit-user-select: none;
    -webkit-touch-callout: none;
}

/*.cell:hover {*/
/*    background-color: #2563eb;*/
/*}*/
</style>
