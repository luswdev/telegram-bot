<template>
    <div class="alert fade" v-bind:class="[shown==classes ? 'show' : 'd-none', 'alert-' + classes]" role="alert">
        <h4 class="alert-heading">{{title}}</h4>
        <slot></slot>
    </div>
</template>

<script>
export default {
    props: ['classes', 'title', 'shown']
}
</script>
    