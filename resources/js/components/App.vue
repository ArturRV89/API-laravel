<template>
<div class="page">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <router-link class="nav-link active" :to="{name:'home'}">Главная</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name:'about'}">About</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name:'desks'}">Доски</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" :to="{name:'example'}">Example</router-link>
                </li>
            </ul>
        </div>
    </nav>
    <div class="content mt-3" >
        <router-view></router-view>
    </div>
</div>

</template>



<script>

export default{
}
</script>
