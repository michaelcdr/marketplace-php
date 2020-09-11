class CadastroProduto{
    constructor(){
        this.condicaoEl = $('#condicao');
        this.btnSubmit = $('button#btn-salvar');
        this.iniciarEventos();
    }

    iniciarEventos(){
        let _self = this;
        //console.log(_self);
        //console.log(_self.tipoPessoaEl);

        _self.condicaoEl.change((el) => {
            let value = $(el.target).val()
            $('.campos-condicao').addClass('hidden');
            if (value){
                $('.campos-condicao[data-tipo="'+value+'"]').removeClass('hidden');
            } else {
                $('.campos-condicao').addClass('hidden');
            }
        });

        // _self.btnSubmit.submit(() => {
            
        // });
    }
}

window.cadastroProduto = new CadastroProduto();