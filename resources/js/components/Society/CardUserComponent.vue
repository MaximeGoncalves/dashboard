<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row flex-column">
                    <p> {{user.fullname}}</p>
                    <p> {{user.email}}</p>
                    <p> {{user.role}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['user']
}
</script>
