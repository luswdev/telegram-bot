$(document).ready( () => {
    $('.alert').alert()

    $('button#clear').on('click', ()=> {
        $('#post-data')[0].value = ''
        $('.alert-dismissible').alert('close')
    })

    $('button#submit').on('click', ()=> {
        $('#btn-spin').toggleClass('d-none')
        let url = 'https://tg.lusw.dev/' + $('#post-location')[0].value;
        let data = $('#post-data')[0].value
       
        try {
            data = JSON.parse(data);
        } catch (e) {
            $('#btn-spin').toggleClass('d-none')
            
            let alert = 
            `<div class="alert alert-warning alert-dismissible fade show" role="alert">
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
        }).done( () => {
            $('#btn-spin').toggleClass('d-none');

            let alert = 
            `<div class="alert alert-success alert-dismissible fade show" role="alert">
                POST to ${url} successed!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`

            $('.main-inner').prepend(alert)   
        }).fail(function() {
            $('#btn-spin').toggleClass('d-none')

            let alert = 
            `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                POST to ${url} failed, please watch log to get more information.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`

            $('.main-inner').prepend(alert) 
        })
    })
})