<script setup lang="ts">
import Grid from "@/Components/Game/Grid.vue";
import CompletionScreen from "@/Components/Game/CompletionScreen.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import {
    computed,
    ref,
    onMounted,
    onUnmounted,
    reactive,
    FunctionalComponent,
} from "vue";
import { Cell, Word } from "@/Types/Game";
import { bresenham } from "@/utils";
import Layout from "@/Layouts/Layout.vue";
import WordList from "@/Components/Game/WordList.vue";
import Feed from "@/Components/Game/Feed.vue";
import { ClockIcon } from "@heroicons/vue/outline";

const props = defineProps<{
    uuid: string;
    difficulty: string;
    // eslint-disable-next-line vue/prop-name-casing
    is_completed: boolean;
    words: Array<Word>;
    grid: Array<Array<Cell>>;
    // eslint-disable-next-line vue/prop-name-casing
    created_at: string;
    created_date: string;
}>();

// Need a better name for this
const trackingGrid = computed(() => {
    return props.grid.map((row: Array<Cell>) =>
        row.map((cell: Cell) =>
            Object.assign(cell, { selected: false, wrong: false })
        )
    );
});

const wordsRemaining = computed<number>(() => {
    return props.words.filter((word: Word) => !word.found).length;
});

const form = useForm({
    word: new Map(),
    coordinates: new Map(),
});

const eventFeed = reactive<
    | any[]
    | [
          {
              content: string;
              date: string;
              datetime: string;
              icon: FunctionalComponent;
              iconBackground: string;
          }
      ]
>([]);

// New selection mechanism:
// Click to select start of the word, start mouse move listener
// Use mouse pointer to select end of word
// Calculate pointer location path
// Select cells along path
const coordinateTracker = reactive<{
    start?: {
        x: number;
        y: number;
    };
    end?: {
        x: number;
        y: number;
    };
}>({});

const cursorPositions = reactive<{
    start?: {
        x: number;
        y: number;
    };
    end?: {
        x: number;
        y: number;
    };
}>({});

const x = ref<number>(0);
const y = ref<number>(0);

function update(event: MouseEvent) {
    if (
        cursorPositions.start !== undefined &&
        cursorPositions.end === undefined
    ) {
        cursorPositions.end = { x: event.pageX, y: event.pageY };

        console.log(cursorPositions);
        console.log(
            Array.from(
                bresenham(
                    cursorPositions.start.x,
                    cursorPositions.start.y,
                    cursorPositions.end.x,
                    cursorPositions.end.y
                )
            )
        );

        return;
    }

    cursorPositions.start = { x: event.pageX, y: event.pageY };
}

onMounted(() => {
    window.addEventListener("click", update);
    eventFeed.push({
        content: "Started the game",
        date: props.created_date,
        datetime: props.created_at,
        icon: ClockIcon,
        iconBackground: "bg-gray-400",
    });
});
onUnmounted(() => window.removeEventListener("click", update));

function letterSelector(x: number, y: number): void {
    if (
        coordinateTracker.start !== undefined &&
        coordinateTracker.end === undefined
    ) {
        coordinateTracker.end = { x, y };

        for (let point of bresenham(
            coordinateTracker.start.x,
            coordinateTracker.start.y,
            coordinateTracker.end.x,
            coordinateTracker.end.y
        )) {
            selectLetter(point.x, point.y, true);
        }

        selectLetter(x, y);

        solve();

        return;
    }

    coordinateTracker.start = { x, y };
    selectLetter(x, y);
}

function selectLetter(x: number, y: number, state?: boolean): void {
    trackingGrid.value[x][y].selected =
        state || !trackingGrid.value[x][y].selected;

    const key = `${x},${y}`;

    if (form.word.has(key) && form.coordinates.has(key) && !state) {
        form.word.delete(key);
        form.coordinates.delete(key);

        return;
    }

    form.word.set(key, props.grid[x][y].letter);
    form.coordinates.set(key, [x, y]);
}

function solve() {
    form.transform((data) => ({
        coordinates: Array.from(data.coordinates.values()),
        word: Array.from(data.word.values()).join(""),
    })).post(`/game/${props.uuid}/solve`, {
        preserveScroll: true,
        onSuccess() {
            form.reset();
            coordinateTracker.start = undefined;
            coordinateTracker.end = undefined;
        },
        onError() {
            coordinateTracker.start = undefined;
            coordinateTracker.end = undefined;
            form.reset("word");
            form.coordinates.forEach((coordinate: Array<number>): void => {
                const [x, y] = coordinate;

                trackingGrid.value[x][y].wrong = true;
            });
            setTimeout(() => {
                form.coordinates.forEach((coordinate: Array<number>): void => {
                    const [x, y] = coordinate;
                    trackingGrid.value[x][y].wrong = false;
                });
                form.reset();
            }, 650);
        },
    });
}

// Implement new script logic a "feed" of events.
// Feed should mark when a word was found
// as well as when a word is not found
</script>

<template>
    <Head :title="'A ' + difficulty.toLowerCase() + ' word search puzzle'" />

    <Layout>
        <template #content>
            <Grid :grid="trackingGrid" @select-cell="letterSelector" />
        </template>
        <template #sidebar>
            <h2 class="text-lg font-bold dark:text-white">
                Difficulty:
                <span class="font-medium">{{ difficulty }}</span>
            </h2>
            <hr />
            <WordList :words="words" :words-remaining="wordsRemaining" />
            <hr />
            <Feed :events="eventFeed" />
        </template>
    </Layout>
    <CompletionScreen v-if="is_completed" :game-id="uuid" />
</template>
