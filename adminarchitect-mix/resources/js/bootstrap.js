const Vue = window.Vue = require('vue');

import VueClip from 'vue-clip';
import {Tag, Select, Option, Button, Dialog, DatePicker, TimePicker,Pagination} from 'element-ui';
import {
    MediaManager,
    MediaLibrary,
    MediaPagination,
    FoldersList,
    FilesList,
    FileInfo,
    FileActions,
    DropZone,
    InstantSearch,
    TagList,
    DateTimePicker,
    InputsField
} from './components';
import {MkDir, Move, Rename} from './components/popups';
import {ModalHeader, ModalFooter} from './components/partials';

Vue.filter('truncate', (value, length) => {
    const l = value.length;

    return value.substr(0, length) + ((l > length) ? '...' : '');
});

// VueClip
Vue.use(VueClip);

// Element UI
Vue.use(Tag);
Vue.use(Select);
Vue.use(Option);
Vue.use(Button);
Vue.use(Dialog);
Vue.use(DatePicker);
Vue.use(TimePicker);
Vue.use(Pagination);
Vue.use(InputsField);

Vue.component('MediaManager', MediaManager);
Vue.component('MediaLibrary', MediaLibrary);
Vue.component('MediaPagination', MediaPagination);
Vue.component('FoldersList', FoldersList);
Vue.component('FilesList', FilesList);
Vue.component('FileInfo', FileInfo);
Vue.component('FileActions', FileActions);
Vue.component('DropZone', DropZone);
Vue.component('InstantSearch', InstantSearch);
Vue.component('TagList', TagList);
Vue.component('DateTimePicker', DateTimePicker);
Vue.component('InputsField', InputsField);

// Popups
Vue.component('MakeDirPopup', MkDir);
Vue.component('MovePopup', Move);
Vue.component('RenamePopup', Rename);

// Partials
Vue.component('ModalHeader', ModalHeader);
Vue.component('ModalFooter', ModalFooter);
