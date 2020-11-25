<?php

use controllers\HomeController;
use controllers\CreateDbController;
use controllers\DestroyDbController;
use controllers\SearchController;
use controllers\SeedController;
use controllers\DetailsProductController;

use controllers\users\UserLogoutController;
use controllers\users\UserLoginController;
use controllers\users\UserAuthenticateController;
use controllers\users\UserCreateController;
use controllers\users\UserEditController;
use controllers\users\UserEditPostController;
use controllers\users\UserCreatePostController;
use controllers\users\UserDeleteController;
use controllers\users\UserListController;
use controllers\users\UserPartiaListController;
use controllers\users\UserRegisterController;
use controllers\users\UserRegisterPostController;

use controllers\OrderListController;
use controllers\OrderListPartialController;
use controllers\OrderDetailsController;

use controllers\AddToCartController;
use controllers\CartController;
use controllers\RemoveFromCartController;
use controllers\CartListController;
use controllers\CartAtualizarQtdProdutoController;

use controllers\products\ProductListController;
use controllers\products\ProductCreateController;
use controllers\products\ProductCreatePostController;
use controllers\products\ProductEditController;
use controllers\products\ProductEditPostController;
use controllers\products\ProductPartialListController;
use controllers\products\ProductDeleteController;
use controllers\products\ProductImageUploadController;
use controllers\products\ProductSimilarController;
use controllers\products\AddSimilarProductControler;
use controllers\products\AddSimilarProductPostControler;

use controllers\CartCheckoutController;
use controllers\CartCheckoutPostController;

use controllers\sellers\SellerSimpleCreateController;
use controllers\sellers\SellerRegisterController;
use controllers\sellers\SellerRegisterPostController;
use controllers\sellers\SellerAuthController;
use controllers\sellers\SellerListController;
use controllers\sellers\SellerPartialListController;
use controllers\sellers\SellerEditController;
use controllers\sellers\SellerEditPostController;
use controllers\sellers\SellerCreateController;
use controllers\sellers\SellerCreatePostController;
use controllers\sellers\SellerDeleteController;
use controllers\sellers\SellerDetailsController;

use controllers\categories\CategoryEditController;
use controllers\categories\CategoryEditPostController;
use controllers\categories\CategoryCreateController;
use controllers\categories\CategoryCreatePostController;
use controllers\categories\CategoryDeleteController;
use controllers\categories\CategoryListController;
use controllers\categories\CategoryListPartialController;
use controllers\categories\CategoryImageUploadController;

use controllers\attributes\AttributeEditController;
use controllers\attributes\AttributeEditPostController;
use controllers\attributes\AttributeCreateController;
use controllers\attributes\AttributeCreatePostController;
use controllers\attributes\AttributeDeleteController;
use controllers\attributes\AttributeListController;
use controllers\attributes\AttributeListPartialController;

use controllers\AddressCreateController;
use controllers\products\SimilarProductPartialListController;
use controllers\products\DeleteSimilarProductControler;
use controllers\products\DislikeProductController;
use controllers\products\LikeProductController;

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
    "/carrinho" => [CartController::class, ""],
    "/listar-itens-carrinho" => [CartListController::class, ""],
    "/atualizar-quantidade-produto" => [CartAtualizarQtdProdutoController::class, ""],
    "/adicionar-carrinho" => [AddToCartController::class, ""],
    "/finalizar-pedido" => [CartCheckoutController::class, ""],
    "/cart-checkout-post" => [CartCheckoutPostController::class, ""],
    "/remover-item-carrinho" => [RemoveFromCartController::class, ""],
    "/produto/curtir" => [LikeProductController::class, ""],
    "/produto/descurtir" => [DislikeProductController::class, ""],

    "/admin/usuario/editar" => [UserEditController::class, "admin,vendedor,comum"],
    "/admin/usuario/editar-post" => [UserEditPostController::class, "admin,vendedor,comum"],
    "/admin/usuario/cadastrar" => [UserCreateController::class, "admin"],
    "/admin/usuario/cadastrar-post" => [UserCreatePostController::class, "admin"],
    "/admin/usuario/deletar" => [UserDeleteController::class, "admin"],
    "/admin/usuario" => [UserListController::class, "admin"],
    "/admin/usuario/lista-table" => [UserPartiaListController::class, "admin"],

    "/admin/produto" => [ProductListController::class, "admin,vendedor"],
    "/admin/produto/lista-partial" => [ProductPartialListController::class, "admin,vendedor"],
    "/admin/produto/cadastrar" => [ProductCreateController::class, "admin,vendedor"],
    "/admin/produto/cadastrar-post" => [ProductCreatePostController::class, "admin,vendedor"],
    "/admin/produto/editar" => [ProductEditController::class, "admin,vendedor"],
    "/admin/produto/editar-post" => [ProductEditPostController::class, "admin,vendedor"],
    "/admin/produto/deletar" => [ProductDeleteController::class, "admin,vendedor"],
    "/admin/produto/upload" => [ProductImageUploadController::class, "admin,vendedor"],

    "/admin/produto/similares" => [ProductSimilarController::class, "admin,vendedor"],
    "/admin/produto/similares/lista-partial" => [SimilarProductPartialListController::class, "admin,vendedor"],
    "/admin/produto/similares/add" => [AddSimilarProductControler::class, "admin,vendedor"],
    "/admin/produto/similares/add-post" => [AddSimilarProductPostControler::class, "admin,vendedor"],
    "/admin/produto/similares/deletar" => [DeleteSimilarProductControler::class, "admin,vendedor"],

    "/admin/categoria/upload" => [CategoryImageUploadController::class, "admin"],
    "/admin/categoria/editar" => [CategoryEditController::class, "admin"],
    "/admin/categoria/editar-post" => [CategoryEditPostController::class, "admin"],
    "/admin/categoria/cadastrar" => [CategoryCreateController::class, "admin"],
    "/admin/categoria/cadastrar-post" => [CategoryCreatePostController::class, "admin"],
    "/admin/categoria/deletar" => [CategoryDeleteController::class, "admin"],
    "/admin/categoria" => [CategoryListController::class, "admin"],
    "/admin/categoria/lista-table" => [CategoryListPartialController::class, "admin"],

    "/admin/atributo" => [AttributeListController::class, "admin"],
    "/admin/atributo/lista-table" => [AttributeListPartialController::class, "admin"],
    "/admin/atributo/cadastrar" => [AttributeCreateController::class, "admin"],
    "/admin/atributo/cadastrar-post" => [AttributeCreatePostController::class, "admin"],
    "/admin/atributo/editar" => [AttributeEditController::class, "admin"],
    "/admin/atributo/editar-post" => [AttributeEditPostController::class, "admin"],
    "/admin/atributo/deletar" => [AttributeDeleteController::class, "admin"],

    "/admin/vendedor" => [SellerListController::class, "admin"],
    "/admin/vendedor/lista-table" => [SellerPartialListController::class, "admin"],
    "/admin/vendedor/editar" => [SellerEditController::class, "admin,vendedor"],
    "/admin/vendedor/editar-post" => [SellerEditPostController::class, "admin,vendedor"],
    "/admin/vendedor/cadastrar" => [SellerCreateController::class, "admin"],
    "/admin/vendedor/cadastrar-post" => [SellerCreatePostController::class, "admin"],
    "/admin/vendedor/deletar" => [SellerDeleteController::class, "admin"],
    "/admin/vendedor/detalhes" => [SellerDetailsController::class, "admin"],

    "/admin/endereco/cadastrar" => [AddressCreateController::class, ""],

    "/admin/usuario/minhas-compras" => [OrderListController::class, "admin,vendedor,comum"],
    "/admin/pedido/detalhes" => [OrderDetailsController::class, "comum,admin,vendedor"],
];


return $routes;
