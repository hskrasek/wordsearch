<script setup lang="ts">
import Cell from "./Cell.vue";
import { Cell as CellType } from "@/Types/Game";
// import { getCoords } from "@/utils";

defineProps<{
    grid: Array<Array<CellType>>;
}>();

// Need to most likely look into using ref functions
// This way, a shaped object or array of array<object>'s to easily
// and efficiently and effectively lookup cell bounding boxes for
// dynamic click and highlight while moving the cursor
// const rowRefs = ref<Array<HTMLElement> | null>([]);
// const rowBoundingBoxes = computed(() => {
//     return rowRefs.value.map((rowRef: HTMLElement) => getCoords(rowRef));
// });
//
// const cellRefs = ref([]);

const emit = defineEmits<{
    (e: "selectCell", x: number, y: number): void;
}>();

function selectedCell(x: number, y: number): void {
    emit("selectCell", x, y);
}

// onMounted(() => console.log(rowBoundingBoxes.value));

// defineExpose({
//     cellRefs,
// });
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
