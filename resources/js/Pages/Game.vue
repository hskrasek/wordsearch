<script setup lang="ts">
import Grid from "../Components/Game/Grid.vue";
import CompletionScreen from "../Components/Game/CompletionScreen.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { computed, ref, onMounted, onUnmounted, reactive } from "vue";
import { Cell, Word } from "@/Types/Game";
import { bresenham } from "@/utils";

const props = defineProps<{
    uuid: string;
    difficulty: string;
    // eslint-disable-next-line vue/prop-name-casing
    is_completed: boolean;
    words: Array<Word>;
    grid: Array<Array<Cell>>;
}>();

// Need a better name for this
const trackingGrid = computed(() => {
    return props.grid.map((row: Array<Cell>) =>
        row.map((cell: Cell) =>
            Object.assign(cell, { selected: false, wrong: false })
        )
    );
});

const totalWordsToFind = computed<number>(() => {
    return props.words.filter((word: Word) => !word.found).length;
});

const form = useForm({
    word: new Map(),
    coordinates: new Map(),
});

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

onMounted(() => window.addEventListener("click", update));
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
            }, 500);
        },
    });
}
</script>

<template>
    <Head :title="'Game ' + uuid" />

    <div class="grid w-full grid-cols-2 gap-4">
        <Grid
            :grid="trackingGrid"
            class="col-span-1"
            @select-cell="letterSelector"
        />
        <div class="col-span-1 border-2 border-gray-800 p-1.5">
            <h2 class="text-lg font-bold">
                Difficulty: <span class="font-medium">{{ difficulty }}</span>
            </h2>
            <hr />
            <h2 class="text-lg font-bold">Words ({{ totalWordsToFind }}):</h2>
            <div class="columns-2">
                <ul>
                    <li
                        v-for="(word, i) in words"
                        :key="i"
                        :class="{ 'line-through decoration-solid': word.found }"
                    >
                        {{ word.text }}
                    </li>
                </ul>
            </div>
            <hr />
            <div class="container">
                <h2 class="text-lg font-bold">Instructions:</h2>
                <ol class="list-inside list-decimal">
                    <li>
                        Locate the given words in the gird.
                        <!--                        <ul class="list-disc">-->
                        <!--                            <li class="indent-0.5">-->
                        <!--                                Words can be found in one of eight possible-->
                        <!--                                directions:-->
                        <!--                            </li>-->
                        <!--                        </ul>-->
                    </li>
                    <li>
                        To mark a word as found, click the first letter of the
                        word.<br />
                        Then click the last letter of the word.
                    </li>
                    <li>TODO: Make these instructions better</li>
                </ol>
                <!-- <p>Mouse position is at: {{ x }}, {{ y }}</p>-->
                <div v-if="form.errors.word" class="text-red-500">
                    {{ form.errors.word }}
                </div>
                <div v-if="form.errors.coordinates" class="text-red-500">
                    {{ form.errors.coordinates }}
                </div>
            </div>
        </div>
    </div>
    <CompletionScreen v-if="is_completed" :game-id="uuid" />
</template>
