/*
    @autor: Michael Costa dos Reis
*/
(function ($) {
    /*
        el: Elemento da instância, use this.
        configuracoes: Objeto com parâmetros necessários para criarmos a instância do sidebar.
    */
    $.sidebar = function (el, configuracoes) {        

        $(".tooltip").remove();

        var obj = {
            'metodo': 'GET',
            'nivel': 1,
            'fechar': true,
            'fecharAoClicarFora': true,
            'pesquisaFixa': false,
            'submit': false,
            'focusCustomizado': false
        };        
        $.extend(obj, configuracoes);

        //criando elemento do sidebar
        if ($(".sidebar").length === 0) {
            $("body").append("<div class='sidebar-overlay branco' ></div>");
            if (obj.fecharAoClicarFora) {
                $(".sidebar-overlay").unbind("click");
                $(".sidebar-overlay").click(function () {
                    $.sidebar.fnFechar();
                });
            }
            $("body").append("<div class='sidebar animated slideInRight open '></div>");
        }

        //removendo scroll body
        $("body").css("overflow", "hidden");
        $(".sidebar > .nivel").addClass("hidden");


        $(obj.target).button("loading");
        $(".sidebar").append("<div class='nivel " + (obj.pesquisaFixa ? "pesquisa" : "") + "' data-nivel='" + obj.nivel + "'></div>");
        $(".sidebar .nivel[data-nivel='" + obj.nivel + "']").html('<div class="loading-flutuante">Processando, aguarde</div>');
        //preenchendo com conteudo retornado via ajax...

        var jqxhr = $.ajax({
            url: obj.url,
            type: obj.metodo,
            data: obj.parametros
        });
        jqxhr.error(function (e) {
            if (e.status == 404) 
                console.error("Página não encontrada, erro 404");
            else
                console.error(e.statusText);

            $.sidebar.fnRemoverNivel(obj.nivel);
        });
        jqxhr.done(function (retorno) {
            $(obj.target).button("reset");
            //preenchendo sidebar com o conteudo recebido via ajax...
            $(".sidebar .nivel[data-nivel='" + obj.nivel + "']").html(retorno);

            jQuery.validator.unobtrusive.parse(jQuery(".sidebar .nivel[data-nivel='" + obj.nivel + "']"));
            var form = $(".sidebar .nivel[data-nivel='" + obj.nivel + "'] form");
            form.submit(function () {
                return false;
            });
            
            $(document).keyup(function (e) {
                if (e.which == 13 && (form.find(".rodape button[data-submit=true]").length > 0))
                    form.find(".rodape button[data-submit=true]").trigger("click");
            });
            
            //callback após abrir modal lateral...
            if (obj.callbackAbrir != null && typeof(obj.callbackAbrir) === "function")
                obj.callbackAbrir.call(this);

            //montando botoes...
            if (obj.botoes != null && obj.botoes.length > 0) {
                //botoes parametrizados pelo usuario:
                $(obj.botoes).each(function (index, element) {
                    var loadingText = '...';
                    if (element.loadingText) {
                        loadingText = element.loadingText
                    } else if (element.submit) {
                        loadingText = 'Processando, aguarde...';
                    }

                    var bt = $("<button id='" + element.id + "' type='button' class='btn " + element.estilo + "' data-submit='" + element.submit + "' data-loading-text='"+loadingText+"' /> ");
                    if (element.icone != undefined && element.icone != null && element.icone != "")
                        bt.html("<i class='" + element.icone + "'></i> " + element.label);
                    else
                        bt.html(element.label);

                    bt.click(function (elBtCriado) {
                        if (element.submit) {
                            //submit padrao...                                                                
                            if (form.validate().form()) {
                                let btnAlvo = $(bt);
                                if (element.id !== null && element.id !== undefined)
                                    btnAlvo = $("#" + element.id);

                                btnAlvo.button("loading");
                                $.post(form.attr("action"), form.serialize(), function (data) {                                        
                                    if (data.Sucesso) {
                                        if (element.callback != null && typeof (element.callback) === "function")
                                            element.callback.call(this, data);
                                    } else
                                        console.error("Ops, ocorreu um problema verifique a seguir: " + data.Texto);

                                    btnAlvo.button("reset");

                                }).error(function (e, error) {
                                    Notifica("Erro", e.statusText, 2000, "danger");
                                    btnAlvo.button("reset");
                                });
                            }
                        } else {
                            //funcão passada pelo usuario...
                            if (element.callback != null && typeof (element.callback) === "function")
                                element.callback.call(this, elBtCriado);
                        }
                    });
                    $(".sidebar .nivel[data-nivel='" + obj.nivel + "'] .rodape").append(bt);
                });                    
            }

            //botao para fechar sidebar...
            if (obj.fechar) {
                var btFechar = $("<i class='fa fa-remove pull-right' style='cursor:pointer;' />");
                btFechar.click(function (el) {
                    $.sidebar.fnFechar();
                });
                $(".sidebar .nivel[data-nivel='" + obj.nivel + "'] .cabecalho").append(btFechar);
            }

            if (!obj.focusCustomizado)
                $.sidebar.fnFocus();
        });
        

        //eventos...
        if (obj.fechar) {
            $(document).unbind('keyup');
            $(document).keyup(function (e) {
                if (e.which == 27) $.sidebar.fnFechar(e);
            });
        }

        $.sidebar.fnLoader = function (ativar) {
            $(".sidebar.open .rodape").find('button').button(ativar === true ? 'loading' : 'reset');
        };

        $.sidebar.fnFechar = function (e) {
            $("body").css("overflow", "unset");
            $(".sidebar").toggleClass("slideOutRight");
            $(".sidebar-overlay").fadeOut(300, function () {
                $('.datepicker').remove();
                $(".sidebar-overlay").remove();
                $(".sidebar").remove();
                $(".popover.note-popover").remove()
                $(document).unbind("keyup");

                //callback botao fechar caso tenha sido definido pelo usuario...                
                if (obj.callbackFechar != null && typeof (obj.callbackFechar) === 'function') {
                    obj.callbackFechar.call(this);
                }
            });
        };

        $.sidebar.fnFocus = function () {
            $(".sidebar .nivel[data-nivel='"+obj.nivel+"']").find("input:not(:hidden),select,textarea").first().focus();
        };

        $.sidebar.fnAdicionarNivel = function () {
            var nivel = $(".sidebar > .nivel").length + 1;
            $(".sidebar > .nivel").addClass("hidden");
            $(".sidebar").append("<div class='nivel' data-nivel='" + nivel + "'></div>");
        };

        $.sidebar.fnRemoverNivel = function (nivel) {
            if (nivel == 1) {
                $.sidebar.fnFechar();
            } else {
                var nivelAtivar = nivel - 1;
                $(".sidebar > .nivel[data-nivel=" + nivel + "]").fadeOut(300, function () {
                    $(this).remove();
                });
                $(".sidebar > .nivel:not(.hidden)").addClass("hidden");
                $(".sidebar > .nivel[data-nivel=" + nivelAtivar + "]").removeClass("hidden");
            }
        };
        return this;
    };
})(jQuery);