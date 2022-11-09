<template>
    <h2 class="text-lg font-bold dark:text-white">
        Words ({{ wordsRemaining }}):
    </h2>
    <div class="columns-2">
        <ul>
            <li
                v-for="(word, i) in words"
                :key="i"
                class="dark:text-white"
                :class="{
                    'line-through decoration-solid decoration-2': word.found,
                }"
            >
                {{ word.text }}
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { Word } from "@/Types/Game";

defineProps<{
    wordsRemaining: number;
    words: Array<Word>;
}>();
</script>
