class Carousel{
    constructor(selector){
        //console.log('carrosel iniciado.');
        $(selector).slick({
            fade:true,
            autoplay:true,
            arrows:true,
            dots:true
        });
    }
}