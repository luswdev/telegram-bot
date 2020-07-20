$(document).ready( () => {
    $('.alert').alert()

    $('button#clear').on('click', ()=> {
        $('#post-data')[0].value = ''
        $('.alert-dismissible').alert('close')
    })

    $('button#submit').on('click', ()=> {
        $('.alert-dismissible').alert('close')

        $('#btn-spin').toggleClass('d-none')
        let url = 'https://tg.lusw.dev/bots/' + $('#post-location')[0].value;
        let data = $('#post-data')[0].value
       
        try {
            data = JSON.parse(data);
        } catch (e) {
            $('#btn-spin').toggleClass('d-none')
            
            let alert = 
            `<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Warning!</h4>
                Please write down POST data in JSON format!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`

            $('.main-inner').prepend(alert)

            return
        }
        data = JSON.stringify(data);

        $.ajax({
            type: 'POST',
            contentType: 'application/json',
            data: data,
            url: url
        }).done( (res) => {
            $('#btn-spin').toggleClass('d-none');

            let alert = 
            `<div class="alert alert-success alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">Success!</h4>
                <p>POST to ${url} successed!</p>
                <details>
                    <summary>Response</summary>
                    <p><pre>${res}</pre></p>
                </details>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`

            $('.main-inner').prepend(alert)   
        }).fail(function(res) {
            $('#btn-spin').toggleClass('d-none')

            let alert = 
            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">${res.status}!</h4>
                <p>POST to ${url} failed, please watch log to get more information.</p>
                <details>
                    <summary>Error report</summary>
                    <p>${res.responseText}</p>
                </details>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`

            $('.main-inner').prepend(alert) 
        })
    })
})