<template>
    <div>
        <h1>Ajouter une nouvelle connaissance</h1>
        <form @submit.prevent="save">
            <input type="text" class="form-control form-control-alternative" placeholder="Titre" v-model="form.title">
            <small v-if="errors.title" :class="errors.title ? 'text-danger' : ''"
                   v-for="error in errors.title">{{ error }}
            </small>
            <v-select label="name"
                      :options="categories"
                      taggable push-tags
                      selectOnTab
                      clearable
                      :close-on-select="false"
                      v-model="form.category"
                      class="form-control-alternative mt-2 mb-2 bg-white"
                      placeholder="Catégorie"></v-select>
            <small v-if="errors.category" :class="errors.category ? 'text-danger' : ''"
                   v-for="error in errors.category">{{ error }}
            </small>
            <editor
                ref="tuiEditor"
                v-model="form.content"
                :options="editorOptions"
                :html="editorHtml"
                :mode="editorMode"
                :previewStyle="editorPreviewStyle"
                height="600px"
            />
            <small v-if="errors.content" :class="errors.content ? 'text-danger' : ''"
                   v-for="error in errors.content">{{ error }}
            </small>
            <button class="btn btn-primary mt-2 float-right" type="submit">Save</button>
        </form>
    </div>
</template>

<script>
import {Editor} from '@toast-ui/vue-editor';

export default {
    components: {
        Editor,
    },
    data() {
        return {
            form: {
                title: '',
                category: '',
                content: '',
            },
            errors: [],
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
            editorPreviewStyle: 'vertical'
        };
    },
    methods: {
        save() {
            this.$Progress.start()
            axios.post('/api/knowledges', {
                ...this.form
            }).then(response => {
                this.$toasted.global.flash({message: "La création à été sauvegardée."});
                this.$Progress.finish()
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail()
            })
        },
        getCategories() {
            axios.get('/api/knowledges').then(response => {
                console.log(response)
                this.categories = response.data.categories
            })
        }
    },
    mounted() {
        this.getCategories()
    }
};
</script>

<style>
    @import 'https://uicdn.toast.com/tui-editor/latest/tui-editor.css';


</style>
