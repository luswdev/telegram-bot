$(document).ready( () => {
    $('.modal').modal({
        onOpenStart: (t, e) => {
            let logDate = $(e).attr('date')
            let logFile = '/log/' + logDate + '/' + $(e).attr('value')
            let downloadName = $(e).attr('date') + "-" + $(e).attr('value')
            $('.modal-content h4').text(logDate + " " + $(e).text() + " Log")
            $('.download-log').attr({"href": logFile, "download": downloadName})
            $('.raw-log').attr({"href": logFile})
            $.ajax(`${logFile}?nocache=${new Date()}`)
            .done((res) => {
                $('.modal-content pre code').empty().append(res)
                document.querySelectorAll('.modal-content pre code').forEach((block) => {
                    hljs.highlightBlock(block);
                });
            })
        },
        onOpenEnd: () => {
            $('pre code').height(($('.modal-content').innerHeight() - $('.modal-content h4').outerHeight(true)) * 0.85 )
        }
    })

})