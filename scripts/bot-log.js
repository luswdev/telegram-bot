let years  = _.range(2020, new Date().getFullYear()+1);
let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
let days   = _.range(1,32)
  
let selectForm = new Vue({
    el: '#selecter',
    data: {
        selecter: {
            years:  years,
            months: months,
            days:   days
        },
        selecting: {
            year:  new Date().getFullYear(),
            month: new Date().getMonth()+1,
            day:   new Date().getDate()
        },
        isToday: true
    },
    methods: {
        peakLog: function () {
            this.checkIsToday()

            let date = this.selecting.year + '-' + ('0' + this.selecting.month).slice(-2) + '-' + ('0' + this.selecting.day).slice(-2) 
            $.ajax({
                type: 'POST',
                contentType: 'application/x-www-form-urlencoded',
                url: '/tg/api/get-log.php',
                data: {
                    'day': date
                },
                beforeSend: function () {
                    this.startTime = new Date().getTime();
                },
                success: function (response) {
                    json = JSON.parse(response)
                    logs.update = json['update']
                    logs.exec   = json['exec']
                    logs.sec    = parseInt(new Date().getTime()-this.startTime, 10)/1000
                    logs.day    = date
                }
            })
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
        'log-tab': httpVueLoader('/tg/components/log-tab.vue')
    },
    methods: {
        openJson: function (id, type) {
            $.ajax({
                type: 'POST',
                contentType: 'application/x-www-form-urlencoded',
                url: '/tg/api/peak-json.php',
                data: {
                    'id': id,
                    'type': type
                },
                success: (response) => {
                    json = JSON.parse(response)
    
                    modals.type = type
                    modals.result = ''
                    json["payload"].forEach( (elem) => {
                        modals.result += JSON.stringify(elem, null, '\t') + '\n'
                    })
                    modals.title = this.day + " " + json.time + " - " + type
                    modals.id = id
    
                    $('#json-modal').modal('show')
                    $('#render-block pre code').empty().text(modals.result)
                    hljs.highlightBlock($('#render-block pre code')[0])
                    modals.result = $('#render-block pre code')[0].innerHTML
                }
            })
        },
        download_log: () => {
            fetch(`/tg/api/download-log.php?day=${logs.day}`)
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
        selectForm.peakLog()
    }
})
