import moment from 'moment'

export function messengerStyleTime(timestamp: string): string {
    const now = moment()
    const date = moment(timestamp)

    if (now.isSame(date, 'day')) {
        return date.format('h:mm A')
    } else if (now.subtract(1, 'day').isSame(date, 'day')) {
        return 'Yesterday'
    } else if (now.isSame(date, 'week')) {
        return date.format('dddd')
    } else if (now.isSame(date, 'year')) {
        return date.format('MMM D')
    } else {
        return date.fromNow()
    }
}

export function formatNumber(value: number): number | string {
    const number_formatter = new Intl.NumberFormat()
    return number_formatter.format(value)
}

export function deltaValue(current: number, previous?: number): number {
    return current - (previous ?? 0)
}

export function deltaLabel(current: number, previous?: number): string {
    if (previous !== undefined) {
        const delta = deltaValue(current, previous ?? 0)
        const absolute_delta = Math.abs(delta)

        if (delta > 0) {
            return '+' + formatNumber(absolute_delta)
        }

        if (delta < 0) {
            return '-' + formatNumber(absolute_delta)
        }

        return 'No change'
    } else {
        return 'total'
    }
}
