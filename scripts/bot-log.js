$(document).ready( () => {
    let logTab = {
        props: ['title', 'logs', 'day', 'sec'],
        template: `
            <div class="my-3 tab-pane" v-bind:class="[title=='update'?'fade show active':'']" :id="title+'-list'" role="tabpanel" :aria-labelledby="title+'-tab'">
                <div class="alert alert-info">
                    <span>
                        <i class="fas fa-check mr-1"></i>
                        Showing rows 0 - {{logs.length>0?logs.length-1:logs.length}} ({{logs.length}} total, Query took {{sec}} seconds.)
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Time</th>
                                <th v-if="title=='exec'" scope="col">API</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="r in logs">
                                <th scope="row">{{r.id}}</th>
                                <td>{{day}}</td>
                                <td>{{r.time}}</td>
                                <td v-if="title=='exec'">{{r.api}}</td>
                                <td class="text-center">
                                    <a href="#" v-on:click="$emit('get-log', r.id, title)">
                                        <i class="fas fa-info-circle text-info"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        `
    }

    let now = new Date()

    let years = _.range(2020, now.getFullYear()+1);
    let months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
    let days = _.range(1,32)
  
    let selectForm = new Vue({
        el: '#selecter',
        data: {
            years: years,
            months: months,
            days: days,
            isToday: true,
            working: true
        },
        methods: {
            peakLog: function (event) {
                this.working = false
                this.isToday = ((now.getFullYear() == event.target[0].value) && (now.getMonth()+1 == event.target[1].value) && (now.getDate() == event.target[2].value))
                let ajaxTime= new Date().getTime();
                let date = event.target[0].value + '-' + ('0' + event.target[1].value).slice(-2) + '-' + ('0' + event.target[2].value).slice(-2) 

                $.post("/tg/api/get-log.php", {"day": date}, "json")
                .done( (response ,textStatus, jqXHR) => {
                    json = JSON.parse(response)
                    update_log.update = json['update']
                    update_log.exec = json['exec']
                    update_log.sec = parseInt(new Date().getTime()-ajaxTime, 10)/1000
                    update_log.day = date

                    this.working = true
                })
            },
            getDay: (e) => {
                let selectMonth = parseInt($("#select-month")[0].value, 10);
                let m31 = [1, 3, 5, 7, 8, 10, 12]
                let m30 = [4, 6, 9, 11]

                if (m31.indexOf(selectMonth) >= 0) {
                    selectForm.days = days.slice(0, 31)
                } else if (m30.indexOf(selectMonth) >= 0) {
                    selectForm.days = days.slice(0, 30)
                } else {
                    let selectYear = parseInt($("#select-year")[0].value, 10);
                    selectForm.days = days.slice(0, selectYear%4 ? 28 : 29)
                }
            },
            backToday: () => {
                $("#select-year").children()[now.getFullYear()-2020].selected = true
                $("#select-month").children()[now.getMonth()].selected = true
                $("#select-day").children()[now.getDate()-1].selected = true

                $("#submit").click()
            }
        },
        mounted: function()  {
           this.backToday()
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

    let update_log = new Vue({
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
                console.log(id, type);

                $.post("/tg/api/peak-json.php", {"id": id, "type": type}, "json")
                .done( (response) => {
                    json = JSON.parse(response)

                    modals.type = type
                    modals.result = JSON.stringify(json["payload"], null, '\t')
                    if (JSON.stringify(json["result"]) !== '[]') {
                        modals.result += '\n' +  JSON.stringify(json["result"], null, '\t')
                    }
                    modals.title = update_log.day + " " + json.time + " - " + type
                    modals.id = id

                    $('#json-modal').modal('show')
                })
            },
            download_log: () => {
                fetch(`/tg/api/download-log.php?day=${update_log.day}`)
                .then(resp => resp.blob())
                .then(blob => {
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = `${update_log.day}.json`;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                })
                .catch(() => console.log('File Download Failed!'));
            }
        },
    })
})