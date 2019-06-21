$(document).ready(function(){
	if(parseInt($('#price_container').attr('min')) > 1)
	{
		$("#minusQtythis").click(function() {
			var quantity = parseFloat($("#quantity").val());
			quantity--;
			if(!(quantity <= 1))
			{			
				if($("#onepricee").attr("price") == $("#onepricee").text())
					var resultpr = parseFloat($("#onepricee").text()).toFixed(2) * parseFloat(quantity).toFixed(2);
				else
					var resultpr = parseFloat($("#onepricee").text()) * parseFloat(quantity).toFixed(2);
				$("#price_container").html(parseFloat(resultpr).toFixed(2));
				$("#quantity").val(quantity);
			}
			else
				$("#quantity").attr("value", 1);	
		});
		
		$("#plusQtythis").click(function() {
			var quantity = $("#quantity").val();
			quantity++;
			if($("#onepricee").attr("price") == $("#onepricee").text())
				var resultpr = parseFloat($("#onepricee").text()).toFixed(2) * parseFloat(quantity).toFixed(2);
			else
				var resultpr = parseFloat($("#onepricee").text()) * parseFloat(quantity).toFixed(2);
			$("#price_container").html(parseFloat(resultpr).toFixed(2));
			$("#quantity").attr("value", quantity);	
		});	
		
		$("input[type=radio]").on("click", function() {
			var conti = $(this).attr('price');
			conti = conti.replace(" ", "");
			if(conti == '' || conti == '0.0000')
			{
				conti = $('#onepricee').attr('price');
				var conti2 = $('#onepricee').attr('price');
			}	
			else{
				if($(this).attr('price-prefix') == '-')
					var conti2 = (parseFloat(conti) - parseFloat($('#onepricee').text()));
				if($(this).attr('price-prefix') == '+')
					var conti2 = (parseFloat(conti) + parseFloat($('#onepricee').text()));
			}
			var quantity = $("#quantity").attr("min");
			$("#quantity").attr("value", quantity);
			if(conti == $("#onepricee").attr("price"))
				var resultpri = parseFloat(conti).toFixed(2) * parseFloat(quantity).toFixed(2);
			else
			{
				if($(this).attr('price-prefix') == '-')
					var resultpri = (parseFloat(conti) - parseFloat($('#onepricee').text())) * parseFloat(quantity).toFixed(2);
				if($(this).attr('price-prefix') == '+')
					var resultpri = (parseFloat(conti) + parseFloat($('#onepricee').text())) * parseFloat(quantity).toFixed(2);	
			}
				
			$("#price_container").html(parseFloat(resultpri).toFixed(2));
			
			$('#onepricee').empty();
			$('#onepricee').append(parseFloat(conti2).toFixed(2));			
		});	
		
		$("select[class=op_proid]").on("change", function() {
			var conti = $(this).find('option:selected').first().attr('price');
			conti = conti.replace(" ", "");
			if(conti == '' || conti == '0.0000')
			{
				conti = $('#onepricee').attr('price');
				var conti2 = $('#onepricee').attr('price');
			}	
			else{
				if($(this).find('option:selected').first().attr('price-prefix') == '-')
					var conti2 = parseFloat($("#onepricee").attr("price")) - parseFloat(conti);
				if($(this).find('option:selected').first().attr('price-prefix') == '+')
					var conti2 = parseFloat($("#onepricee").attr("price")) + parseFloat(conti);
			}
			var quantity = $("#quantity").attr("min");
			$("#quantity").attr("value", quantity);
			if(conti == $("#onepricee").attr("price"))
				var resultpri = parseFloat(conti).toFixed(2) * parseFloat(quantity).toFixed(2);
			else
			{
				if($(this).find('option:selected').first().attr('price-prefix') == '-')
					var resultpri = (parseFloat($("#onepricee").attr("price")) - parseFloat(conti)) * parseFloat(quantity).toFixed(2);
				if($(this).find('option:selected').first().attr('price-prefix') == '+')
					var resultpri = (parseFloat($("#onepricee").attr("price")) + parseFloat(conti)) * parseFloat(quantity).toFixed(2);		
			}
				
			$("#price_container").html(parseFloat(resultpri).toFixed(2));
			
			$('#onepricee').empty();
			$('#onepricee').append(parseFloat(conti2).toFixed(2));	
		});		
	}
	else{
		
		$("#minusQtythis").click(function() {
			var quantity = parseFloat($("#quantity").val());
			if(!(quantity <= 1))
			{
				quantity--;
				if($("#onepricee2").attr("price") == $("#onepricee2").text())
					var resultpr = parseFloat($("#onepricee2").text()).toFixed(2) * parseFloat(quantity).toFixed(2);
				else
					var resultpr = parseFloat($("#onepricee2").text()) * parseFloat(quantity).toFixed(2);
				$("#price_container").html(parseFloat(resultpr).toFixed(2));
				$("#quantity").val(quantity);
			}
			else
				$("#quantity").attr("value", 1);
		});
		
		$("#plusQtythis").click(function() {
			var quantity = $("#quantity").val();
			quantity++;
			if($("#onepricee2").attr("price") == $("#onepricee2").text())
				var resultpr = parseFloat($("#onepricee2").text()).toFixed(2) * parseFloat(quantity).toFixed(2);
			else
				var resultpr = parseFloat($("#onepricee2").text()) * parseFloat(quantity).toFixed(2);
			$("#price_container").html(parseFloat(resultpr).toFixed(2));
			$("#quantity").attr("value", quantity);	
		});			
		
		$("input[type=radio]").on("click", function() {
			var conti = $(this).attr('price');
			conti = conti.replace(" ", "");
			if(conti == '' || conti == '0.0000')
			{
				conti = $('#onepricee2').attr('price');
				var conti2 = $('#onepricee2').attr('price');
			}	
			else{
				if($(this).attr('price-prefix') == '-')
					var conti2 = (parseFloat(conti) - parseFloat($('#onepricee2').text()));
				if($(this).attr('price-prefix') == '+')
					var conti2 = (parseFloat(conti) + parseFloat($('#onepricee2').text()));
			}
			var quantity = $("#quantity").attr("min");
			$("#quantity").attr("value", quantity);
			if(conti == $("#onepricee2").attr("price"))
				var resultpri = parseFloat(conti).toFixed(2) * parseFloat(quantity).toFixed(2);
			else
			{
				if($(this).attr('price-prefix') == '-')
					var resultpri = (parseFloat(conti) - parseFloat($('#onepricee2').text())) * parseFloat(quantity).toFixed(2);
				if($(this).attr('price-prefix') == '+')
					var resultpri = (parseFloat(conti) + parseFloat($('#onepricee2').text())) * parseFloat(quantity).toFixed(2);	
			}
				
			$("#price_container").html(parseFloat(resultpri).toFixed(2));
			
			$('#onepricee2').empty();
			$('#onepricee2').append(parseFloat(conti2).toFixed(2));			
		});	
		
		$("select[class=op_proid]").on("change", function() {
			var conti = $(this).find('option:selected').first().attr('price');
			conti = conti.replace(" ", "");
			if(conti == '' || conti == '0.0000'){
				conti = $('#onepricee2').attr('price');
				var conti2 = $('#onepricee2').attr('price');
			}else{
				if($(this).find('option:selected').first().attr('price-prefix') == '-')
					var conti2 = parseFloat($("#onepricee2").attr("price")) - parseFloat(conti);
				if($(this).find('option:selected').first().attr('price-prefix') == '+')
					var conti2 = parseFloat($("#onepricee2").attr("price")) + parseFloat(conti);
			}
            
			var quantity = $("#quantity").attr("min");
			$("#quantity").attr("value", quantity);
			if(conti == $("#onepricee2").attr("price"))
				var resultpri = parseFloat(conti).toFixed(2) * parseFloat(quantity).toFixed(2);
			else
			{
				if($(this).find('option:selected').first().attr('price-prefix') == '-')
					var resultpri = (parseFloat($("#onepricee2").attr("price")) - parseFloat(conti)) * parseFloat(quantity).toFixed(2);
				if($(this).find('option:selected').first().attr('price-prefix') == '+')
					var resultpri = (parseFloat($("#onepricee2").attr("price")) + parseFloat(conti)) * parseFloat(quantity).toFixed(2);		
			}
			
			$("#price_container").html(parseFloat(resultpri).toFixed(2));
			
			$('#onepricee2').empty();
			$('#onepricee2').append(parseFloat(conti2).toFixed(2));	
		});			
	}

});	

