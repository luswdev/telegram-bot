let headerVue = new Vue({ 
    el: '#vue-header',
    components: {
        'nav-bar-item': httpVueLoader('/components/nav-bar-item.vue'),
        'nav-bar-link': httpVueLoader('/components/nav-bar-link.vue')
    }
})

let footerVue = new Vue({
    el: '#vue-footer',
    components: {
        'copyright': httpVueLoader('/components/copyright.vue'),
        'b2t': httpVueLoader('/components/b2t.vue')
    },
})