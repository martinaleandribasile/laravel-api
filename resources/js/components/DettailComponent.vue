<template>
  <div >
    <h1 class="mb-4">Post</h1>
       <div>
            <h2 class="text-info">Title:</h2>
            <h3>{{post.title}}</h3>
       </div>
       <div>
            <h2 class="text-info">Content:</h2>
            <h3>{{post.content}}</h3>
       </div>
       <div>
            <h2 class="text-info">Categoria:</h2>
            <div v-for="category in categories" :key="category.id">
                <h3 v-if="category.id == post.category_id">{{category.name}}</h3>
            </div>
            <h4 class="text-black-50" v-if="post.category_id == null">Non ci sono categorie associate</h4>
       </div>
  </div>
</template>

<script>
export default {
name: 'DettailPostComponent',
props:{
    post:Object,
    categories:Array
}
}
</script>

<style>

</style>
