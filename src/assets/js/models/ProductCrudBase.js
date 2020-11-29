class ProductCrudBase {

    constructor() {
        this._tbAttributeEl = $("#tb-attributes");
        this._hasNoAttributesEl = $('#has-no-attributes');
        this._optionsSwalSuccess = {
            showCancelButton: false,
            type: 'success',
            confirmButtonText: 'Voltar para lista.',
            showLoaderOnConfirm: true,
            allowOutsideClick: false
        };
        this.btnSubmit = $('button#btn-salvar');
        this.formEl = $('#form-cadastro');
        this.uploadEl = $('#upload-container');
        this.validator = new GenericValidator($('#form-cadastro'));
        this.dropzone = new ProductDropzone(this, $("#images"));

    }

    getModel() {
        let _self = this;
        return {
            productId: _self.formEl.find('#productId').val(),
            title: _self.formEl.find('#title').val(),
            price: _self.formEl.find('#price').val(),
            sku: _self.formEl.find('#sku').val(),
            description: _self.formEl.find('#description').val(),
            stock: _self.formEl.find('#stock').val(),
            images: _self.formEl.find('#images').val(),
            offer: _self.formEl.find("input[name=offer]:checked").val()
        };
    }

    initImgRemoveEvent() {
        let _self = this;
        $("#product-img-cards-container button.btn").unbind('click');
        $("#product-img-cards-container button.btn").click(function () {
            let el = $(this);
            let callback = function () {
                el.parent().parent().fadeOut(500, function () {
                    el.parent().parent().remove();
                    _self.dropzone.update();
                });
            };
            alertConfirm({ title: "Atenção", text: "Deseja remover a imagem?" }, callback);
        });
    }

    initAddAtributeEvent() {
        let _self = this;
        $("#btn-add-attribute").on('click', function () {
            _self.addAttribute($(this));
        });
    }

    getAttributesSelected() {
        let attributes = [];
        $('[name=cb-attribute]:checked').each(function () {
            let el = $(this);
            attributes.push({ attributeId: parseInt(el.val()), name: el.data('name') });
        });
        return attributes;
    }

    createRowAttributeHtml(attribute) {
        return `<tr data-attribute-id="${attribute.attributeId}">
            <td width="10px" scope="row" style="vertical-align:middle">
                <button type="button" class="btn btn-danger btn-sm btn-remove-attribute" data-toggle="button" aria-pressed="false" autocomplete="off"><i class="fa fa-trash"></i></button>
            </td>
            <td valign="middle" style="vertical-align:middle" width="30%">${attribute.name}</td>
            <td valign="middle" style="vertical-align:middle" width="70%">
                <input type="text" class="form-control" name="attribute-${attribute.attributeId}" id="attribute-${attribute.attributeId}" data-required="true" aria-describedby="helpId" placeholder="">
                <small id="helpId" class="form-text text-muted">Valor do atributo ${attribute.name}</small>
            </td>
        </tr>`;
    }

    tableAttributeIsVisible() {
        return !this._tbAttributeEl.hasClass("hidden");
    }

    initEventRemoveRowAttributeHtml() {
        let _self = this;
        $('.btn-remove-attribute').off('click');
        $('.btn-remove-attribute').on('click', function () {
            $(this).parent().parent().remove();
            setTimeout(function () {
                if (_self._tbAttributeEl.find('tbody > tr').length == 0) {
                    _self._tbAttributeEl.addClass('hidden');
                    _self._hasNoAttributesEl.removeClass('hidden');
                }
            }, 200);
        });
    }

    attributeAlreadyExists(attributeId) {
        return this._tbAttributeEl.find(`tbody > tr[data-attribute-id=${attributeId}]`).length > 0;
    }

    //adiciona atributo na ficha tec.
    addAttribute(btnEl) {
        btnEl = $(btnEl);
        let _self = this;
        let productId = btnEl.data('productId');
        $.sidebar(this, {
            url: `/admin/produto/atributo/adicionar?id=${productId}`,
            callbackAbrir: function () {
                $("#tb-avaliable-attributes tbody tr").on('click', function (event, handler) {
                    // console.log(event);
                    if (!$(event.target).is('input')) {
                        if ($(event.target).parent().find('input').is(':checked'))
                            $(event.target).parent().find('input').prop('checked', false);
                        else
                            $(event.target).parent().find('input').prop('checked', true);
                    }
                });
            },
            botoes: [
                {
                    estilo: "btn-warning", icone: "fa fa-chevron-left", callback: function () {
                        $.sidebar.fnFechar();
                    }
                },
                {
                    estilo: "btn-dark", icone: "fa fa-save", label: "Salvar", callback: function () {
                        let attributesSelecteds = _self.getAttributesSelected();

                        if (!_self.tableAttributeIsVisible()) {
                            _self._tbAttributeEl.removeClass('hidden');
                            _self._hasNoAttributesEl.addClass('hidden');
                        }

                        attributesSelecteds.forEach((attribute) => {
                            if (!_self.attributeAlreadyExists(attribute.attributeId))
                                _self._tbAttributeEl.find('tbody').append(_self.createRowAttributeHtml(attribute));
                        });
                        _self.initEventRemoveRowAttributeHtml();
                        $.sidebar.fnFechar();
                    }
                }
            ]
        });
    }
}