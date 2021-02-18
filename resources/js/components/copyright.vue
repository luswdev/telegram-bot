<template>
    <div class="footer-inner container text-black-50 py-3">    
        <hr>
        &copy; {{year}} LuSkywalker
    </div>
</template>

<script>
export default {
    data () {
        return {
            year: new Date().getFullYear()
        }
    }
}
</script>