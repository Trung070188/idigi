export const loadCKEditor = (ckeditor_element) => {
    if (!window.CKEDITOR) {
        console.error('CKEditor not found');

        return;
    }

    CKEDITOR.replace(ckeditor_element, {
        extraPlugins: 'codesnippet,colorbutton,colordialog',
        codeSnippet_theme: 'monokai_sublime',
        height: 650,
        //filebrowserBrowseUrl: '/js/ckfinder/ckfinder.html',
        //filebrowserImageBrowseUrl: '/js/ckfinder/ckfinder.html?type=Images',
        //filebrowserUploadUrl: '/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        //filebrowserImageUploadUrl: '/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
        filebrowserImageUploadUrl: '/xadmin/ckeditor/upload?type=Images&_token=' + document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        toolbar: [
            { name: 'document', items: ['Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates'] },
            { name: 'clipboard', items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'] },
            { name: 'editing', items: ['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'] },
            {
                name: 'forms',
                items: ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField']
            },
            //'/',
            {
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language']
            },
            { name: 'links', items: ['Link', 'Unlink', 'Anchor'] },
            {
                name: 'insert',
                items: ['Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe']
            },
            //'/',
            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'tools', items: ['Maximize', 'ShowBlocks'] },
            { name: 'about', items: ['About'] }
        ]
    });

    CKEDITOR.config.allowedContent = true;

    return CKEDITOR;
};