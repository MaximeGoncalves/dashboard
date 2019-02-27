import moment from "moment/moment";
import Vue from 'vue'

Vue.filter('formatDate', function (date) {
    moment.locale('fr');
    return moment(date).format('LL');
});

