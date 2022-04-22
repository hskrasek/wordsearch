/**
 * @see bresenham-algorithm https://stackoverflow.com/questions/4672279/bresenham-algorithm-in-javascript
 * @param x0
 * @param y0
 * @param x1
 * @param y1
 */
export function* bresenham(x0: number, y0: number, x1: number, y1: number) {
    const dx = Math.abs(x1 - x0);
    const dy = Math.abs(y1 - y0);
    const sx = x0 < x1 ? 1 : -1;
    const sy = y0 < y1 ? 1 : -1;
    let err = dx - dy;

    while (x0 !== x1 || y0 !== y1) {
        yield { x: x0, y: y0 };

        const e2 = 2 * err;
        if (e2 > -dy) {
            err -= dy;
            x0 += sx;
        }
        if (e2 < dx) {
            err += dx;
            y0 += sy;
        }
    }
}

export function getCoords(element: HTMLElement) {
    const box = element.getBoundingClientRect();

    return {
        top: box.top + window.scrollY,
        right: box.right + window.scrollX,
        bottom: box.bottom + window.scrollY,
        left: box.left + window.scrollX,
    };
}
