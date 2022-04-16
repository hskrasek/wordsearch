import { LoDashStatic } from "lodash";
import { AxiosInstance } from "axios";
import { Config, RouteParamsWithQueryOverload, Router } from "ziggy-js";

declare global {
    interface Window {
        _: LoDashStatic;
        axios: AxiosInstance;
    }

    declare function route(): Router;
    declare function route(
        name: string,
        params?: RouteParamsWithQueryOverload,
        absolute: ?boolean,
        customZiggy?: Config
    ): string;
}
