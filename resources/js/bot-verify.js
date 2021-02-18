/**
 * bot-verify.js
 */

require('./bootstrap');

let form = new Vue({
    el: '#verify-vue',
    data: {
        code: ''
    },
    methods: {
        check: function () {
            if (!this.code)
                return false

            let url = `/tg/auth/totp/${this.code}`
            window.axios.auth.post(`/totp/${this.code}`)
            .then( (res) => {
                if (res.data.ok) {
                    window.location.reload()
                } else {
                    let alert = 
                    `<div class="alert alert-danger alert-dismissible fade show m-auto" role="alert">
                        <strong>Bad Request!</strong> You are typing wrong auth code.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>`
                    document.getElementById('verify-container').insertAdjacentHTML("afterBegin", alert)
                }
            })
        },
        onKeyPress: function(event) {
            if (event.keyCode == 13) {
                this.check();
            }
        }
    }
});