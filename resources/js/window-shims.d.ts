import { LoDashStatic } from "lodash";
import { AxiosInstance } from "axios";

declare global {
    interface Window {
        _: LoDashStatic;
        axios: AxiosInstance;
    }
}
