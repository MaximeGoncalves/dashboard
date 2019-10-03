<template>
    <div class="px-2">
        <div class="main-board">
            <!--<div class="row">-->
                <!--<div class="col-2">-->
                    <!--<h3>Tableau Favoris</h3>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="row">-->
                <!--<div class="col-2">-->
                    <!--<a href="#">-->
                        <!--<div class="card shadow bg-gradient-primary text-white py-4">-->
                            <!--<div class="card-body d-flex justify-content-center align-items-center">-->
                                <!--azeazeazeazeaze-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</a>-->
                <!--</div>-->
            <!--</div>-->

            <div class="row mt-4">
                <div class="col-2">
                    <h3>Tableau</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-2" v-for="board in boards">
                    <router-link :to="'boards/' + board.id">
                        <div class="card shadow bg-gradient-primary text-white py-4">
                            <div class="card-body d-flex justify-content-center align-items-center">
                                {{ board.name }}
                            </div>
                        </div>
                    </router-link>
                </div>
                <div class="col-2">
                    <a href="#" data-toggle="modal" data-target="#addBoard">
                        <div class="card shadow bg-light text-white py-4">
                            <div class="card-body d-flex justify-content-center align-items-center px-2">
                                <span class="h4 mb-0"><b>Ajouter un nouveau tableau</b></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addBoard" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-top" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row justify-content-center align-item-center">
                            <div class="col-10">
                                <input type="text" class="form-control form-control-alternative" placeholder="Nom du tableau" v-model="board">
                            </div>
                            <div class="col-2 d-flex justify-content-center align-item-center">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" :disabled="board === ''" @click="addBoard">Créer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            board: '',
            boards: {},
        }
    },
    methods: {
        getBoard (){
            axios.get('/api/board').then(response => {
                console.log(response)
                this.boards = response.data
            })
        },
        addBoard (){
            axios.post('/api/board', {
                name: this.board
            }).then(response => {
                this.boards.push(response.data)
                $('#addBoard').modal('hide')
            })
        }
    },
    created (){
        this.getBoard();
    }
}
</script>

<style>

    .main-board .card:hover.bg-gradient-primary{
        background: linear-gradient(87deg, #575cb4 0, #664bb4 100%) !important;

    }
    .main-board .card:hover.bg-light{
        background: #e9ecef !important;
    }

</style>
