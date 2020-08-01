$.ajax({
    dataType: 'json',
    url: `/tg/data/bots.json?nocache=${new Date()}`,
    success: (json) => {
        new Vue({
            el: '#vue-body',
            data: {
                bots: json, 
                rendered: true
            },
            mounted: function () {
                this.rendered = false
            }
        })
    }
})
