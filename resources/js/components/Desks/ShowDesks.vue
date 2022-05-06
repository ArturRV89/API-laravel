<template>

    <div class="container">
        <h2>{{ name }}</h2>

        <form class="form-control">
            <p>Изменить название доски</p>
            <input class="form-control " type="text"  v-model="name" @blur="saveName" :class="{ 'is-invalid': $v.name.$error }" >
        <!--Валидация для досок-->
            <div class="invalid-feedback" v-if="!$v.name.minLength" >
                Мин. кол-во символов: {{$v.name.$params.minLength.min}}.
            </div>
            <div class="invalid-feedback" v-if="!$v.name.maxLength" >
                Макс. кол-во символов: {{$v.name.$params.maxLength.max}}.
            </div>
        </form>

        <!--        Спинер-->
        <div class="spinner-border mt-3" role="status" v-if="loading">
            <span class="visually-hidden">Loading...</span>
        </div>

        <!--    Создание списка    -->
        <form class="form-control mt-3" @submit.prevent="createDeskList">
            <div class="mb-3" :class="{ 'is-invalid': $v.desk_lists_name.$error }">
                <input class="form-control" type="text" placeholder="Введите название нового списка"  v-model="desk_lists_name" >
            </div>
            <button class="btn btn-success">Создать список</button>
            <!--Валидация для списков-->
            <div class="invalid-feedback" v-if="!$v.desk_lists_name.minLength" >
                Мин. кол-во символов: {{$v.desk_lists_name.$params.minLength.min}}.
            </div>
            <div class="invalid-feedback" v-if="!$v.desk_lists_name.maxLength" >
                Макс. кол-во символов: {{$v.desk_lists_name.$params.maxLength.max}}.
            </div>
        </form>


        <!--Алерт-->
        <div class="alert alert-danger" role="alert" v-if="errored">
            Ошибка загрузки данных!
        </div>

        <!--    Карточки списков    -->
        <div class="row">
            <div class="col-lg-4" v-for="desk_list in desk_lists" :key="desk_list.id" >
                <div class="card mt-3">
                    <div class="card-body">
                        <form @submit.prevent="renameListId(desk_list.id, desk_list.name)" v-if="desk_list_input_id == desk_list.id">
                            <input v-model="desk_list.name" type="text" class="form-control" placeholder="Введите название списка" >
                        </form>
                        <h4 v-else class="card-title d-flex justify-content-between" @click="desk_list_input_id = desk_list.id">{{desk_list.name}}<i class="fa-solid fa-pencil"></i></h4>
                        <button type="button" class="btn btn-danger d-flex justify-content-center mt-2" @click="deleteDeskList(desk_list.id)">Удалить список</button>

                        <!-- Карточки  (Card)-->
                        <div class="card mt-2" v-for="card in desk_list.cards" :key="card.id">
                            <div class="text-dark bg-light ">
                                <div class="card-body">
                                    <p class="card-text">{{ card.name }}</p>
                                    <button type="button" class="btn btn-warning " @click="deleteCard(card.id)">Удалить карточку</button>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Открыть</button>
                                </div>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary">Редактировать</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form @submit.prevent="createCard(desk_list.id)">
                            <input v-model="card_names[desk_list.id]" class="form-control mt-2" type="text" placeholder="Создать новую карточку" :class="{ 'is-invalid': $v.card_names.$each[desk_list.id].$error }" >
                            <!--Валидация для карточек (Card)-->
                            <div class="invalid-feedback" v-if="!$v.card_names.$each[desk_list.id].minLength" >
                                Мин. кол-во символов: {{$v.card_names.$each[desk_list.id].$params.minLength.min}}.
                            </div>
                            <div class="invalid-feedback" v-if="!$v.card_names.$each[desk_list.id].maxLength" >
                                Макс. кол-во символов: {{$v.card_names.$each[desk_list.id].$params.maxLength.max}}.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import {minLength, maxLength} from 'vuelidate/lib/validators'
export default {
    props:[
        'deskId'
    ],
    data(){
        return{
            name: null,
            desk_lists_name: null,
            errored : false,
            loading : true,
            desk_lists : null,
            desk_list_input_id : null,
            card_names:[]
        }
    },
    methods:{
        deleteCard(id){
            axios
                .post('/api/V1/cards/' + id, {
                    _method : 'DELETE'
                })
                .then(response => {
                    this.getDeskLists()
                })
                .catch(error => {
                    console.log(error.response.data.errors)
                    this.errored = true
                })
        },
        createCard(desk_list_id){
            this.$v.card_names.$each[desk_list_id].$touch()
            if(this.$v.card_names.$each[desk_list_id].$anyError){
                return;
            }
            axios
                .post('/api/V1/cards',{
                    name : this.card_names[desk_list_id],
                    desk_list_id : desk_list_id
                })
                .then(response =>{
                    this.getDeskLists()
                })
                .catch(error => {
                    console.log(error.response.data.errors)
                    this.errored = true
                })
        },

        getDeskLists(){
            axios
                .get('/api/V1/desk-lists',{
                    params:{
                    desk_id : this.deskId
                    }
                })
                .then(response => {
                    this.desk_lists = response.data.data
                    this.desk_lists.forEach(el=>{
                        this.card_names[el.id] = ''
                    })
                })
                .catch(error => {
                    console.log(error.response.data.errors)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },

        saveName(){
            // проверка валидации
            this.$v.$touch()
                if(this.$v.$anyError){
                    return;
                }
            axios
                .post('/api/V1/desks/' + this.deskId, {
                    _method: 'PUT',
                    name: this.name,
                })
                .then(response => {
                })
                .catch(error=> {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        createDeskList(){
            this.$v.desk_lists_name.$touch()
            if(this.$v.desk_lists_name.$anyError){
                return;
            }

            axios
                .post('/api/V1/desk-lists', {
                    name: this.desk_lists_name,
                    desk_id: this.deskId,
                })
                .then(response => {
                    this.desk_list_name = ''
                    this.desk_lists = []
                    this.getDeskLists()
                })
                .catch(error=> {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },
        deleteDeskList(id){
            axios
                .post('/api/V1/desk-lists/' + id,{
                    _method : 'DELETE',
                })
                .then(response =>{
                    this.desk_lists = []
                    this.getDeskLists()
                })
                .catch(error => {
                    console.log(error.response.data.errors)
                    if (error)
                    this.errored = true
                })
                .finally(()=>{
                    this.loading = false
                })
        },
        renameListId(id, name){
            axios
                .post('/api/V1/desk-lists/' + id,{
                    _method : 'PUT',
                    name : name,
                })
                .then(response=>{
                    this.desk_list_input_id = null
                })
                .catch(error=>{
                    console.log(error.response.data.errors)
                    this.errored = true
                })
                .finally(()=>{
                    this.loading = false
                })
        }
    },

    mounted() {
        axios
            .get('/api/V1/desks/' + this.deskId)
            .then(response => {
                this.name = response.data.data.name
            })
            .catch(error=> {
                console.log(error)
                this.errored = true
            })
            .finally(() => {
                this.loading = false
            })
        this.getDeskLists()
    },

    validations:{
        name:{
            maxLength: maxLength(255),
            minLength: minLength(5)
        },
        desk_lists_name:{
            maxLength: maxLength(255),
            minLength: minLength(5)
        },
        card_names: {
            $each :{
                maxLength: maxLength(255),
                minLength: minLength(5)
            }
        }
    }
}
</script>

