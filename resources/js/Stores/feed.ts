import dayjs from "dayjs";
import { defineStore } from "pinia";
import { ref } from "vue";
import { CheckCircleIcon, XIcon } from "@heroicons/vue/solid";

// Put a pause on this. State is saved between games, causing the feed from a previous game to persis.
export const useFeedStore = defineStore("feed", () => {
    const feed = ref([]);

    function foundWord(word: string) {
        feed.value.push({
            content: "Found " + word,
            date: dayjs().calendar(),
            datetime: dayjs().format("Y-m-d H:i:s"),
            icon: CheckCircleIcon,
            iconBackground: "bg-green-400",
        });
    }

    function invalidWord(word: string) {
        feed.value.push({
            content: word + " is not a valid selection",
            date: dayjs().calendar(),
            datetime: dayjs().format("Y-m-d H:i:s"),
            icon: XIcon,
            iconBackground: "bg-red-400",
        });
    }

    return { feed, foundWord, invalidWord };
});
