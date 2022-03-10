let tinymcemini = require('tinymce');
require('tinymce/themes/silver/theme');
require('tinymce/icons/default/icons');
require('tinymce/plugins/advlist');
require('tinymce/plugins/autolink');
require('tinymce/plugins/lists');
require('tinymce/plugins/paste');
require('tinymce/plugins/link');
require('tinymce/plugins/image');
//require('tinymce/plugins/charmap');
//require('tinymce/plugins/print');
//require('tinymce/plugins/preview');
//require('tinymce/plugins/searchreplace');
require('tinymce/plugins/code');
//require('tinymce/plugins/fullscreen');
require('tinymce/plugins/media');
require('tinymce/plugins/table');

tinymce.init({
    selector: 'textarea[data-editor="tinymce"]',
    plugins: [
        "advlist autolink lists link image",
        "code",
        "media table paste"
    ],
    branding : false,
    image_advtab: false,
    images_upload_url: '/administration/media/upload',
    images_upload_credentials: true,
    images_upload_base_path : '/media/',
    relative_urls: false,
    image_uploadtab: false,
    height: 400,
    entity_encoding: 'raw',
    //statusbar: false,
    toolbar: 'undo redo' +
        ' | removeformat | formatselect | bold italic underline  superscript subscript strikethrough' +
        ' | alignleft  aligncenter alignright alignjustify' +
        ' | bullist numlist blockquote outdent indent' +
        ' | link image table' +
        ' | code',
    file_picker_callback : function(cb,value,meta){
        let popup = window.open('/administration/media/popup', '_blank', 'fullscreen=1,location=0,menubar=0,status=0,toolbar=0,titlebar=0,scrollbars=0');
        if (popup) {
            window['onMediaFileSelected'] = function(url) {
                let location = window.location.origin,
                img_url = url.replace(location,'')
                cb(img_url,{alt : 'Test'})
                popup.close();
                return url;
            };
            popup.opener = window;
        }

        return false;
    },

    menubar: false,
    //toolbar: 'undo redo' +
    //' | bold italic underline strikethrough' +
    //' | alignleft  aligncenter alignright alignjustify' +
    //' | bullist numlist blockquote outdent indent' +
    //' | link image imagetools' +
    //' | removeformat code brbtn',
    //plugins: [
    //    'lists autolink link image preview print',
    //    'fullscreen code paste',
    //],
    setup: (editor) => {
        let e = editor.getElement();
        editor.settings.readonly = e.readOnly || e.disabled;

       // editor.addButton('brbtn', {
       //     icon: 'line',
       //     tooltip: "Insert New Line",
       //     onclick: function() {
       //         editor.insertContent('<br />');
       //     },
       // });
    },
});

tinymce.init({
    selector: 'textarea[data-editor="tinymcemini"]',
    plugins: [
        "autolink link ",
        "code",
        "paste"
    ],
    /*image_advtab: true,
    images_upload_url: '/administration/media/upload',
    relative_urls: false,*/
    height: 200,
    statusbar: false,
    paste_as_text: true,
    branding: false,
    forced_root_block: '',
    entity_encoding: 'raw',
    menubar: false,
    toolbar: 'undo redo' +
        ' | bold italic underline' +
        //' | alignleft  aligncenter alignright alignjustify' +
        //' | bullist numlist blockquote outdent indent' +
        ' | link' +
        ' | removeformat code brbtn',
    //plugins: [
    //    'lists autolink link image preview print',
    //    'fullscreen code paste',
    //],
    /*setup: (editor) => {
        let e = editor.getElement();
        editor.settings.readonly = e.readOnly || e.disabled;

        // editor.addButton('brbtn', {
        //     icon: 'line',
        //     tooltip: "Insert New Line",
        //     onclick: function() {
        //         editor.insertContent('<br />');
        //     },
        // });
    },*/
});

