<script setup lang="ts">
import Grid from "../Components/Game/Grid.vue";
import CompletionScreen from "../Components/Game/CompletionScreen.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import { computed, ref, onMounted, onUnmounted, reactive } from "vue";
import { Cell, Word } from "@/Types/Game";

const props = defineProps<{
    uuid: string;
    difficulty: string;
    // eslint-disable-next-line vue/prop-name-casing
    is_completed: boolean;
    words: Array<Word>;
    grid: Array<Array<Cell>>;
}>();

const wordMap = computed<{ [key: string]: boolean }>(() => {
    return Array.from(props.words).reduce(
        (carry: object, word: Word): object => {
            carry[word.text] = word.found;

            return carry;
        },
        {}
    );
});

// Need a better name for this
const trackingGrid = computed(() => {
    return props.grid.map((row: Array<Cell>) =>
        row.map((cell: Cell) =>
            Object.assign(cell, { selected: false, wrong: false })
        )
    );
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

const x = ref<number>(0);
const y = ref<number>(0);

function update(event: MouseEvent) {
    x.value = event.pageX;
    y.value = event.pageY;
}

onMounted(() => window.addEventListener("mousemove", update));
onUnmounted(() => window.removeEventListener("mousemove", update));

function selectLetter(x: number, y: number): void {
    trackingGrid.value[x][y].selected = !trackingGrid.value[x][y].selected;

    if (
        coordinateTracker.start !== undefined &&
        coordinateTracker.end === undefined
    ) {
        coordinateTracker.end = { x, y };

        console.log(coordinateTracker);
    }

    if (coordinateTracker.start === undefined) {
        coordinateTracker.start = { x, y };
    }

    const key = `${x},${y}`;

    if (form.word.has(key) && form.coordinates.has(key)) {
        form.word.delete(key);
        form.coordinates.delete(key);

        return;
    }

    form.word.set(key, props.grid[x][y].letter);
    form.coordinates.set(key, [x, y]);

    if (Array.from(form.word.values()).join("") in wordMap.value) {
        solve();
    }
}

function solve() {
    form.transform((data) => ({
        coordinates: Array.from(data.coordinates.values()),
        word: Array.from(data.word.values()).join(""),
    })).post(`/game/${props.uuid}/solve`, {
        preserveScroll: true,
        onSuccess() {
            form.reset();
        },
        onError() {
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
            }, 1000);
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
            @select-cell="selectLetter"
        />
        <div class="col-span-1 border-2 border-gray-800 p-1.5">
            <h1 class="text-lg font-bold">
                Difficulty: <span class="font-medium">{{ difficulty }}</span>
            </h1>
            <hr />
            <h1 class="text-lg font-bold">Words:</h1>
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
            <div class="container">
                <p>Mouse position is at: {{ x }}, {{ y }}</p>
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
