<section id="caminho-produto">
    <div class="row mt-3">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-dark">
                    <li class="breadcrumb-item"><a href="index.html"><?php echo $product->getCategoryName(); ?></a></li>
                    <li class="breadcrumb-item"><a href="index.html"><?php echo $product->getSubCategoryName(); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <?php echo $product->getTitle(); ?>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</section>