function productparam_refreshPrice(el){
    var priceEl = el.find('.price');

    var sum = parseFloat(priceEl.attr('data-price'));

    el.find('.category-product-option').each(function(){  

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

function productparam_setOptionDivPrice(box,price,prefix){
    price = (price)?price:"0";
    box.attr('price',prefix+price);
    productparam_refreshPrice(box.parent().parent());
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
        var el = $(this);
        var box = el.parent().parent();  
        productparam_setOptionDivPrice(
                box, 
                el.attr('price'),
                el.attr('price-prefix')
        );        
    }); 
    
    $(".category-product-option select.op_proid").on("change", function() {
        var el = $(this);
        var box = el.parent();        
        productparam_setOptionDivPrice(
                box, 
                el.find('option:selected').first().attr('price'),
                el.find('option:selected').first().attr('price-prefix')
        );     
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
    $('input[name="quantity"]').change(function() {
        var quantity = $(this).parent().find('input[name="quantity"]');
        if(quantity.attr('min')<quantity.val()){
            var quantityV = quantity.val()*1;
            quantity.val(quantityV);
            productparam_refreshPrice(quantity.parent().parent().parent().parent().parent());
        }
    });
    
}