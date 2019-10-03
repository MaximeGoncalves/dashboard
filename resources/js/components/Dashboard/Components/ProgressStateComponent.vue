<template>
    <div class="card shadow">
        <div class="card-header border-0">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">{{ this.title }}</h3>
                </div>
                <div class="col text-right">
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <!-- Projects table -->
            <table class="table align-items-center table-flush">
                <thead class="thead-light">
                <tr>
                    <th scope="col" v-for="array in this.array">{{ array }}</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in items">
                    <th scope="row" v-if="title !== 'Tickets par utilisateur'">
                        {{ item.name }}
                    </th>
                    <th v-if="title == 'Tickets par utilisateur'">
                        {{ item.fullname }}
                    </th>
                    <td>
                        {{ item.count }}
                    </td>
                    <td>
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="mr-2">{{ (100 * item.count / total).toFixed(2) }} %</span>
                            <div>
                                <div class="progress">
                                    <div class="progress-bar bg-gradient-primary" role="progressbar"
                                         :aria-valuenow="(100 * item.count / total).toFixed(2)" aria-valuemin="0"
                                         aria-valuemax="100"
                                         :style="{width:(100 * item.count / total).toFixed(2) + '%'} "></div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {

    props: {
        title: {
            type: String,
            default: 'Mon titre'
        },
        items: {
            required: true,
        },
        array: {
            type: Array,
            required: true,
        },
        total : {
            required : true,
        }
    }
}
</script>
