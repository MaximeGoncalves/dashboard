<template>
    <div class="card card-stats mb-4 mb-xl-0">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <h5 class="card-title text-uppercase text-muted mb-0">{{ title }}</h5>
                    <span class="h2 font-weight-bold mb-0" v-if="percent === true">{{ data }} %</span>
                    <span class="h2 font-weight-bold mb-0" v-else>{{ data }}</span>
                </div>
                <div class="col-auto">
                    <div class="icon icon-shape text-white rounded-circle shadow"
                         :class="bgColor">
                        <i :class="iconClass"></i>
                    </div>
                </div>
            </div>
            <p class="mt-3 mb-0 text-muted text-sm">
                <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
                <span class="text-nowrap">Since last month</span>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true,
        },
        data: {
            required: true,
        },
        iconClass: {
            type: String,
            required: false,
        },
        bgColor: {
            type: String,
        },
        percent: {
            type: Boolean,
            default: false,
        }
    }
}
</script>
