<template>
    <div>
        <div class="row">
                <div class="col-12 col-md-8">
                    <div class="card d-block">
                        <div class="card-body">
                            <div class="dropdown float-right">
                                <a href="#" class="dropdown-toggle arrow-none card-drop" data-toggle="dropdown" aria-expanded="false">
                                    <i class="dripicons-dots-3"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-142px, 20px, 0px);">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="mdi mdi-pencil mr-1"></i>Edit</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="mdi mdi-delete mr-1"></i>Delete</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="mdi mdi-email-outline mr-1"></i>Invite</a>
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item"><i class="mdi mdi-exit-to-app mr-1"></i>Leave</a>
                                </div>
                            </div>
                            <!-- project title-->
                            <h3 class="mt-0">
                                App design and development
                            </h3>
                            <div class="badge badge-secondary mb-3">Ongoing</div>

                            <h5>Project Overview:</h5>

                            <p class="text-muted mb-2">
                                With supporting text below as a natural lead-in to additional contenposuere erat a ante. Voluptates, illo, iste itaque voluptas
                                corrupti ratione reprehenderit magni similique? Tempore, quos delectus asperiores libero voluptas quod perferendis! Voluptate,
                                quod illo rerum? Lorem ipsum dolor sit amet.
                            </p>

                            <p class="text-muted mb-4">
                                Voluptates, illo, iste itaque voluptas corrupti ratione reprehenderit magni similique? Tempore, quos delectus asperiores
                                libero voluptas quod perferendis! Voluptate, quod illo rerum? Lorem ipsum dolor sit amet. With supporting text below
                                as a natural lead-in to additional contenposuere erat a ante.
                            </p>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <h5>Start Date</h5>
                                        <p>17 March 2018 <small class="text-muted">1:00 PM</small></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <h5>End Date</h5>
                                        <p>22 December 2018 <small class="text-muted">1:00 PM</small></p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <h5>Budget</h5>
                                        <p>$15,800</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h5>Team Members:</h5>
                                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mat Helme" class="d-inline-block">
                                    <img src="assets/images/users/avatar-6.jpg" class="rounded-circle img-thumbnail avatar-sm" alt="friend">
                                </a>

                                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Michael Zenaty" class="d-inline-block">
                                    <img src="assets/images/users/avatar-7.jpg" class="rounded-circle img-thumbnail avatar-sm" alt="friend">
                                </a>

                                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" data-original-title="James Anderson" class="d-inline-block">
                                    <img src="assets/images/users/avatar-8.jpg" class="rounded-circle img-thumbnail avatar-sm" alt="friend">
                                </a>

                                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Mat Helme" class="d-inline-block">
                                    <img src="assets/images/users/avatar-4.jpg" class="rounded-circle img-thumbnail avatar-sm" alt="friend">
                                </a>

                                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" data-original-title="Michael Zenaty" class="d-inline-block">
                                    <img src="assets/images/users/avatar-5.jpg" class="rounded-circle img-thumbnail avatar-sm" alt="friend">
                                </a>

                                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="" data-original-title="James Anderson" class="d-inline-block">
                                    <img src="assets/images/users/avatar-3.jpg" class="rounded-circle img-thumbnail avatar-sm" alt="friend">
                                </a>
                            </div>

                        </div> <!-- end card-body-->

                    </div>
                    <div class="card shadow mb-2">
                        <div class="card-body">
                            <h4 class="card-title">Nouveau message</h4>
                            <form @submit.prevent="addMessage">
                                <div class="form-group" :class="errors.content ? 'has-danger' : ''">
                            <textarea name="content" id="comment" cols="30" rows="3" class="form-control"
                                      v-model="message"></textarea>
                                    <small v-if="errors.content" :class="errors.content ? 'text-danger' : ''"
                                           v-for="error in errors.content">{{ error }}
                                    </small>
                                    <div class="row">
                                        <div class="col-12 d-flex justify-content-end">
                                            <button class="btn btn-primary mt-2 mb-2">Envoyer</button>
                                        </div>
                                    </div>

                                </div>
                            </form>
                            <div class="row" v-for="message in messages">
                                <message-component :msg="message" :key="message.id"></message-component>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <attachments-component :ticket="ticket"></attachments-component>
                    <action-component :actions="actions" :ticket="ticket"></action-component>
                </div>
            </div>
    </div>
</template>

<script>
export default {

}
</script>
