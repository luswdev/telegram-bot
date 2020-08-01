let mainVue = new Vue({
    el: '#vue-main',
    data: {
        payload: '',
        targetUrl: 'main.php',
        isSending: false,
        result: '',
        alertShow: ''
    },
    components: {
        'bs4-alert': httpVueLoader('/tg/components/alert.vue')
    },
    methods: {
        clear_log: function () {
            this.payload    = ''
            this.alertShow  = ''
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
                url: url,
                data: data,
                success: (res) => {
                    this.isSending  = false
                    this.result     = res;
                    this.alertShow  = 'success'
                },
                error: (res) => {
                    this.isSending  = false
                    this.result     = res
                    this.alertShow  = 'danger'
                }
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
                    $.ajax({
                        type: 'POST',
                        contentType: 'application/x-www-form-urlencoded',
                        url: '/tg/api/peak-json.php', 
                        data: {
                            'id': uid,
                            'type': 'update'
                        },
                        success: (response) => {
                            json = JSON.parse(response)        
                            this.payload = JSON.stringify(json["payload"], null, '\t')
                        }
                    })
                default:
                    break
            }
        }
    }
})
