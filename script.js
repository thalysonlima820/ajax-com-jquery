$(document).ready(() => {
	
    $('#documentacao').on('click', () => {

        // METODO LOAD
        //$("#pagina").load('documentacao.html')

        // METODO GET
        $.get('documentacao.html', data => {
            $('#pagina').html(data)
        })

        // METODO POST
        // $.post('documentacao.html', data => {
        //      $('#pagina').html(data)
        // })


    })
    $('#suporte').on('click', () => {
        $.get('suporte.html', data => {
            $('#pagina').html(data)
        })
    })


    $('#competencia').on('change', e => {
        // console.log($(e.target).val())

        let competencia = $(e.target).val()
        // console.log(competencia)

        $.ajax({
            type: 'GET',
            url: 'app.php',
            data: `competencia=${competencia}`,
            dataType: 'json',
            success: dados => { console.log(dados.numeroVendas, dados.totalVendas);
                $('#numero_vendas').html(dados.numeroVendas);
                $('#total_vendas').html( 'R$ ' + dados.totalVendas)
            },
            error: erro => { console.log(erro)}
        })
    })



})