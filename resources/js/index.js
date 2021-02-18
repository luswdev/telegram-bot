/**
 * index.js
 */
require('./bootstrap');

fetch( `/tg/data/bots.json?nocache=${new Date()}`, {
    method: 'POST', 
}).then( (res) => { 
    if(res.ok) {
        return res.json();
    }
    throw new Error('Network response was not ok.');
}).then( (json) => { 
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
}).catch( (err) => {
    console.log('Error:', err);
})