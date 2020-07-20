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
  
    new Vue({
        el: '#vue-main',
        data: {
            years: years,
            months: months,
            days: days,
        },
        methods: {
            peakLog: function (event) {
                $('.alert').alert('close')
                
                $('#update pre code').empty();
                $('#exec pre code').empty();

                $('#update .btn-raw').attr({'href': "#"}).removeAttr('target')
                $('#exec .btn-raw').attr({'href': "#"}).removeAttr('target')

                $('#update .btn-download').attr({'href': "#"}).removeAttr('download')
                $('#exec .btn-download').attr({'href': "#"}).removeAttr('download')

                let url = event.target[0].value + '/' + ('0' + event.target[1].value).slice(-2) + '/' + ('0' + event.target[2].value).slice(-2) 
                let date = event.target[0].value + '-' + ('0' + event.target[1].value).slice(-2) + '-' + ('0' + event.target[2].value).slice(-2) 

                $.ajax({
                    type: "post",
                    url: url + '/updated.txt',
                    success: (res) => {
                        $('#update pre code').append(res)
                        $('#update .btn-raw').attr({'href': url + '/updated.txt', 'target': '_blank'})
                        $('#update .btn-download').attr({'href': url + '/updated.txt', 'download': date + '-updated.txt'})
                        hljs.highlightBlock(document.getElementById("update-code"));
                    },
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.log(jqXHR, textStatus, errorThrown)
                        let alert = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Bad Request.</strong> You selected log file (${url + '/updated.txt'}) is not exist.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>`
                      $(".main-inner").prepend(alert);
                      return
                    }
                });

                $.ajax({
                    type: "post",
                    url: url + '/exec.txt',
                    success: (res) => {
                        $('#exec pre code').append(res)
                        $('#exec .btn-raw').attr({'href': url + '/exec.txt', 'target': '_blank'})
                        $('#exec .btn-download').attr({'href': url + '/exec.txt', 'download': date + '-exec.txt'})
                        hljs.highlightBlock(document.getElementById("exec-code"));
                    },
                    error: (jqXHR, textStatus, errorThrown) => {
                        console.log(jqXHR, textStatus, errorThrown)
                        let alert = `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Bad Request.</strong> You selected log file (${url + '/exec.txt'})  is not exist.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>`
                      $(".main-inner").prepend(alert);
                      return
                    }
                });  
            },
            backToday: () => {
                $("#select-year").children()[now.getFullYear()-2020].selected = true
                $("#select-month").children()[now.getMonth()].selected = true
                $("#select-day").children()[now.getDate()-1].selected = true
            }
        },
        mounted: () => {
            $("#select-year").children()[now.getFullYear()-2020].selected = true
            $("#select-month").children()[now.getMonth()].selected = true
            $("#select-day").children()[now.getDate()-1].selected = true

            $("#submit").click()
        }
    })
})