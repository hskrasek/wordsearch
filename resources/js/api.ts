import axios, { AxiosInstance, AxiosResponse } from "axios";
import dayjs from "dayjs";
import calendar from "dayjs/plugin/calendar";
import relativeTime from "dayjs/plugin/relativeTime";
import ObjectSupport from "dayjs/plugin/objectSupport";
dayjs.extend(calendar);
dayjs.extend(relativeTime);
dayjs.extend(ObjectSupport);

export default class Api {
    private axios: AxiosInstance;

    constructor() {
        this.axios = axios;
        this.axios.defaults.baseURL = "/api/";
        this.axios.defaults.withCredentials = true;
    }

    public gameStats(gameId: string): Promise<{
        difficulty: string;
        took: string;
        started_at: string;
        first_word_found_at: string;
        finished_at: string;
    }> {
        return this.axios
            .get("/games/" + gameId + "/stats")
            .then((response: AxiosResponse) => {
                const stats = response.data;

                const hours = Math.floor((stats.took as number) / 3600)
                    .toString()
                    .padStart(2, "0");
                const minutes = Math.floor(((stats.took as number) % 3600) / 60)
                    .toString()
                    .padStart(2, "0");
                const seconds = Math.floor((stats.took as number) % 60)
                    .toString()
                    .padStart(2, "0");

                stats.took = `${hours}:${minutes}:${seconds}`;
                stats.started_at = dayjs(stats.started_at).calendar();
                stats.first_word_found_at = dayjs(
                    stats.first_word_found_at
                ).calendar();
                stats.finished_at = dayjs(stats.finished_at).calendar();

                return Promise.resolve(stats);
            });
    }

    public userExists(username: string): Promise<boolean> {
        return this.axios
            .head("/users/" + username)
            .then(() => Promise.resolve(true))
            .catch(() => Promise.resolve(false));
    }
}
