import moment from "moment/moment";
import Vue from 'vue'

Vue.filter('formatDate', function (date) {
    moment.locale('fr');
    return moment(date).format('LL');
});

Vue.filter('shortDate', function (date) {
    moment.locale('fr');
    return moment(date).format('Do MMMM');
});

Vue.filter('FullDate', function (date) {
    moment.locale('fr');
    return moment(date).format('llll');
});

Vue.filter('formatDateInHour', function (date) {
    moment.locale('fr');
    // return moment(date).format('LL');
    return moment(date).startOf('hour').fromNow();
});

Vue.filter('dateFromNow', function (date) {
    moment.locale('fr');
    // return moment(date).format('LL');
    return moment(date).fromNow();
});

