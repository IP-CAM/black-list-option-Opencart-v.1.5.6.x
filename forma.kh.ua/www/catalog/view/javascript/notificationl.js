var urldom = $(location).attr('protocol');
urldom += "//";
urldom += $(location).attr('host')+"/";

var urlsecret = urldom;
urlsecret += "secret-razdel";

var urllogin = urldom;
urllogin += "index.php?route=account/login";

var urlregister = urldom;
urlregister += "index.php?route=account/simpleregister";

var successac = urldom;
successac += "index.php?route=account/success";


if(urlregister == location.href)
{
	localStorage.setItem('_ym_altOpvma21d', 1);
}

if(successac == location.href)
{
	if(localStorage.getItem('_ym_altOpvma21d') != 1)
	{
		setTimeout( 
			function() 
			{
				document.location.href = "https://forma.kh.ua/secret-razdel";
			}
			, 2000);
	}
}
	
if(urlregister !== $(location).attr('href') && urllogin !== $(location).attr('href'))
{

	
	$(document).ready(function(){
		var getvalue = $('.fmkh').find('a').attr('href');
		if(getvalue == urllogin)
		{	
			$.get("https://forma.kh.ua/catalog/view/theme/bakers-choco/template/common/forma/formalog.html", function(data) 
			{
				setTimeout(
					function() 
					{
						if(localStorage.getItem('formalogin') != 1)
						{
							localStorage.setItem('_ym_altOpvma21d', 0);
							localStorage.setItem('formalogin', 1);
							$('.formalog').remove();
							$('.formalog-overlay').remove();
							$('body').append(data);

							$('.formalog-overlay').fadeIn(1000,
							function(){
								$('.formalog').animate({opacity: 1, top: '15%'}, 300);
								$('.formalog-overlay').animate({opacity: 0.5}, 300);
							});
							$(".close_header_popup").click(function(e) {
								$('.formalog').remove();
								$('.formalog-overlay').remove();
							});				
							$(".close_footer_popup").click(function(e) {
								$('.formalog').remove();
								$('.formalog-overlay').remove();
							});
						}
					}
				, 90000);
				
				$(document).mouseleave(function(e)
				{
					if(localStorage.getItem('formalogin') != 1)
					{
						localStorage.setItem('_ym_altOpvma21d', 0);
						localStorage.setItem('formalogin', 1);
						if(!($("div").is(".formalog-overlay") && $("div").is(".formalog")))
						{
							$('.formalog').remove();
							$('.formalog-overlay').remove();
							
							$('body').append(data);
							
							$('.formalog-overlay').fadeIn(1000,
							function(){
								$('.formalog').animate({opacity: 1, top: '15%'}, 300);
								$('.formalog-overlay').animate({opacity: 0.5}, 300);
							});
							
							$(".close_header_popup").click(function(e) {
								$('.formalog').remove();
								$('.formalog-overlay').remove();
							});				
							$(".close_footer_popup").click(function(e) {
								$('.formalog').remove();
								$('.formalog-overlay').remove();
							});
						}
					}
				});
			});			
		}
	});	
}
