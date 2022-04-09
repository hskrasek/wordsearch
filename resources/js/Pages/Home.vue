<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
    difficulties: Array,
    selectedDifficulty: String,
})
</script>

<template>
  <Head title="Welcome" />

  <div class="relative flex items-top justify-center min-h-screen">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold underline">
        Choose Difficulty
      </h1>
      <form>
        <ul>
          <template
            v-for="(difficulty, i) in difficulties"
            :key="i"
          >
            <li>
              <Link
                href="/game"
                type="button"
                as="button"
                method="post"
                :data="{ difficulty }"
                class="bg-blue-500"
              >
                {{ difficulty.name }}
              </Link>
            </li>
          </template>
        </ul>
      </form>
    </div>
  </div>
</template>
