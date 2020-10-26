<div class="row">
    <div class="col-md-12">
        <table id="tb-products" data-page="0" class="table table-bordered table-hovered table-striped">
            <thead>
                <tr>
                    <th width="10%"></th>
                    <th style="text-align:center">Image</th>
                    <th>Produto</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!isset($products) || count($products) == 0) : ?>
                    <tr>
                        <td colspan="5">Nenhum registro cadastrado.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" name="cb-similar-product" id="cb-similar-product-<?php echo $product->getId(); ?>">
                            </td>
                            <td class="text-center">
                                <?php if ($product->hasImages()) : ?>
                                    <img src="<?php echo $product->getDefaultImage(); ?>" title="" alt="" class="img-fluid" style="max-width:100px; max-height:100px; ">
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong>Sku:</strong> <?php echo $product->getSku(); ?> <br />
                                <strong>Vendor:</strong> <?php echo $product->getSeller(); ?><br />
                                <?php echo $product->getTitle(); ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?php require_once './views/partials/pagination-admin-controlls.php' ?>
    </div>
</div>