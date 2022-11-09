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
                return Promise.resolve(response.data);
            });
    }

    public userExists(username: string): Promise<boolean> {
        return this.axios
            .head("/users/" + username)
            .then(() => Promise.resolve(true))
            .catch(() => Promise.resolve(false));
    }
}
