$(document).ready( () => {

    let now = new Date()

    let years = [];
    for (let i=2020; i<=now.getFullYear(); ++i) {
        years.push(i)
    }

    let months = [];
    for (let i=1; i<=12; ++i) {
        months.push(i)
    }

    let days = [];
    for (let i=1; i<=31; ++i) {
        days.push(i)
    }
  
    let selectForm = new Vue({
        el: '#selecter',
        data: {
            years: years,
            months: months,
            days: days,
            isToday: true
        },
        methods: {
            peakLog: function (event) {
                this.isToday = ((now.getFullYear() == event.target[0].value) && (now.getMonth()+1 == event.target[1].value) && (now.getDate() == event.target[2].value))
                let ajaxTime= new Date().getTime();
                let date = event.target[0].value + '-' + ('0' + event.target[1].value).slice(-2) + '-' + ('0' + event.target[2].value).slice(-2) 

                $.post("/api/get-log.php", {"day": date}, "json")
                .done( (response ,textStatus, jqXHR) => {
                    json = JSON.parse(response)
                    update_log.update = json['update']
                    update_log.exec = json['exec']
                    update_log.sec = parseInt(new Date().getTime()-ajaxTime, 10)/1000
                    update_log.day = date;
                })
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
            title: ''
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
        methods: {
            open_json: (id, type) => {
                console.log(id, type)
                $.post("/api/peak-json.php", {"id": id, "type": type}, "json")
                .done( (response) => {
                    json = JSON.parse(response)

                    modals.result = JSON.stringify(json["payload"], null, '\t')
                    if (JSON.stringify(json["result"]) !== '[]') {
                        modals.result += '\n' +  JSON.stringify(json["result"], null, '\t')
                    }
                    modals.title = update_log.day + " " + json.time + " - " + type

                    $('#json-modal').modal('show')
                })
            },
            download_log: () => {
                fetch(`/api/download-log.php?day=${update_log.day}`)
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