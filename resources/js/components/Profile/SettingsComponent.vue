<style>
    .timeline {
        position: relative;
        margin: 0 0 30px 0;
        padding: 0;
        list-style: none;
    }

    .timeline > .time-label > span {
        font-weight: 600;
        padding: 5px;
        display: inline-block;
        background-color: #fff;
        border-radius: 4px;
        color: white;
    }

    .timeline:before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #ddd;
        left: 31px;
        margin: 0;
        border-radius: .25rem;
    }

    .timeline > li {
        position: relative;
        margin-right: 10px;
        margin-bottom: 15px;
    }

    .timeline > li::after {
        display: block;
        clear: both;
        content: "";
    }

    .timeline > li > .fa, .timeline > li > .glyphicon, .timeline > li > .ion {
        width: 30px;
        height: 30px;
        font-size: 15px;
        line-height: 30px;
        position: absolute;
        background: #adb5bd;
        border-radius: 50%;
        text-align: center;
        left: 18px;
        top: 0;
        color: white;
    }

    .timeline-inverse > li > .timeline-item {
        background: #f8f9fa;
        box-shadow: none;
    }

    .timeline > li > .timeline-item {
        box-shadow: 0 0 2rem 0 rgba(136, 152, 170, .15) !important;
        border-radius: .25rem;
        margin-top: 0;
        background: #fff;
        color: #444;
        margin-left: 60px;
        margin-right: 15px;
        padding: 0;
        position: relative;
    }

    .timeline > li > .timeline-item > .time {
        color: #999;
        float: right;
        padding: 10px;
        font-size: 12px;
    }

    .timeline > li > .timeline-item > .timeline-header {
        margin: 0;
        color: #555;
        border-bottom: 1px solid rgba(0, 0, 0, .125);
        padding: 10px;
        font-size: 16px;
        line-height: 1.1;
    }

    .timeline-inverse > li > .timeline-item > .timeline-header {
        border-bottom-color: #ddd;
    }

    .timeline > li > .timeline-item > .timeline-body, .timeline > li > .timeline-item > .timeline-footer {
        padding: 10px;
    }

    .timeline > li > .timeline-item > .timeline-body, .timeline > li > .timeline-item > .timeline-footer {
        padding: 10px;
    }
</style>

<template>
    <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
            <ul class="nav nav-pills">
                <!--<li class="nav-item"><a class="nav-link active show" href="#timeline" data-toggle="tab">Timeline</a>-->
                <!--</li>-->
                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
            </ul>
        </div><!-- /.card-header -->
        <div class="card-body">
            <div class="tab-content">
                <!-- /.tab-pane -->
                <!--<div class="tab-pane active show" id="timeline">-->
                    <!--&lt;!&ndash; The timeline &ndash;&gt;-->
                    <!--<ul class="timeline timeline-inverse">-->
                        <!--&lt;!&ndash; timeline time label &ndash;&gt;-->
                        <!--<li class="time-label">-->
                        <!--<span class="bg-primary p-2">-->
                          <!--10 Feb. 2014-->
                        <!--</span>-->
                        <!--</li>-->
                        <!--&lt;!&ndash; /.timeline-label &ndash;&gt;-->
                        <!--&lt;!&ndash; timeline item &ndash;&gt;-->
                        <!--<li>-->
                            <!--<i class="fa fa-envelope bg-primary"></i>-->

                            <!--<div class="timeline-item">-->
                                <!--<span class="time"><i class="fa fa-clock-o"></i> 12:05</span>-->

                                <!--<h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>-->

                                <!--<div class="timeline-body">-->
                                    <!--Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,-->
                                    <!--weebly ning heekya handango imeem plugg dopplr jibjab, movity-->
                                    <!--jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle-->
                                    <!--quora plaxo ideeli hulu weebly balihoo...-->
                                <!--</div>-->
                                <!--<div class="timeline-footer">-->
                                    <!--<a href="#" class="btn btn-primary btn-sm">Read more</a>-->
                                    <!--<a href="#" class="btn btn-danger btn-sm">Delete</a>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</li>-->
                        <!--&lt;!&ndash; END timeline item &ndash;&gt;-->
                        <!--&lt;!&ndash; timeline item &ndash;&gt;-->
                        <!--<li>-->
                            <!--<i class="fa fa-user bg-info"></i>-->

                            <!--<div class="timeline-item">-->
                                <!--<span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>-->

                                <!--<h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend-->
                                    <!--request-->
                                <!--</h3>-->
                            <!--</div>-->
                        <!--</li>-->
                        <!--&lt;!&ndash; END timeline item &ndash;&gt;-->
                        <!--&lt;!&ndash; timeline item &ndash;&gt;-->
                        <!--<li>-->
                            <!--<i class="fa fa-comments bg-warning"></i>-->

                            <!--<div class="timeline-item">-->
                                <!--<span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>-->

                                <!--<h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>-->

                                <!--<div class="timeline-body">-->
                                    <!--Take me to your leader!-->
                                    <!--Switzerland is small and neutral!-->
                                    <!--We are more like Germany, ambitious and misunderstood!-->
                                <!--</div>-->
                                <!--<div class="timeline-footer">-->
                                    <!--<a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</li>-->
                        <!--&lt;!&ndash; END timeline item &ndash;&gt;-->
                        <!--&lt;!&ndash; timeline time label &ndash;&gt;-->
                        <!--<li class="time-label">-->
                        <!--<span class="bg-success">-->
                          <!--3 Jan. 2014-->
                        <!--</span>-->
                        <!--</li>-->
                        <!--&lt;!&ndash; /.timeline-label &ndash;&gt;-->
                        <!--&lt;!&ndash; timeline item &ndash;&gt;-->
                        <!--<li>-->
                            <!--<i class="fa fa-camera bg-purple"></i>-->

                            <!--<div class="timeline-item">-->
                                <!--<span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>-->

                                <!--<h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>-->

                                <!--<div class="timeline-body">-->
                                    <!--<img src="http://placehold.it/150x100" alt="..." class="margin">-->
                                    <!--<img src="http://placehold.it/150x100" alt="..." class="margin">-->
                                    <!--<img src="http://placehold.it/150x100" alt="..." class="margin">-->
                                    <!--<img src="http://placehold.it/150x100" alt="..." class="margin">-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</li>-->
                        <!--&lt;!&ndash; END timeline item &ndash;&gt;-->
                        <!--<li>-->
                            <!--<i class="fa fa-clock-o bg-gray"></i>-->
                        <!--</li>-->
                    <!--</ul>-->
                <!--</div>-->
                <!-- /.tab-pane -->

                <div class="tab-pane active show" id="settings">
                    <form @submit.prevent="UpdateProfile(user.id)">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <input type="text"
                                           class="form-control form-control-alternative"
                                           name="name"
                                           placeholder="Username"
                                           v-model="user.name" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group " :class="errors.fullname ? 'has-danger' : ''">
                                    <input type="text"
                                           class="form-control form-control-alternative"
                                           :class="errors.fullname ? 'is-invalid' : ''"
                                           name="fullname"
                                           placeholder="Nom complet"
                                           v-model="user.fullname">
                                    <small v-if="errors.fullname" :class="errors.fullname ? 'text-danger' : ''"
                                           v-for="error in errors.fullname">
                                        {{ error }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <input type="email"
                                           class="form-control form-control-alternative"
                                           name="email"
                                           placeholder="E-mail"
                                           v-model="user.email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <input type="file"
                                           class="form-control form-control-alternative"
                                           name="photo"
                                           placeholder="Photo de profile"
                                           @change="UpdatePhoto"
                                    >
                                    <small class="ml-3">Taille recommandé 100 x 100</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group " :class="errors.password ? 'has-danger' : ''">
                                    <input type="password"
                                           class="form-control form-control-alternative"
                                           :class="errors.password ? 'is-invalid' : ''"
                                           name="password"
                                           placeholder="Password"
                                           v-model="user.newPassword"
                                    value="">
                                    <small v-if="errors.password" :class="errors.password ? 'text-danger' : ''"
                                           v-for="error in errors.password">{{
                                        error }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group" :class="errors.password ? 'has-danger' : ''">
                                    <input type="password"
                                           class="form-control form-control-alternative"
                                           :class="errors.password ? 'is-invalid' : ''"
                                           name="confirm-password"
                                           placeholder="Confirm password"
                                           v-model="user.confirmPassword">
                                </div>
                                <small v-if="errors.password" :class="errors.password ? 'text-danger' : ''"
                                       v-for="error in errors.password">
                                    {{ error }}
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
        </div><!-- /.card-body -->
    </div>
</template>

<script>
export default {

    props: [
        'user'
    ],
    data (){
        return{
            errors: []
        }
    },
    methods: {
        UpdateProfile(user) {
            this.errors = []
            this.$Progress.start()
            axios.put('/api/updateProfile', {
                fullname: this.user.fullname,
                email: this.user.email,
                photo: this.user.photo,
                password: this.user.newPassword,
                password_confirmation: this.user.confirmPassword
            }).then(() => {
                Fire.$emit('UpdatePhoto')
                this.$toasted.global.flash({message : "Vos informations ont été enregistrées."});
                this.$Progress.finish()
                this.user.newPassword = ''
                this.user.confirmPassword = ''
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.$Progress.fail()
            })
        },

        UpdatePhoto(e) {
            let file = e.target.files[0];
            let reader = new FileReader();
            reader.onloadend = (file) => {
                this.user.photo = reader.result
            }
            reader.readAsDataURL(file)
        }
    }

}
</script>
