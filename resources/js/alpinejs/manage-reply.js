window.judging = () => {
    return {
        court: false,
        killed: false,
        judge() { this.court = true },
        spare() { this.court = false },
        isDying() { return this.court === true },
        isAlive() { return this.court === false },
        kill() { this.killed = true },
    }
}

window.changing = () => {
    return {
        show: false,
        open() { this.show = true },
        close() { this.show = false },
        isOpen() { return this.show === true },
        isClose() { return this.show === false },
    }
}