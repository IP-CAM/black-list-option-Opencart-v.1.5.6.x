function productparam_refreshPrice(el){
    var priceEl = el.find('.price');

    var sum = parseFloat(priceEl.attr('data-price'));

    el.find('.options div').each(function(){  

        var conti = $(this).attr('price');   

        if(conti){     
            conti = conti.replace(" ", "");
            if(conti != '' || conti != '0.0000'){
                sum += parseFloat(conti);
            }
        }
    });

    var quantity = el.find('input[name="quantity"]').val();

    sum *= quantity;

    var s = priceEl.attr('data-currency');

    if(quantity == 1) s = priceEl.attr('data-currency')+'/'+priceEl.attr('data-pc');

    priceEl.find('span').eq(0).html(parseFloat(sum).toFixed(2)+s);

}

function productparam_setOptionDivPrice(_box,price){
    var box = $(_box).parent().parent();
    price = (price)?price:"0";
    box.attr('price',$(_box).attr('price-prefix')+price);
    productparam_refreshPrice(box.parent().parent().parent());
}

function productparam_refreshEvent(){
    $(".minus-q").click(function() {
        var quantity = $(this).parent().find('input[name="quantity"]');
        if(quantity.attr('min')<quantity.val()){
            var quantityV = quantity.val()*1-1;
            quantity.val(quantityV);
            productparam_refreshPrice(quantity.parent().parent().parent().parent().parent());
        }
    });
    
    
    $(".category-product-option input[type=radio]").on("click", function(){
        productparam_setOptionDivPrice(this, $(this).attr('price'));        
    }); 
    
    $(".category-product-option select[class=op_proid]").on("change", function() {
        productparam_setOptionDivPrice(this, $(this).find('option:selected').first().attr('price'));     
    });
    
    $(".plus-q").click(function() {        
        var quantity = $(this).parent().find('input[name="quantity"]');
        var quantityV = quantity.val()*1+1;
        quantity.val(quantityV);
        productparam_refreshPrice(quantity.parent().parent().parent().parent().parent());
    });
    
    $(".minus-q").click(function() {
        var quantity = $(this).parent().find('input[name="quantity"]');
        if(quantity.attr('min')<quantity.val()){
            var quantityV = quantity.val()*1-1;
            quantity.val(quantityV);
            productparam_refreshPrice(quantity.parent().parent().parent().parent().parent());
        }
    });
}