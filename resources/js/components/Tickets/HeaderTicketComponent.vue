<template>
    <div class="row m-0 px-2 py-3">
        <div class="col d-flex">
            <div class="dropdown">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Nouveau <i class="fas fa-angle-down"></i>
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a href="#textarea-comment" class="dropdown-item"
                       @click="$emit('addComment')">Nouveau message</a>
                    <a href="#note" class=" dropdown-item" v-if="$gate.isAdmin()"
                       @click.prevent="$emit('addNote')">Nouvelle note</a>
                </div>
            </div>
            <label for="files" class="btn btn-default m-0 mr-2">Ajouter des fichiers</label>
            <input type="file"
                   id="files"
                   class="form-control form-control-alternative input-file"
                   placeholder="Ajouter des Fichiers"
                   multiple
                   v-on:change="handleFileUploads">
            <a class="btn btn-danger text-white"
               v-if="$gate.isAdmin()"
               @click="$emit('deleteTicket')">Supprimer</a>
        </div>
    </div>
</template>
<script>
export default {
    props: ['element', 'type', 'ticket'],
    data() {
        return {
            errors: [],
            files: [],
            uploadText: 'Attendez la fin du téléchargement',
        }
    },
    methods: {
        handleFileUploads(e) {
            this.$Progress.start();
            this.$Progress.set(0)
            let files = e.target.files || e.dataTransfer.files;
            for (let i = files.length - 1; i >= 0; i--) {
                this.files.push(files[i]);
            }
            let formData = new FormData();
            for (var i = 0; i < this.files.length; i++) {
                let file = this.files[i];
                formData.append('files[' + i + ']', file);
            }
            formData.append('type', this.type)

            let self = this
            const config = {
                onUploadProgress: function (progressEvent) {
                    let percent = (Math.round((progressEvent.loaded * 100) / progressEvent.total)).toString()
                    if (percent === '100') {
                        self.$emit('uploadFinish')
                    }
                    swal({
                        title: percent + '%',
                        text: self.uploadText,
                        icon: "info",
                        dangerMode: false,
                    })
                }
            }
            axios.post('/api/attachment/' + this.element.id, formData, config)
                .then(response => {
                    for (let i = 0; i < response.data.length; i++) {
                        this.element.attachments.push(response.data[i])
                    }
                    this.files = []
                    this.$Progress.finish();
                }).catch(error => {
                this.$Progress.fail()
                this.errors = error.response.data.message;
                swal({
                    title: "Une erreur est survenue",
                    text: this.errors,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
            })
        },
    },
    mounted() {
        this.$on('uploadFinish', () => {
            this.uploadText = 'Téléchargement terminé.'
        })
    }
}

</script>
