class ProductCrudBase
{   
    getModel()
    {
        let _self = this;
        return {
            productId : _self.formEl.find('#productId').val(),
            title: _self.formEl.find('#title').val(),
            price: _self.formEl.find('#price').val(),
            sku: _self.formEl.find('#sku').val(),
            description: _self.formEl.find('#description').val(),
            stock: _self.formEl.find('#stock').val(),
            images:_self.formEl.find('#images').val(),
            offer: _self.formEl.find("input[name=offer]:checked").val()
        };
    }

    initImgRemoveEvent()
    {
        let _self = this;
        $("#product-img-cards-container button.btn").unbind('click');
        $("#product-img-cards-container button.btn").click(function(){
            let el = $(this);
            let callback = function(){
                el.parent().parent().fadeOut(500, function(){
                    el.parent().parent().remove();
                    _self.dropzone.update();
                });
            };
            alertConfirm({
                title: "Atenção", 
                text: "Deseja remover a imagem?"
            }, callback);
        });
    }
}