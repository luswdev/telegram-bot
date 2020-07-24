$(document).ready( function () {
    let BS4Alert = {
        props: ['classes', 'title', 'shown'],
        template: `
            <div class="alert fade show" v-bind:class="[shown==classes ? '' : 'd-none', 'alert-' + classes]" role="alert">
                <h4 class="alert-heading">{{title}}</h4>
                <slot></slot>
            </div>
        `
    }

    let mainVue = new Vue({
        el: '#vue-main',
        data: {
            payload: '',
            targetUrl: 'main.php ',
            isSending: false,
            result: '',
            errorStatus: '',
            alertShow: ''
        },
        components: {
            'bs4-alert': BS4Alert
        },
        methods: {
            clear_log: function () {
                this.payload = ''
                this.alertShow = ''
            },
            test_log: function () {
                /* clear alert and enable spinner */
                this.isSending = true
                this.alertShow = ''

                let url = '/tg/bots/' + this.targetUrl
                let data = this.payload

                try {
                    data = JSON.parse(data);
                } catch (e) {
                    this.isSending = false
                    this.alertShow = 'warning'
                    return
                }
                data = JSON.stringify(data);
        
                $.ajax({
                    type: 'POST',
                    contentType: 'application/json',
                    data: data,
                    url: url
                }).done( (res) => {
                    this.isSending = false
                    this.result = res;
                    this.alertShow = 'success'
                }).fail( (res) => {
                    this.isSending = false
                    this.errorStatus = res.status
                    this.alertShow = 'danger'
                })
            }
        },
        mounted: function () {
            if (window.location.href.split('?').length > 1 && window.location.href.split('?')[1].split('=').length > 1) {
                switch (window.location.href.split('?')[1].split('=')[0]) {
                    case 'data': 
                        this.payload = decodeURI(window.location.href.split('?')[1].split('=')[1])
                        break
                    case 'uid': 
                        let uid = window.location.href.split('?')[1].split('=')[1]
                        $.post("/tg/api/peak-json.php", {"id": uid, "type": 'update'}, "json")
                        .done( (response) => {
                            json = JSON.parse(response)        
                            this.payload = JSON.stringify(json["payload"], null, '\t')
                        })
                    default:
                        break
                }
            }
        }
    })
})