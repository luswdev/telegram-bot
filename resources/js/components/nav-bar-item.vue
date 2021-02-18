<template>
    <li class="nav-item">
        <a class="nav-link" v-bind:class="[current==name ? 'active font-weight-bold' : ''] " v-bind:href="current==name ? '#' : url">
            {{name}}
        </a>
    </li>
</template>

<script>
export default {
    props: ['current', 'name', 'url']
}
</script>