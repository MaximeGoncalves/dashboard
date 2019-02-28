<template>
    <div>
        <div class="card shadow mb-2" v-if="ticket.attachments && ticket.attachments[0] ">
            <div class="card-body">
                <h4 class="card-title">Fichiers attachés</h4>
                <div class="card mb-1 shadow-none border" v-for="attachment in ticket.attachments">
                    <div class="p-2">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <i class="fas fa-paperclip"></i>
                            </div>
                            <div class="col pl-0">
                                <a class="text-muted font-weight-bold"
                                   :href="attachment.link"
                                   download>
                                    {{ attachment.name }}
                                </a>
                                <p class="mb-0">2.3 MB</p>
                            </div>
                            <div class="col-auto">
                                <!-- Button -->
                                <a href="javascript:void(0);" class="btn btn-link btn-lg text-muted">
                                    <i class="dripicons-download"></i>
                                </a>
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
    props : ['ticket']
}</script>
