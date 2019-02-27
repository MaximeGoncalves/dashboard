<template>
    <div>
        <div class="row">
            <div class="col-12">
                <div class="card ">
                    <div class="col-12 col-md-6">
                        <div v-for="category in categories">
                            {{ category.name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {

    data (){
        return {
           categories: {},
           Folder: {}
        }
    },

    created (){
        axios.get('/api/knowledges').then(response => {
            console.log(response)
            this.categories = response.data.category;
        })
    }
}
</script>
