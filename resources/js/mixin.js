import Vue from 'vue';
import {numberFormat, parseIntEx} from "./utils";

Vue.mixin({
    data() {
        return {
            auth: window.$auth,
        }
    },
    methods: {
        truncate(str, length) {
            var dots = str.length > length ? '...' : '';
            return str.substring(0, length)+dots;
        },
        n(a) {
            a = parseIntEx(a);
            return numberFormat(a)
        },

        d(tzTime) {
            if (!tzTime) {
                return '';
            }
            const m =  moment(tzTime);
            // if (m.format('YYYY') == (new Date).getFullYear()) {
            //     return m.format('DD/MM HH:mm')
            // }

            return m.format('DD/MM/YYYY HH:mm')
        },
        m: function (value, rate, reverse) {
            if (typeof value === 'string') {
                value = Number(value.replace(/,/g,''));
            }

            if (reverse === true) {
                return formatNumber(Math.ceil(value * rate));
            }
            return formatNumber(value/rate,2);
        },
        fromNow: function (time) {
            if (!time) {
                return ''
            }
            return moment(time).fromNow();
        }

    }
});

export const toNumber =  (val) => {
    if (val === null || val === undefined || val === '') {
        return null;
    }
    val = val + '';
    return val.replace(/[^0-9]/g, '');
};
