import moment from 'moment'

export function messengerStyleTime(timestamp: string) {
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
