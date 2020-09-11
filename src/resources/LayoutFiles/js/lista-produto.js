class CadastroProduto{
    constructor(){
        this.iniciarEventos();
    }

    iniciarEventos(){
        

        setTimeout(() => {
            $('.card').addClass('in')

        },300)
    }
}

window.cadastroProduto = new CadastroProduto();