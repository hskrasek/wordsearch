<script setup>
import { ref } from 'vue';

defineProps({
    x: Number,
    y: Number,
    letter: String,
})

const isSelected = ref(false);

const emit = defineEmits({
    selectCell(letter, x, y) {
        console.log(isSelected);

        return true;
    }
});

</script>

<template>
    <div
        class="hover:bg-sky-300 cell text-base text-center font-semibold align-middle"
        @click="$emit('selectCell', letter, x, y)"
        :class="{'bg-green-300': isSelected}"
    >
        {{ letter }}
    </div>
</template>

<style scoped>
.cell {
    margin: 0 auto;
    border: none;
    display: table-cell;
    height: 2.5vw;
    width: 2.5vw;
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
