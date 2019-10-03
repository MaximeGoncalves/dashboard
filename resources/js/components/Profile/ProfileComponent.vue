<template>
        <div class="row">
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image ">
                                <a href="#">
                                    <img
                                        :src=" photo "
                                        class="rounded-circle">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-8 pt-md-4 pb-0 pb-md-4">
                    </div>
                    <div class="card-body pt-0 pt-md-4">
                        <div class="row">
                            <div class="col-12 p-5">
                            </div>
                        </div>
                        <div class="text-center">
                            <h3 v-model="user.fullname">{{ user.fullname }}</h3>
                            <div class="h5 font-weight-300">
                                <i class="ni location_pin mr-2"></i>
                                {{ user.society.name }}
                            </div>
                            <div class="h5 mt-4">
                                <i class="fas fa-user-tie text-primary mr-2"></i>Profession : {{ user.profession ? user.profession : 'N/A'}}
                            </div>
                            <div class="h5">
                                <i class="fas fa-phone text-primary mr-2"></i>Direct : {{user.phone ? user.phone : 'N/A' }}
                            </div>
                            <div class="h5">
                                <i class="fas fa-phone text-primary mr-2"></i>Société : {{user.society.phone}}
                            </div>
                            <hr class="my-4">
                            <a :href="'mailto:' + user.email" class="btn btn-sm btn-default">Message</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 order-xl-1">
                <settings :user="user"></settings>
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
