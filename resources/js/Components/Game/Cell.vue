<script setup lang="ts">
import { Cell } from "@/Types/Game";

const props = defineProps<{
    x: number;
    y: number;
    cell: Cell;
}>();

const emit = defineEmits<{
    (e: "selectCell", x: number, y: number): void;
}>();

function selectCell(): void {
    emit("selectCell", props.x, props.y);
}
</script>

<template>
    <div class="table-cell h-8 w-8 text-center">
        <button
            class="h-full w-full border-none bg-white p-0 align-middle text-base font-semibold outline-0 transition-colors duration-300 ease-in-out hover:bg-sky-300"
            :class="{
                'bg-green-300': cell.selected,
                'bg-green-400': cell.found,
                'bg-red-500': !cell.found && cell.wrong,
            }"
            @click="selectCell"
        >
            {{ cell.letter }}
        </button>
    </div>
</template>

<style scoped></style>
