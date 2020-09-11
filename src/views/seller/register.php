<!doctype html>
<html lang="en">
    <head>
        <title>Loja Whatever - Sua loja de instrumentos músicais e acessórios</title>

        <!-- Meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" 
            rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" 
            href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
            integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" 
            crossorigin="anonymous">
        <link rel="stylesheet" 
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" 
            href="css/login.css">
        <link rel="stylesheet" href="/libs/sweetAlert2/sweetalert2.min.css">
    </head>
    <body>  
        <!-- cabeçalho e navegação -->
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">
                        <span class="logo-strong">L</span>oja<span class="logo-strong">W</span>hatever</a>
                </div>
            </nav>
            <div class="spacer-header"></div>
        </header>
        <main>
           <div class="container-register">
                <div class="col-md-12">
                    <form class="form-signin mt-5" id="form-login"  method="post" action="/login-post">
                        <div class="card p-3">
                            <h1 class="h4 mb-3 font-weight-normal text-center ">
                                Para começar a vender informe os dados abaixo e clique em criar conta
                            </h1>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nome" class="sr-only">Nome</label>
                                        <input type="text" id="nome" name="nome" class="form-control" 
                                        placeholder="Informe seu nome" required="" 
                                        autofocus="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sobrenome" class="sr-only">Password</label>
                                        <input type="text" id="sobrenome" name="sobrenome" class="form-control" 
                                            placeholder="Informe seu sobrenome" required="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="login" class="sr-only">Login</label>
                                    <input type="login" id="login" name="login"
                                         class="form-control" 
                                        placeholder="Informe seu login" required="" 
                                        autofocus="">
                                </div>
                                <div class="col-md-6">
                                    <label for="password" class="sr-only">Password</label>
                                    <input type="password" id="password" name="password" 
                                        class="form-control" 
                                        placeholder="Informe sua senha" required="">
                                </div>
                            </div>
                            <button class="btn btn-lg btn-block mt-3 btn-outline-dark" 
                                id="btn-register"
                                data-loading-text="Processando, aguarde <i class='fa fa-circle-o-notch fa-spin'></i>"
                                type="submit"> Criar conta
                            </button>
                        </div>
                    </form>
                </div>
           </div>
        </main>
        <footer class="mt-3 text-light"></footer>

        <!-- Optional JavaScript -->
        <script src="/libs/jquery/jquery-3.4.1.min.js" ></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" 
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" 
            crossorigin="anonymous"></script>
        <script src="/libs/sweetAlert2/sweetalert2.min.js"></script>
        <script src="/js/bs4-extensions.js"></script>
        <script src="/js/sweetAlertHelper.js"></script>
        <script src="/js/models/SellerRegister.js"></script>
        
        <script>
            let sellerRegister = null;
            $(function(){
                sellerRegister = new SellerRegister();
            });
        </script>
    </body>
</html>