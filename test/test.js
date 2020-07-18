$(document).ready( () => {
    $('.alert').alert()

    $('button#clear').on('click', ()=> {
        $('#post-data')[0].value = ''
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
            `<div class="alert alert-warning alert-dismissible" role="alert" data-aos="fade-up">
                Please write down POST data in JSON format!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`

            $('.main-inner').append(alert)

            AOS.init({
                duration: 700,
                eeasing: 'ease-in-out-sine',
            })    
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
            `<div class="alert alert-success alert-dismissible" role="alert" data-aos="fade-up">
                POST to ${url} successed!
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`

            $('.main-inner').append(alert)  

            AOS.init({
                duration: 700,
                eeasing: 'ease-in-out-sine',
            })   
        }).fail(function() {
            $('#btn-spin').toggleClass('d-none')

            let alert = 
            `<div class="alert alert-danger alert-dismissible" role="alert" data-aos="fade-up">
                POST to ${url} failed, please watch log to get more information.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>`

            $('.main-inner').append(alert)

            AOS.init({
                duration: 700,
                eeasing: 'ease-in-out-sine',
            })   
        })
    })
})