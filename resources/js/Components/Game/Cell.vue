<script setup>
import {ref} from 'vue';

const props = defineProps({
    x: Number,
    y: Number,
    cell: Object,
});

const isSelected = ref(false);

const emit = defineEmits(['selectCell']);

function selectCell() {
    isSelected.value = !isSelected.value;

    emit('selectCell', props.x, props.y);
}
</script>

<template>
    <div
        class="hover:bg-sky-300 cell text-base text-center font-semibold align-middle cursor-default"
        @click="selectCell"
        :class="{'bg-green-300': isSelected, 'bg-green-400': cell.found}"
    >
        {{ cell.letter }}
    </div>
</template>

<style scoped>
.cell {
    margin: 0 auto;
    border: none;
    display: table-cell;
    height: 2vw;
    width: 2vw;
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
