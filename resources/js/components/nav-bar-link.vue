<template>
    <li class="nav-item">
        <a class="nav-link" v-bind:href="url" target="_blank">
            <i v-bind:class="icon"></i>
        </a>
    </li>
</template>

<script>
export default {
    props: ['icon', 'url']
}
</script>
    