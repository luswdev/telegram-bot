$(document).ready( () => {
    $('.collapsible').collapsible()
    let elem = document.getElementById('collapsible');
    let instance = M.Collapsible.getInstance(elem);
    instance.open(0)

    $('.modal').modal({
        onOpenStart: (t, e) => {
            let logDate = $(e).attr('date')
            let logFile = '/log/' + logDate + '/' + $(e).attr('value')
            let downloadName = $(e).attr('date') + "-" + $(e).attr('value')

            $('#log-title').text(logDate + " " + $(e).text() + " Log")
            $('#download-log').attr({"href": logFile, "download": downloadName})
            $('#raw-log').attr({"href": logFile})

            $.ajax(`${logFile}?nocache=${new Date()}`)
            .done((res) => {
                $('#log-body').empty().append(res)
                document.querySelectorAll('.modal-content pre code').forEach((block) => {
                    hljs.highlightBlock(block);
                });
            })
        },
        onOpenEnd: () => {
            $('#log-body').height(($('.modal-content').innerHeight() - $('#log-title').outerHeight(true)) * 0.85 )
        }
    })

    $('.collapsible').children().each( function () {
        $(this).find('.badge').text(
            $(this).find('.collapsible-body').children().length
        )
    })
})