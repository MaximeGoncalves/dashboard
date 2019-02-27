<template>
    <div>
        <h1>Ajouter une nouvelle connaissance</h1>
        <editor
            ref="tuiEditor"
            v-model="editorText"
            :options="editorOptions"
            :html="editorHtml"
            :mode="editorMode"
            :previewStyle="editorPreviewStyle"
            height="600px"
        />
        <viewer
            class=""
            :value="editorText"
        />
    </div>
</template>

<script>
import {Editor, Viewer} from '@toast-ui/vue-editor';

export default {
    components: {
        Editor,
        Viewer
    },
    data() {
        return {
            message: '',
            viewerText: '# TOAST UI Markdown Viewer + Vue\n This is Viewer.',
            editorText: 'This is initialValue.',
            editorOptions: {
                minHeight:'0',
                hideModeSwitch: false,
                toolbarItems: [
                    'heading',
                    'bold',
                    'italic',
                    'strike',
                    'divider',
                    'hr',
                    'quote',
                    'divider',
                    'ul',
                    'ol',
                    'task',
                    'indent',
                    'outdent',
                    'divider',
                    'table',
                    'image',
                    'link',
                    'divider',
                    'code',
                    'codeblock'
                ]
            },
            editorHtml: '',
            editorMode: 'markdown',
            editorVisible: true,
            editorPreviewStyle: 'vertical'
        };
    },
};
</script>

<style>
    @import 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.css';
    @import 'https://uicdn.toast.com/tui-editor/latest/tui-editor-contents.css';
    @import 'https://uicdn.toast.com/tui-editor/latest/tui-editor.css';
    @import 'https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.15.5/styles/atom-one-dark.min.css';

</style>
