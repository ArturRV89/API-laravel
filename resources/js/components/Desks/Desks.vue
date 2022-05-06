<template>

    <div class="container">
        <h1>Desks</h1>

        <form class="form-control " @submit.prevent="createDesk">
            <div class="mb-3" :class="{ 'is-invalid': $v.name.$error }">
                <input type="text" placeholder="Введите название новой доски" class="form-control" v-model="name" >
            </div>
            <button class="btn btn-success">Создать</button>

            <!--Валидация-->
            <div class="invalid-feedback" v-if="!$v.name.minLength" >
                Мин. кол-во символов: {{$v.name.$params.minLength.min}}.
            </div>
            <div class="invalid-feedback" v-if="!$v.name.maxLength" >
                Макс. кол-во символов: {{$v.name.$params.maxLength.max}}.
            </div>
        </form>

        <!--        Алерт-->
        <div class="alert alert-danger mt-3" role="alert" v-if="errored">
            Ошибка загрузки данных! <br>
            {{ errors[0] }}
        </div>

        <div class="row">
            <div class="col-lg-4 align-content-center " v-for="desk in desks">
                <div class="card mt-3">
                    <router-link class="card-body  nav-link" :to="{name:'showDesks', params:{deskId: desk.id}}">
                        <h4 class="card-title">{{desk.name}}</h4>
                    </router-link>
                    <div class="card-body">
                        <button type="button" class="btn btn-danger w-25 " @click="deleteDesk(desk.id)">Удалить</button>
                    </div>
                </div>
            </div>
        </div>



<!--        Спинер-->
        <div class="spinner-border mt-3" role="status" v-if="loading">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</template>



<script>
import {minLength, maxLength} from 'vuelidate/lib/validators'

export default {
    data(){
        return{
            desks:[],
            errored: false,
            loading: true,
            name: null,
            errors: [],
        }
    },

    mounted() {
        this.getAllDesks()
    },

    methods:{
        getAllDesks(){
            axios
                .get('/api/V1/desks')
                .then(response => {
                    this.desks = response.data.data
                })
                .catch(error=> {
                    console.log(error)
                    this.errored = true
                })
                .finally(() => {
                    this.loading = false
                })
        },

        ///// Создать доску////
        createDesk(){
            // проверка валидации
            this.$v.$touch()
            if(this.$v.$anyError){
                return;
            }
            axios
                .post('/api/V1/desks',{
                    _method: 'POST',
                    name: this.name
                })
                .then(response=>{
                    this.name = ''
                    this.desks = []
                    this.getAllDesks()
                })
                .catch(error=>{
                    console.log(error)
                    if (error.response.data.errors.name){
                        this.errors = []
                        this.errors.push(error.response.data.errors.name[0])
                    }
                    this.errored = true
                })
                .finally(()=>{
                    this.loading = false
                })
        },

        ///// Удалить доску////
        deleteDesk(id){
            if (confirm('Вы действительно хотите удалить доску?')){
                axios
                    .post('/api/V1/desks/' + id,{
                        _method: 'DELETE'
                    })
                    .then(response => {
                        this.desks = []
                        this.getAllDesks()
                    })
                    .catch(error=> {
                        console.log(error)
                        if (error)
                        this.errored = true
                    })
                    .finally(()=>{
                        this.loading = false
                    })
            }
        },
    },
    validations:{
        name: {
            maxLength: maxLength(255),
            minLength: minLength(5),
        }
    }
}
</script>

