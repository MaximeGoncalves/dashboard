<template>
    <li class="nav-item dropdown">
        <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" @click="readNotifications">
            <i class="ni ni-bell-55"></i>
            <span class="notification" v-if="notifications && notifications.length > 0">{{ notifications.length }}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
            <!-- Dropdown header -->
            <div class="px-3 py-3">
                <h6 class="text-sm text-muted m-0">Vous avez<strong class="text-primary"> {{ notifications.length }}</strong> notifications.</h6>
            </div>
            <!-- List group -->
            <div class="list-group list-group-flush">
                <a href="#!" class="list-group-item list-group-item-action" v-for="notification in notifications">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <!-- Avatar -->
                            <img alt="Image placeholder" src="https://argon-dashboard-pro-laravel.creative-tim.com/argon/img/theme/team-1.jpg" class="avatar rounded-circle">
                        </div>
                        <div class="col ml--2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="mb-0 text-sm">{{ notification.data.user }}</h4>
                                </div>
                                <div class="text-right text-muted">
                                    <small>{{ notification.created_at | formatDateInHour}} </small>
                                </div>
                            </div>
                            <p class="text-sm mb-0">{{ notification.data.name }}</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- View all -->
            <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">View all</a>
        </div>
    </li>
</template>

<script>
export default {

    data (){
        return {
            notifications : []
        }
    },
    methods : {
        getNotifications (){
            axios.get('/api/notifications/').then(response => {
                // console.log(response)
                this.notifications = response.data
            })
        },
        readNotifications(){
            axios.get('/api/notifications/read')
        }
    },
    mounted() {
        this.getNotifications()
    }
}
</script>
<style>
    .notification{
        position: absolute;
        top: 0px;
        right: 0px;
        font-size: 9px;
        background: #f44336;
        color: #fff;
        z-index: 1;
        min-width: 15px;
        padding: 0 5px;
        height: 15px;
        border-radius: 10px;
        text-align: center;
        line-height: 15px;
        vertical-align: middle;
        display: block;
    }
</style>
