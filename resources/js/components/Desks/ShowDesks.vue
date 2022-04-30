<template>

    <div class="container-sm">
        <div class="input-group mb-3" >
            <input class="form-control " type="text"  v-model="name" @blur="saveName" :class="{ 'is-invalid': $v.name.$error }" >

        <!--Валидация-->
            <div class="invalid-feedback" v-if="!$v.name.required">
                Обязательное поле.
            </div>
            <div class="invalid-feedback" v-if="!$v.name.minLength" >
                Мин. кол-во символов: {{$v.name.$params.minLength.min}}.
            </div>
            <div class="invalid-feedback" v-if="!$v.name.maxLength" >
                Макс. кол-во символов: {{$v.name.$params.maxLength.max}}.
            </div>
        </div>

<!--         Алерт-->
        <div class="alert alert-danger" role="alert" v-if="errored">
            Ошибка загрузки данных!
        </div>
    </div>

</template>

<script>
import {required, minLength, maxLength} from 'vuelidate/lib/validators'
export default {
    props:[
        'deskId'
    ],
    data(){
        return{
            name:null,
            errored: false,
            loading: true
        }
    },
    methods:{
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
    },

    validations:{
        name:{
            required,
            maxLength: maxLength(255),
            minLength: minLength(5)
        }
    }
}
</script>

