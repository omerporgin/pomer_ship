import TimeAgo from 'javascript-time-ago';
import tr from 'javascript-time-ago/locale/tr';

/**
 *
 */
export class TimeAgoObj {

    constructor() {
        try {
            TimeAgo.setDefaultLocale(tr.locale);
            TimeAgo.addLocale(tr);
            this.timeAgo = new TimeAgo('tr-TR');
        } catch (e) {
            console.log(e);
        }
    };

    /**
     *
     */
    showUnixTimeStamp(newDate) {
        var unixTimeZero = Date.parse(newDate);
        return this.show(unixTimeZero);
    }

    /**
     *
     */
    show(newDate) {
        return this.timeAgo.format(newDate);
    }

}
