<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';
import Grid from '../Components/Game/Grid';
import {provide} from "vue";

const props = defineProps({
    difficulty: String,
    words: Array,
    grid: Array,
    uuid: String,
    id: Number,
})

provide('game-id', props.uuid);
</script>

<template>
    <Head :title="'Game ' + uuid"/>

    <div class="container mx-auto">
    <Grid :grid="grid" />
    <h1>Difficulty: {{ difficulty }}</h1>
    <ul>
        <li v-for="word in words" :class="{'line-through': word.found}">{{ word.text }}</li>
    </ul>
    </div>
</template>
