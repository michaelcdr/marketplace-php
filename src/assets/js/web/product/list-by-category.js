$(function () {
    $('.categories-menu ul > li a').on('click', function () {
        $.get("/produto/subcategoria", { subCategoryId: $(this).data('id') }, function (data) {
            $("#products-list").html(data);
        });
    });
});