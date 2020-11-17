class ListOfSimilarProducts {
    constructor() {
        this.initEvents();
        this._routeDelete = '/admin/produto/deletar';
        this._routeList = '/admin/produto/lista-partial';
        this._listContainerEl = $('#container-products');
        console.log("entrou em ListOfSimilarProducts");
    }

    initEvents() {
        let _self = this;
        $('.btn-delete').unbind('click')
        $('.btn-delete').click(function () {
            let btnEl = $(this);
            _self.delete(btnEl);
        });

        $('#btn-add').unbind('click')
        $('#btn-add').click(function () {
            _self.add($(this));
        });

        $('#btn-pesquisar').unbind('click')
        $('#btn-pesquisar').click(function () {
            let search = $("#search-products").val();
            _self.toList(0, search);
        });

        $("#search-products").unbind('keyup')
        $("#search-products").keyup(function (ev) {
            if (ev.which === 13)
                _self.toList(0, $("#search-products").val());
        });
    }

    delete(btnEl) {
        //exibindo msg amigavel ao usuario e quando ele confirmar volta para index
        let _self = this;
        let callback = function () {
            let params = { id: btnEl.data('id') };
            $.post(_self._routeDelete, params, function (data) {
                if (data.success) {
                    _self.toList(0, $("#search-products").val());
                } else {
                    alertError({ text: data.text, msg: data.msg });
                }
            });
        }
        alertConfirm({
            title: 'Deseja remover esse produto?',
            text: 'Essa ação não podera ser desfeita.'
        }, callback);
    }

    toList(page, search) {
        let params = { page: page, s: search };
        let _self = this;
        $.get(_self._routeList, params, function (data) {
            _self._listContainerEl.html(data);
            _self.initEvents();
        });
    }

    add(btnEl) {
        console.log('entrou no metodo adicionar')
        btnEl = $(btnEl);
        $.sidebar(this, {
            url: `/admin/produto/similares/add?id=${btnEl.data('productId')}`,
            botoes: [
                {
                    estilo: "btn-warning", icone: "fa fa-chevron-left", callback: function () {
                        $.sidebar.fnFechar();
                    }
                }
            ]
        });
    }
}

window.productList = new ListOfSimilarProducts();