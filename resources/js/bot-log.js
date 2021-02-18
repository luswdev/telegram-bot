/**
 * bot-log.js
 */

require('./bootstrap');

import logTab from './components/log-tab'
import hljs from 'highlight.js'

/* extern fetch function with timer */
window.fetch = (function(fetch) {
    return function(fn, t) {
        const begin = Date.now();
        return fetch.apply(this, arguments).then(function(response) {
            response.timing = Date.now() - begin;
            return response;
        });
    };
})(window.fetch);

let years  = _.range(2020, new Date().getFullYear()+1);
let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
let days   = _.range(1,32)

let apiDate = {
    year:  new Date().getFullYear(),
    month: new Date().getMonth()+1,
    day:   new Date().getDate()
}

let currentLocation = window.location.href.split('/')
if (currentLocation.length === 8) {
    apiDate = {
        year:  currentLocation[5],
        month: currentLocation[6],
        day:   currentLocation[7]
    }
}
  
let selectForm = new Vue({
    el: '#selecter',
    data: {
        selecter: {
            years:  years,
            months: months,
            days:   days
        },
        selecting: apiDate,
        isToday: true
    },
    methods: {
        peakLog: function () {
            this.checkIsToday()

            window.location.href = `/tg/log/${this.selecting.year}/${this.selecting.month}/${this.selecting.day}` 
        },
        getDay: function () {
            let selectMonth = parseInt(this.selecting.month, 10);
            let m31 = [1, 3, 5, 7, 8, 10, 12]
            let m30 = [4, 6, 9, 11]

            if (m31.indexOf(selectMonth) >= 0) {
                this.selecter.days = days.slice(0, 31)
            } else if (m30.indexOf(selectMonth) >= 0) {
                this.selecter.days = days.slice(0, 30)
            } else {
                let selectYear = parseInt(this.selecting.year, 10);
                this.selecter.days = days.slice(0, selectYear%4 ? 28 : 29)
            }

            if (this.selecting.day > this.selecter.days.length) {
                this.selecting.day = this.selecter.days.length
            }
        },
        backToday: function () {
            this.selecting = {year: new Date().getFullYear(), month: new Date().getMonth()+1, day: new Date().getDate()}
            this.peakLog()
            this.getDay()
        },
        checkIsToday: function () {
            this.isToday = ((new Date().getFullYear() == this.selecting.year) &&
                            (new Date().getMonth()+1 == this.selecting.month) &&
                            (new Date().getDate() == this.selecting.day))
        },
        getRow: function () {
            window.axios.instance.get(`log/${this.selecting.year}/${this.selecting.month}/${this.selecting.day}`)
            .then( (res) => {
                logs.update = res.data.result.update
                logs.exec = res.data.result.exec
            })
        }
    },
    mounted: function () {
        this.checkIsToday()

        if (currentLocation.length !== 8) {
            window.location.href = `/tg/log/${this.selecting.year}/${this.selecting.month}/${this.selecting.day}` 
        }
    }
})

let modals = new Vue({
    el: '#json-modal',
    data: {
        result: '',
        title: '',
        type: '',
        id: ''
    }
})

let logs = new Vue({
    el: '#result-content',
    data: {
        update: [],
        exec: [],
        sec: 0,
        day: ''
    },
    components: {
        'log-tab': logTab
    },
    methods: {
        openJson: function (id, type) {
            window.axios.instance.get(`log/${type}/${id}`)
            .then( (res) => {
                modals.type = type
                modals.result = ''
                res.data.result.forEach( (elem) => {
                    modals.result += JSON.stringify(elem, null, '\t') + '\n'
                })
                modals.title = this.day + " " + res.data.time + " - " + type
                modals.id = id

                $('#render-block pre code').empty().text(modals.result)
                hljs.highlightBlock($('#render-block pre code')[0])
                modals.result = $('#render-block pre code')[0].innerHTML
            })
        },
        download_log: () => {
            fetch(`/tg/api/log/${selectForm.selecting.year}/${selectForm.selecting.month}/${selectForm.selecting.day}`)
            .then(resp => resp.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = url;
                a.download = `LuSkywalker-${logs.day}.json`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
            })
            .catch(() => console.log('File Download Failed!'));
        }
    },
    mounted: function () {
        selectForm.getRow()

        this.day = `${selectForm.selecting.year}-${selectForm.selecting.month}-${selectForm.selecting.day}`

        /* update every 10 mins if picker is today */
        let updateLoop = setInterval(() => {
            selectForm.checkIsToday();
            if (selectForm.isToday) {
                selectForm.peakLog()
            }
        }, 600000);
    }
})
