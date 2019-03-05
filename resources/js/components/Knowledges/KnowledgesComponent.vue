<template>
    <div>
        <div class="row mb-2">
            <div class="col-12">
                <router-link to="/addKnowledge" class="btn btn-sm btn-primary">Ajouter</router-link>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card shadow p-5">
                    <div class="row">
                        <div class="col-12 col-md-4" v-for="category in categories">
                            <h2>{{ category.name }}</h2>
                            <div v-for="knowledge in category.knowledges">
                                <h4>
                                    <router-link :to="'knowledges/'+ knowledge.id">{{ knowledge.title }}</router-link>
                                </h4>
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

    data() {
        return {
            knowledges: {},
            categories: {},
            Folder: {}
        }
    },

    created() {
        axios.get('/api/knowledges').then(response => {
            console.log(response)
            this.knowledges = response.data.knowledges;
            this.categories = response.data.categories;
        })
    }
}
</script>
