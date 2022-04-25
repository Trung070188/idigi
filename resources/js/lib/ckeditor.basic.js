export const loadCKEditor = (ckeditor_element) => {
    if (!window.CKEDITOR) {
        console.error('CKEditor not found');

        return;
    }

    CKEDITOR.replace(ckeditor_element, {
        extraPlugins: 'codesnippet',
        codeSnippet_theme: 'monokai_sublime',
        height: 150,
        toolbar: [
            {
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language']
            },
            {name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize']},
            {name: 'colors', items: ['TextColor', 'BGColor']}
        ]
    });

    CKEDITOR.config.allowedContent = true;

    return CKEDITOR;
};