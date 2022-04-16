export interface Word {
    readonly text: string;
    readonly found: boolean;
}

export interface Cell {
    readonly letter: string;
    readonly found: boolean;
    selected: boolean;
    wrong: boolean;
}
