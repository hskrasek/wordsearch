<script setup lang="ts">
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from "@headlessui/vue";
import { CheckIcon } from "@heroicons/vue/outline";
import { onMounted, reactive, ref } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import Api from "@/api";

const api = new Api();

interface Stats {
    difficulty?: string;
    completed_in_seconds?: string;
    average_between_words?: number;
    first_word_found?: string;
    words?: object;
}

const props = defineProps<{
    gameId: string;
}>();

const stats = reactive<Stats>({});

onMounted(async () => {
    api.gameStats(props.gameId).then((gameStats) =>
        Object.assign(stats, gameStats)
    );
});

const open = ref(true);
</script>

<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="relative z-10">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                />
            </TransitionChild>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6"
                        >
                            <div>
                                <div
                                    class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100"
                                >
                                    <CheckIcon
                                        class="h-6 w-6 text-green-600"
                                        aria-hidden="true"
                                    />
                                </div>
                                <div class="mt-3 sm:mt-5">
                                    <DialogTitle
                                        as="h3"
                                        class="text-center text-lg font-medium leading-6 text-gray-900"
                                        >Game completed</DialogTitle
                                    >
                                    <div class="mt-2">
                                        <dl
                                            class="mt-5 grid grid-cols-1 divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow md:grid-cols-3 md:divide-y-0 md:divide-x"
                                        >
                                            <div
                                                v-for="item in stats"
                                                :key="item.name"
                                                class="px-4 py-5 sm:p-6"
                                            >
                                                <dt
                                                    class="text-lg font-semibold text-gray-900"
                                                >
                                                    {{ item.name }}
                                                </dt>
                                                <dd
                                                    class="mt-1 flex items-baseline justify-between md:block lg:flex"
                                                >
                                                    <div
                                                        class="flex items-baseline text-base font-normal text-indigo-600"
                                                    >
                                                        {{ item.stat }}
                                                    </div>
                                                </dd>
                                            </div>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3"
                            >
                                <Link
                                    type="submit"
                                    class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:col-start-2 sm:text-sm"
                                    as="button"
                                    method="post"
                                    :href="route('game.create')"
                                    :data="{ difficulty: stats.difficulty }"
                                >
                                    Change difficulty
                                </Link>
                                <Link
                                    ref="cancelButtonRef"
                                    type="button"
                                    class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:col-start-1 sm:mt-0 sm:text-sm"
                                    as="button"
                                    :href="route('home')"
                                >
                                    New game
                                </Link>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
