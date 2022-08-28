<template>
    <div class="mt-2 flow-root">
        <ul role="list" class="-mb-8">
            <li v-for="(event, eventIdx) in events" :key="eventIdx">
                <div class="relative pb-4">
                    <span
                        v-if="eventIdx !== events.length - 1"
                        class="absolute top-4 left-2 -ml-px h-full w-0.5 bg-gray-200"
                        aria-hidden="true"
                    />
                    <div class="relative flex space-x-3">
                        <div>
                            <span
                                :class="[
                                    event.iconBackground,
                                    'flex h-4 w-4 items-center justify-center rounded-full ring-4 ring-white',
                                ]"
                            >
                                <component
                                    :is="event.icon"
                                    class="h-5 w-5 text-white"
                                    aria-hidden="true"
                                />
                            </span>
                        </div>
                        <div
                            class="flex min-w-0 flex-1 justify-between space-x-4"
                        >
                            <div>
                                <p class="text-sm text-gray-500">
                                    {{ event.content }}
                                </p>
                            </div>
                            <div
                                class="whitespace-nowrap text-right text-sm text-gray-500"
                            >
                                <time :datetime="event.datetime">{{
                                    event.date
                                }}</time>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup lang="ts">
import { CheckIcon, ThumbUpIcon, UserIcon } from "@heroicons/vue/solid";
import { FunctionalComponent } from "vue";

defineProps<{
    events: Array<{
        content: string;
        date: string;
        datetime: string;
        icon: FunctionalComponent;
        iconBackground: string;
    }>;
}>();
</script>
