<script setup lang="ts">
import Cell from "./Cell.vue";
import { Cell as CellType } from "@/Types/Game";

defineProps<{
    grid: Array<Array<CellType>>;
}>();

const emit = defineEmits<{
    (e: "selectCell", x: number, y: number): void;
}>();

function selectedCell(x: number, y: number): void {
    emit("selectCell", x, y);
}
</script>

<template>
    <div class="table border-2 border-gray-800 bg-white">
        <div v-for="(row, x) in grid" :key="x" class="table-row">
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

<style scoped></style>
