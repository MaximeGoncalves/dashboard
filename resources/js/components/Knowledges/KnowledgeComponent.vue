<template>
    <div class="container">

        <button class="btn btn-primary mb-2" @click.prevent="editable" v-if="!edit">Editer</button>
        <div class="card p-4" v-if="!edit">
            <h2>{{ form.title }}</h2>
            <h4 class="text-muted">{{ form.category.name }}</h4>
            <viewer
                class=""
                :value="form.content"
            />
        </div>
        <div class="card p-4" v-if="edit">
            <form @submit.prevent="updateKnowledge">
                <input type="text" v-model="form.title"
                                   class="form-control form-control-alternative"></input>
                <v-select label="name"
                          :options="categories"
                          taggable push-tags
                          selectOnTab
                          clearable
                          :close-on-select="false"
                          v-model="form.category"
                          class="form-control-alternative mt-2 mb-2 bg-white"
                          placeholder="Catégorie"></v-select>
                <editor
                    ref="tuiEditor"
                    v-model="form.content"
                    :options="editorOptions"
                    :html="editorHtml"
                    :mode="editorMode"
                    :previewStyle="editorPreviewStyle"
                    height="600px"
                ></editor>
                <button class="btn btn-primary mt-2 float-right" type="submit">Save</button>
            </form>
        </div>
    </div>
</template>

<script>
import 'tui-editor/dist/tui-editor-contents.css';
import 'highlight.js/styles/atom-one-dark-reasonable.css';

import {Editor, Viewer} from '@toast-ui/vue-editor';

export default {

    components: {
        Viewer,
        Editor,
    },
    data() {
        return {
            form: {
                title: '',
                category: '',
                content: '',
            },
            categories: [],
            editorOptions: {
                minHeight: '0',
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
            editorPreviewStyle: 'vertical',
            edit: false,
        }
    },
    methods: {
        editable() {
            this.edit = true
        },
        updateKnowledge(){
            axios.put('/api/knowledges/' + this.$route.params.id, {
                ...this.form
            }).then(response => {
                this.edit = false
                console.log(response)
            })
        }
    },
    mounted() {

        axios.get('/api/knowledges/' + this.$route.params.id).then(response => {
            this.form.title = response.data.response.knowledge.title
            this.form.content = response.data.response.knowledge.content
            this.form.category = response.data.response.knowledge.category
            this.categories = response.data.response.categories

        })
    }
}
</script>

<style>
    @import 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.css';

</style>
