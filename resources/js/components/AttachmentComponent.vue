<template>
    <div v-if="element.attachments != 0">
        <div class="row">
            <div class="col-05">
                <i class="fas fa-paperclip text-muted mt-3"></i>
            </div>
            <div class="col">
                <div class="row">
                    <div class="card mb-1 shadow-none border col-3 mr-2"
                         v-for="(attachment, key) in element.attachments"
                         style="word-wrap:normal">
                        <div class="p-1">
                            <div class="row align-items-center ">
                                <div class="col-lg-3 pl-0 pr-2">
                                    <div class="attachment-avatar-sm">
                                                                    <span class="avatar-title bg-default rounded">
                                                                        .{{ attachment.type }}
                                                                    </span>
                                    </div>
                                </div>
                                <div class="col-lg-7 pl-0 elypsis" >
                                    <a class="text-default text-wrap"
                                       :href="attachment.url"
                                       download
                                       style="font-size:12px">
                                        {{ attachment.name }}
                                    </a>
                                    <p class="mb-0 text-muted" v-if="attachment.size > 10000 ">{{
                                        (attachment.size / 1000000).toFixed(2) }} MB</p>
                                    <p class="mb-0 text-muted" v-else>{{ (attachment.size /
                                        1000).toFixed(2) }} KB</p>
                                </div>
                                <div class="col-lg-2 px-1">
                                    <!-- Button -->
                                    <a href="#"
                                       class="btn btn-link btn-lg text-muted"
                                       @click.prevent="deleteFile(attachment.id, key)">
                                        <i class="fas fa-times"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    props: ['element', 'type'],
    methods: {
        deleteFile(id, key) {
            swal({
                title: "Etes vous sur ? ",
                text: "Cette manipulation est irréversible !",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then(willDelete => {
                    if (willDelete) {
                        this.$Progress.start();
                        axios.delete('/api/attachment/' + id, {
                            params: {
                                elementID: this.element.id,
                                type: this.type
                            }
                        }).then(response => {
                            this.element.attachments.splice(key, 1);
                            swal("Le fichier à été supprimé", {
                                icon: "success",
                            });
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
                    } else {
                        swal("La suppression est annulée.");
                    }
                });
        },
    },
}</script>

<style>
    .elypsis{
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
