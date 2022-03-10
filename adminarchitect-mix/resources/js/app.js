require('./bootstrap');

import store from './media/store';
import lang from 'element-ui/lib/locale/lang/fr';
import locale from 'element-ui/lib/locale';

locale.use(lang);

new Vue({
    el: '#app',
    store
});
