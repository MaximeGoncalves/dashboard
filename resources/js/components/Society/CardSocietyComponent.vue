<template>
    <div class="card">
        <div class="card-body d-flex align-items-center flex-column">
            <router-link :to="'society/' + society.id">
                <h2 class="h2">{{society.name}}</h2>
            </router-link>
            <p>{{society.address}}</p>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-muted text-sm"><i class="fas fa-users mr-2"></i>{{society.users.length}} Utilisateurs</span>
            <router-link :to="'society/' + society.id">
                <button class="btn btn-default">Consulter</button>
            </router-link>
        </div>
    </div>
</template>

<script>
export default {
    props:['society']
}

</script>
