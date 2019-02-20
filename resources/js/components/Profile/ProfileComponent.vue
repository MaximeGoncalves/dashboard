<template>
    <div class="container-fluid mt--7">
        <div class="row">
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="#">
                                    <img
                                        :src=" photo "
                                        class="rounded-circle">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                        <div class="d-flex justify-content-between">
                            <a href="#" class="btn btn-sm btn-info mr-4">Connect</a>
                            <a href="#" class="btn btn-sm btn-default float-right">Message</a>
                        </div>
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <div class="row">
                            <div class="col">
                                <div class="card-profile-stats d-flex justify-content-center mt-md-5">
                                    <div>
                                        <span class="heading">22</span>
                                        <span class="description">Friends</span>
                                    </div>
                                    <div>
                                        <span class="heading">10</span>
                                        <span class="description">Photos</span>
                                    </div>
                                    <div>
                                        <span class="heading">89</span>
                                        <span class="description">Comments</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 v-model="user.fullname">{{ user.fullname }}</h3>
                            <div class="h5 font-weight-300">
                                <i class="ni location_pin mr-2"></i>
                                {{ user.society.name }}
                            </div>
                            <div class="h5 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                            </div>
                            <div>
                                <i class="ni education_hat mr-2"></i>University of Computer Science
                            </div>
                            <hr class="my-4">
                            <p>Ryan — the name taken by Melbourne-raised, Brooklyn-based Nick Murphy — writes, performs
                                and records all of his own music.</p>
                            <a href="#">Show more</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 order-xl-1">
                <settings :user="user"></settings>
            </div>
        </div>
    </div>
</template>

<script>
import Settings from './SettingsComponent'

export default {
    components: {
        Settings,
    },

    data() {
        return {
            user: {
                society: {
                    name: '',
                }
            },
            photo:''
        }
    },
    methods: {
        getProfilePhoto() {
            if (this.user.photo !== 'profile.png') {
                this.photo = '/img/profile/' + this.user.name + '/' + this.user.photo
            } else {
                this.photo = '/img/profile/profile.png'
            }
        },
    },

    created() {

        axios.get('/api/profile').then(result => {
            this.user = result.data
            this.getProfilePhoto()
        })
        Fire.$on('UpdatePhoto', () => {
            axios.get('/api/profile').then(result => {
                this.user = result.data
                this.getProfilePhoto()
            })
        })

    }
}
</script>
