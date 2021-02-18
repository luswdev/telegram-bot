/**
 * bot-test.js
 */

require('./bootstrap');

import BS4Alert from './components/alert'

let mainVue = new Vue({
    el: '#vue-main',
    data: {
        payload: '',
        targetUrl: 'main',
        isSending: false,
        result: '',
        alertShow: ''
    }, 
    components: {
        'bs4-alert': BS4Alert
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

            /* setup API url and request payload */
            let url = '/tg/api/bot/' + this.targetUrl
            let data = this.payload

            /* trying to prase payload into JSON format */
            try {
                data = JSON.parse(data);
            } catch (e) {
                this.isSending = false
                this.alertShow = 'warning'
                return
            }
        
            /* if format corrently, fetch API */
            window.axios.instance.post(`bot/${this.targetUrl}`, data, {headers: {'Content-Type': 'application/json'}})
            .catch( (err) => {
                console.log(err)
                this.isSending  = false
                this.result     = err.status
                this.alertShow  = 'danger'
            })
            .then( (res) => { 
                this.isSending  = false
                this.result     = res.data.result;
                this.alertShow  = 'success'
            })
        },
        getURL: () => {
            let url = window.location.href
            let urlArr = url.split('?')
            let getDict = {}
    
            if (urlArr.length <= 1) {
                return getDict
            }

            urlArr[1].split('&').forEach( (element) => {
                let values = element.split('=')
                if (values.length > 1) {
                    getDict[values[0]] = values[1]
                }
            })
    
            return getDict
        }
    },
    mounted: function () {
        let gets = this.getURL()
        if (Object.keys(gets).length > 0 && gets.uid) {
            let uid = gets.uid
            window.axios.instance.get(`log/update/${uid}`)
            .then( (res) => {
                this.payload = JSON.stringify(res.data.result[0], null, '\t')
            })
        }
    }
})
