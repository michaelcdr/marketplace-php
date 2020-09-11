//evitando que o dropzonejs fique procurando uma instancia...
Dropzone.autoDiscover = false;

class ProductCreate extends ProductCrudBase
{
    constructor()
    {
        super();

        this.btnSubmit = $('button#btn-salvar');
        this.formEl = $('#form-cadastro');
        this.uploadEl = $('#upload-container');
        this.validator = new GenericValidator($('#form-cadastro'));
        this.dropzone = new ProductDropzone(this, $("#images"));
        this.initEvents();
    }

    initEvents()
    {
        this.initSubmitEvent();
    }

    initSubmitEvent()
    {
        let _self = this;
        _self.formEl.submit(function() {            
            let validateResponse = _self.validator.validate();
            if (validateResponse.isValid)
            {   
                //dados validos, iremos gravar...  
                _self.btnSubmit.button('loading');

                $.ajax({
                    type: 'POST',
                    url: '/admin/produto/cadastrar-post',
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData:false,
                    success: function(data){ 
                        if (data.success){
                            Swal.fire({
                                title: data.msg,                            
                                showCancelButton: false,
                                type:'success',
                                confirmButtonText: 'Ok, voltar para lista.',
                                showLoaderOnConfirm: true,                        
                                allowOutsideClick: false

                            }).then((result) => {
                                if (result.value) {
                                    document.location = "/admin/produto";
                                }
                            });
                        } else {
                            //se deu alguma zica na request mostraremos 
                            //um alert amigavel...
                            alertError({ toast:false, text :data.msg});
                            _self.btnSubmit.button('reset');
                        }
                    }
                })
            }
            return false;
        });
    }
}

window.productImageCard = new ProductImageCard();
window.productCreate = new ProductCreate();