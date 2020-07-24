$(document).ready( () => {
    $.getJSON(`/tg/data/bots.json?nocache=${new Date()}`)
    .done( (json) => {
        new Vue({
            el: '#vue-body',
            data: {
                bots: json
            }
        })
    })
});