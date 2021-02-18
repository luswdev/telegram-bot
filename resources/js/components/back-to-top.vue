<template>
    <div class="text-right fixed-bottom m-3" v-bind:style="styles" id="back-to-top-btn-box">
        <button class="btn btn-styled rounded-circle" v-on:click="backToTop" id="b2t">
            <i class="fas fa-chevron-up"></i>
        </button>
    </div>
</template>

<script>
export default {
    data () {
        return {
           isTop: true,
            styles: {
                bottom: '-120px'
            }
        }
    },
    methods: {
        backToTop: function () {
            this.scrollToTop(100)
            document.getElementById('b2t').blur()
        },
        scrollToTop: (duration) => {
            $('body, html').stop(true, true).animate({
                scrollTop: 0
            }, duration, 'linear');
        }
    },
    mounted: function () {
        window.addEventListener('scroll', () => {
            this.isTop = !(document.documentElement.scrollTop > 0);
            this.styles.bottom = this.isTop ? '-120px' : '0' 
        })
    }
}
</script>
    