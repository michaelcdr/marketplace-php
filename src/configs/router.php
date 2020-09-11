<?php
    use controllers\HomeController;
    use controllers\CreateDbController;
    use controllers\DestroyDbController;
    use controllers\UserLogoutController;
    use controllers\UserLoginController;
    use controllers\UserAuthenticateController;
    use controllers\SearchController;
    use controllers\SeedController;
    use controllers\DetailsProductController;
    
    use controllers\UserCreateController;
    use controllers\UserEditController;
    use controllers\UserEditPostController;
    use controllers\UserCreatePostController;
    use controllers\UserDeleteController;
    use controllers\UserListController;
    use controllers\UserPartiaListController;
    use controllers\UserRegisterController;
    use controllers\UserRegisterPostController;

    use controllers\OrderListController;
    use controllers\OrderListPartialController;
    use controllers\OrderDetailsController;

    use controllers\AddToCartController;
    use controllers\CartController;
    use controllers\RemoveFromCartController;
    use controllers\CartListController;
    use controllers\CartAtualizarQtdProdutoController;
    use controllers\ProductListController;
    use controllers\ProductCreateController;
    use controllers\ProductCreatePostController;
    use controllers\ProductEditController;
    use controllers\ProductEditPostController;
    use controllers\ProductPartialListController;
    use controllers\ProductDeleteController;
    use controllers\ProductImageUploadController;

    use controllers\CartCheckoutController;
    use controllers\CartCheckoutPostController;

    use controllers\SellerSimpleCreateController;
    use controllers\SellerRegisterController;
    use controllers\SellerRegisterPostController;
    use controllers\SellerAuthController;
    use controllers\SellerListController;
    use controllers\SellerPartialListController;
    use controllers\SellerEditController;
    use controllers\SellerEditPostController;
    use controllers\SellerCreateController;
    use controllers\SellerCreatePostController;
    use controllers\SellerDeleteController;
    use controllers\SellerDetailsController;

    use controllers\CategoryEditController;
    use controllers\CategoryEditPostController;
    use controllers\CategoryCreateController;
    use controllers\CategoryCreatePostController;
    use controllers\CategoryDeleteController;
    use controllers\CategoryListController;
    use controllers\CategoryListPartialController;
    use controllers\CategoryImageUploadController;

    use controllers\AddressCreateController;

    //rota X [controller , roles]
    $routes = [
        "/" => [HomeController::class, ""],
        "/createdb" => [CreateDbController::class, ""], 
        "/destroydb" => [DestroyDbController::class, ""],
        "/logout" => [UserLogoutController::class, "admin,vendedor,comum"],
        "/login" => [UserLoginController::class, ""],
        "/vender" => [SellerSimpleCreateController::class, ""],
        "/registrar" => [UserRegisterController::class, ""],
        "/registrar-usuario-post" => [UserRegisterPostController::class, ""],
        "/vendedor-indentificacao" => [SellerAuthController::class, ""],
        "/vendedor-registrar" => [SellerRegisterController::class, ""],
        "/vendedor-registrar-post" => [SellerRegisterPostController::class, ""],
        
        "/autenticar" => [UserAuthenticateController::class, ""],
        "/pesquisa" => [SearchController::class, ""],
        "/seed" => [SeedController::class, ""],
        "/detalhes-produto" => [DetailsProductController::class, ""],
        "/carrinho"=> [CartController::class, ""],
        "/listar-itens-carrinho"=> [CartListController::class, ""],
        "/atualizar-quantidade-produto" => [CartAtualizarQtdProdutoController::class, ""],
        "/adicionar-carrinho" => [AddToCartController::class, ""],
        "/finalizar-pedido" => [CartCheckoutController::class, ""],
        "/cart-checkout-post"=> [CartCheckoutPostController::class, ""],
        "/remover-item-carrinho" => [RemoveFromCartController::class, ""],       

        "/admin/usuario/editar" => [UserEditController::class, "admin,vendedor,comum"],
        "/admin/usuario/editar-post" => [UserEditPostController::class, "admin,vendedor,comum"],
        "/admin/usuario/cadastrar" => [UserCreateController::class,"admin"],
        "/admin/usuario/cadastrar-post" => [UserCreatePostController::class,"admin"],
        "/admin/usuario/deletar" => [UserDeleteController::class,"admin"],
        "/admin/usuario" => [UserListController::class,"admin"],        
        "/admin/usuario/lista-table" => [UserPartiaListController::class,"admin"],
        
        "/admin/produto" => [ProductListController::class, "admin,vendedor"],
        "/admin/produto/lista-partial" => [ProductPartialListController::class, "admin,vendedor"],
        "/admin/produto/cadastrar" => [ProductCreateController::class,"admin,vendedor"],
        "/admin/produto/cadastrar-post" => [ProductCreatePostController::class,"admin,vendedor"],
        "/admin/produto/editar" => [ProductEditController::class,"admin,vendedor"],
        "/admin/produto/editar-post" => [ProductEditPostController::class,"admin,vendedor"],
        "/admin/produto/deletar" => [ProductDeleteController::class,"admin,vendedor"],
        "/admin/produto/upload" => [ProductImageUploadController::class,"admin,vendedor"],
        
        "/admin/categoria/upload" => [CategoryImageUploadController::class,"admin"],
        "/admin/categoria/editar" => [CategoryEditController::class, "admin"],
        "/admin/categoria/editar-post" => [CategoryEditPostController::class, "admin"],
        "/admin/categoria/cadastrar" => [CategoryCreateController::class,"admin"],
        "/admin/categoria/cadastrar-post" => [CategoryCreatePostController::class,"admin"],
        "/admin/categoria/deletar" => [CategoryDeleteController::class,"admin"],
        "/admin/categoria" => [CategoryListController::class,"admin"],        
        "/admin/categoria/lista-table" => [CategoryListPartialController::class,"admin"],
        
        "/admin/vendedor" => [SellerListController::class,"admin"],
        "/admin/vendedor/lista-table" => [SellerPartialListController::class,"admin"],
        "/admin/vendedor/editar" => [SellerEditController::class, "admin,vendedor"],
        "/admin/vendedor/editar-post" => [SellerEditPostController::class, "admin,vendedor"],
        "/admin/vendedor/cadastrar" => [SellerCreateController::class,"admin"],
        "/admin/vendedor/cadastrar-post" => [SellerCreatePostController::class,"admin"],
        "/admin/vendedor/deletar" => [SellerDeleteController::class,"admin"],
        "/admin/vendedor/detalhes" => [SellerDetailsController::class,"admin"],

        "/admin/endereco/cadastrar" => [AddressCreateController::class,""],

        "/admin/usuario/minhas-compras" => [OrderListController::class,"admin,vendedor,comum"],
        "/admin/pedido/detalhes" => [OrderDetailsController::class,"comum,admin,vendedor"],
    ];
    return $routes;
?>