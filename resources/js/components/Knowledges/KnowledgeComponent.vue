<template>
    <div class="container">
        <h2>{{ title }}</h2>
        <button class="btn btn-primary" @click.prevent="edit = true">Editer</button>
        <div class="card p-4">

            <h4>{{ category }}</h4>
            <viewer
                class=""
                :value="content"
                v-if="!edit"
            />
            <editor
                ref="tuiEditor"
                v-model="form.content"
                :options="editorOptions"
                :html="editorHtml"
                :mode="editorMode"
                :previewStyle="editorPreviewStyle"
                height="600px"
                v-if="edit"
            ></editor>
        </div>
    </div>
</template>

<script>
import 'tui-editor/dist/tui-editor-contents.css';
import 'highlight.js/styles/atom-one-dark-reasonable.css';

import {Viewer} from '@toast-ui/vue-editor';

export default {

    components: {
        Viewer
    },
    data() {
        return {
            edit: false,
            title:'',
            content : '',
            category : {},
        }
    },
    mounted (){

      axios.get('/api/knowledges/' + this.$route.params.id).then(response => {
          this.title = response.data.title
          this.content = response.data.content
          this.category = response.data.category
        })
    }
}
</script>

<style>
    @import 'https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.41.0/codemirror.css';

</style>
