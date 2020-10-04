class Home{
    constructor(){
        this.animateSections();
    }

    animateSections(){
        //dando uma animada na bagaça...
        setTimeout(() => {
            $('.card').addClass('in');
            setTimeout(() => {
                $('#linhas').addClass('in');
            },300);
        },300);        
    }
}
