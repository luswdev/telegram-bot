<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/http-vue-loader"></script>

<template>
    <div class="alert fade show" v-bind:class="[shown==classes ? '' : 'd-none', 'alert-' + classes]" role="alert">
        <h4 class="alert-heading">{{title}}</h4>
        <slot></slot>
    </div>
</template>

<script>
module.exports = {
    props: ['classes', 'title', 'shown']
}
</script>
    