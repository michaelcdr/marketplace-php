$(function() {
    let qtdImgs = parseInt($("#img-container").data('qtd'));

    if (qtdImgs > 0) {
        let minImgs = 3;
        if (qtdImgs < minImgs)
            minImgs = qtdImgs;

        $('.slider-for').slick({
            arrows: false,
            fade: true,
            asNavFor: '.slider-nav-produto'
        });

        $('.similar-products-slider').slick({
            speed: 1500,
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: true,
            autoplay: true,
            infinite: true,
            autoplaySpeed: 2000,
            prevArrow: '<a class="slick-prev">' +
                '<i class="fa fa-chevron-left"></i>' +
                '</a>',
            nextArrow: '<a  class="slick-next">' +
                '<i class="fa fa-chevron-right"></i>' +
                '</a>'
        });

        $('.slider-nav-produto').slick({
            slidesToShow: minImgs,
            arrows: true,
            asNavFor: '.slider-for',
            focusOnSelect: true,
            prevArrow: '<button type="button" class="btn btn-outline-dark slick-next">' +
                '<i class="fa fa-chevron-left"></i>' +
                '</button>',
            nextArrow: '<button type="button" class="btn btn-outline-dark slick-next">' +
                '<i class="fa fa-chevron-right"></i>' +
                '</button>'
        });
    }
});