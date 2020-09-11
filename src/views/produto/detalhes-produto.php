<?php
    $titlePage = "Loja Whatever - Sua loja de instrumentos músicais e acessórios";
    require_once './views/partials/header.php';
?>   

<!-- conteudo principal -->
<main>
    <div class="container">
        <section id="caminho-produto">
            <div class="row mt-3" >
                <div class="col-md-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-dark">
                            <li class="breadcrumb-item"><a href="index.html">Cordas</a></li>
                            <li class="breadcrumb-item"><a href="index.html">Guitarras</a></li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <?php echo $product->getTitle(); ?>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </section>

        <section id="detalhes-produto">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="card p-3">
                        <div class="row">
                            <!-- Imagens disponiveis para o produto -->
                            <div class="col-lg-5" id="img-container" data-qtd="<?php echo count($product->getImages()); ?>">
                                <div class="card p-3 slider-for">
                                    <?php foreach($product->getImages() as $image) :?>
                                    <div class="text-center">
                                        <img src="img/products/<?php echo $image["FileName"]?>" 
                                            class="img-fluid d-block " 
                                            style="max-height:250px; text-align:center; display:inline !important;" 
                                            alt="Guitarra Ibanez RG 7420Z | HH | 7 Cordas | Weathered Black (WK)" 
                                            title="Guitarra Ibanez RG 7420Z | HH | 7 Cordas | Weathered Black (WK)">
                                    </div>
                                    <?php endforeach; ?>
                                </div>   
                                <div class="slider-nav-produto">
                                    <?php foreach($product->getImages() as $image) :?>
                                    <div style="width:70px; height:70px; text-align:center">
                                        <img src="img/products/<?php echo $image["FileName"]?>" 
                                            style="max-height:50px;display:inline;"  class="img-fluid">
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <h4><?php echo $product->getTitle(); ?></h4>
                                <div class="sku">(Cód. <?php echo $product->getSku(); ?>)</div>
                                <div class="vendedor">Vendido por: <strong><?php echo $product->getSeller(); ?></strong></div>
                                <div class="estoque">Qtd. estoque: <?php echo $product->getStock(); ?></div>
                                <?php if (intval($product->getStock()) > 0): ?>
                                    <div class="preco">
                                        <div class="preco-avista h2 mb-0"><?php echo $product->getFormattedPrice() ?></div>
                                        <small class="parcela">em 1x no cartão</small>
                                    </div>
                                    <a class="btn btn-success btn-block" href="/adicionar-carrinho?id=<?php echo $product->getId(); ?>">
                                        <i class="fa fa-shopping-cart"></i> Adicionar no carrinho
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <section>
            <h3 class="mt-3">Informações do produto</h5>
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="card p-3">
                        <div class="row">
                            <div class="col-lg-12">
                                <p>
                                    <?php echo $product->getDescriptionFormatted(); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<?php require_once './views/partials/footer.php' ?>
<script src="libs/slick/slick.min.js"></script>
<script>
    $(function(){
        let qtdImgs = parseInt($("#img-container").data('qtd'));
        console.log(qtdImgs)
        if (qtdImgs > 0){
            let minImgs = 3;
            if (qtdImgs < minImgs)
                minImgs = qtdImgs;

            $('.slider-for').slick({
                arrows: false,
                fade: true,
                asNavFor: '.slider-nav-produto'
            });
            $('.slider-nav-produto').slick({
                slidesToShow:minImgs,
                arrows: true,
                asNavFor: '.slider-for',
                focusOnSelect: true,
                prevArrow:'<button type="button" class="btn btn-outline-dark slick-next"><i class="fa fa-chevron-left"></i></button>',
                nextArrow:'<button type="button" class="btn btn-outline-dark slick-next"><i class="fa fa-chevron-right"></i></button>'
            });

        }

    })

    
</script>
        