$(document).ready(function(){
	//$('[title]').removeAttr('title');
	//alert(window.location);
	
	//$('body,html').animate({scrollTop: 0}, 0);
	
	
	
	var isMobile = {
		Android: function() {
			return navigator.userAgent.match(/Android/i);
		},
		BlackBerry: function() {
			return navigator.userAgent.match(/BlackBerry/i);
		},
		iOS: function() {
			return navigator.userAgent.match(/iPhone|iPad|iPod/i);
		},
		Opera: function() {
			return navigator.userAgent.match(/Opera Mini/i);
		},
		Windows: function() {
			return navigator.userAgent.match(/IEMobile/i);
		},
		any: function() {
			return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
		}
	};
	$('#upButton').click(function () {
		$('body,html').animate({
			scrollTop: 0
		}, 200);
		return false;
	});
	$("#PHONE").keydown(function(event) {
	// Разрешаем: backspace, delete, tab и escape
		if ( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 27 || 
			 // Разрешаем: Ctrl+A
			(event.keyCode == 65 && event.ctrlKey === true) || 
			 // Разрешаем: home, end, влево, вправо
			(event.keyCode >= 35 && event.keyCode <= 39)) {
				 // Ничего не делаем
				 return;
		}
		else {
			// Обеждаемся, что это цифра, и останавливаем событие keypress
			if ((event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
				event.preventDefault(); 
			}   
		}
	});
	
	$(".pagination_link").click(function () {
		$("#resAction").html("<div class='overload'><div class='load'></div></div>");
		$('html, body').animate({scrollTop: $('section').offset().top}, 500);
    });
	$(".confirm-view-prod").click(function () {
		$(".product-prd-list").slideToggle(300);
    });
	
	$('.detail-img, .detail-more-img, .product-detail .gift-el .img').slickLightbox();
	
	$(".search-ico").click(function () {
        //$(this).addClass("act");
		$("body").css("overflow", "hidden");
        $("#search-fixed-box").slideDown(300);
		$("#search-fixed-overlow").fadeIn(300);
		$('.search-form-box input').focus();
    });
	$(".close-search, #search-fixed-overlow").click(function () {
        //$('.search-ico').removeClass("act");
		$("body").removeAttr("style");
        $("#search-fixed-box").slideUp(300);
		$("#search-fixed-overlow").fadeOut(300);
    });
	$(".menu-fixed li").each(function(){
		if ($(this).is(':has(ul)')) {
			$(this).prepend('<i class="t-icon turn"></i>');
		}
	});
	$('.sidebar-filter .sidebar-filter-item .filt-elems ul').slimScroll({
		color: '#3a3a3a',
		height: '',
		maxHeight: 305,
		railColor : '#3a3a3a',
	});
	$('.index-rev-news .element .element-prop p').slimScroll({
		color: '#3a3a3a',
		height: '',
		maxHeight: 90,
		railColor : '#3a3a3a',
	});
	$(".sidebar-filter .sidebar-filter-item").each(function( index, element ) {
		if($( window ).width() >= 992){
			var strGet = window.location.search.replace( '?', '');
			var keyval = '';
			var params = '';
			if (index == 0 || index == 1) {
				$(element).children('.filter-head').children('i').removeClass('turn').addClass('expand');
				$(element).children('.filt-elems').addClass('open');
			}
			var id = $(element).children('.filter-head');
			if(strGet){
				params = strGet.split("&");
				for(var i = 0; i < params.length; i++) { 
					keyval = params[i].split("="); 
					keyvall = keyval[0].split("_"); 
					//alert($(id).attr('id'));
					if(keyvall[0] == $(id).attr('id')) { 
						var head = $(id).attr('id');
						//alert('#'+head);
						$('#'+head).children('i').removeClass('turn').addClass('expand');
						$('#'+head).siblings('.filt-elems').addClass('open');
					}
				}
			}
		}
	});
	
	/*if ($(".filt-size ul li").size()>0) {
		$(".filt-size ul li").each(function( index, element ) {
			if (index == 0) {
				$(element).children('.filt').click();
			}
		});
	}*/
	$(".review-add").click(function (){
		$('html, body').animate({scrollTop: $(".comment-add").offset().top}, 500);
	});
	$(".sidebar-menu li").each(function(){
		if ($(this).is(':has(ul)')) {
			$(this).prepend('<i class="t-icon turn"></i>');
		}
		if ($(this).is(':has(ul.open)')) {
			$(this).children('i').removeClass('turn').addClass('expand');
		}
		if ($(this).is(':has(ul.sidebar-drop2.open)')) {
			$(this).children('i').removeClass('turn').addClass('expand');
			$(this).parent('ul.sidebar-drop').addClass('open');
		}
	});
	$(".sidebar-filter-item .filter-head").each(function(){
		if ($(this).is(':has(ul)')) {
			$(this).prepend('<i class="t-icon-f turn"></i>');
		}
		if ($(this).is(':has(ul.sidebar-drop.open)')) {
			$(this).children('i').removeClass('turn').addClass('expand');
		}
	});
	$('.t-icon').click(function(){
		//var parid = $(this).parent().attr('id');
		//alert();
		if ($(this).siblings("ul").hasClass("open")) {
			$(this).removeClass('expand').addClass('turn');
			$(this).siblings("ul").slideUp(300).removeClass("open");
		} else {
			//$(".sidebar-menu-box .sidebar-drop").slideUp(300).removeClass("open");
			//$('.sidebar-menu-box li i').removeClass('expand').addClass('turn');
			//$('html, body').animate({scrollTop: $(".sidebar-menu").offset().top}, 300);
			$(this).removeClass('turn').addClass('expand');
			$(this).siblings("ul").slideDown(300).addClass("open");
		}
	});
	$('.t-icon-f').click(function(){
		if ($(this).hasClass("turn")) {
			$(this).removeClass('turn').addClass('expand');
			$(this).parent().siblings('.filt-elems').slideDown(300).addClass("open");

		} else {
			$(this).removeClass('expand').addClass('turn');
			$(this).parent().siblings('.filt-elems').slideUp(300).removeClass("open");
		}
	});
	$(".menu-ico").click(function () {
		
		if($( window ).width() > 510){
			$('.menu-fixed .menu').animate({width: "450px"}, 100);
		} else {
			$('.menu-fixed .menu').animate({width: $( window ).width()-30}, 100);
		}
		$('.menu-fixed').addClass("shown").animate({width: "100%"}, 300);
		$(".menu-fixed-overlow").fadeIn(300);
		$("body").css("overflow", "hidden");
    });
	$(".close-menu, .menu-fixed-overlow").click(function () {
		$('.menu-fixed, .menu-fixed .menu').animate({width: "0px"}, 300);
		setTimeout(function() { 
			$("body, .menu-fixed, .menu-fixed .menu").removeAttr("style"); 
			$('.menu-fixed').removeClass("shown");
		}, 300);
		$(".menu-fixed-overlow").fadeOut(300);
    });
	$('.search-form-box #bt_src').click(function() { 
		$("#resAction").html("<div class='overload'><div class='load'></div></div>");
	});	
		
	$('.filt').click(function() { 
		//$("#resAction").html("<div class='overload'><div class='load'></div></div>");
		var strNoGet = window.location.pathname;
		var strGet = window.location.search.replace( '?', '');
		var name = $(this).attr('name');
		var value = encodeURI($(this).val());
		var res = '';
		var new_url = '';
		var keyval = '';
		var params = '';
		var url = location.href;
		var zn = '';
		
		if($(this).is(':checked')) {  
			$(this).attr('checked','checked');
			params = strGet.split("&");
			for(var i = 0; i < params.length; i++) { 
				keyval = params[i].split("="); 
                if(keyval[0] != "PAGEN_1" && keyval[0] != "PRICE_MIN" && keyval[0] != "PRICE_MAX") { 
                	res += "&"+params[i];
                }
			}
			
			res = res.substr(1);
			//alert(res);
			if(res != '')
				new_url = strNoGet+"?"+res;
			else
				new_url = strNoGet;
				
			if(res != "")
			    zn = '&';
			else
			    zn = '?';
				
			var urldata = new_url+""+zn+""+name+"="+value;  
			window.history.pushState(null, null, urldata);
			//$('html, body').animate({scrollTop: $('.elements').offset().top}, 500);
			//location.reload();
		} else { 
			$(this).removeAttr('checked');
			params = strGet.split("&");
			for(var i = 0; i < params.length; i++) { 
				keyval = params[i].split("="); 
				//alert(decodeURIComponent(value.replace(/\+/g,  " ")));
                if(decodeURIComponent(keyval[1].replace(/\+/g,  " ")) !== decodeURIComponent(value.replace(/\+/g,  " ")) && keyval[0] != "PAGEN_1" && keyval[0] != "PRICE_MIN" && keyval[0] != "PRICE_MAX") { 
                	res += "&"+params[i];
                }
			}
			
			res = res.substr(1);
			//alert(res);
			if(res != '')
				new_url = "?"+res;
			else
				new_url = '';

			var urldata = strNoGet+""+new_url;
			window.history.pushState(null, null, urldata);
			//$('html, body').animate({scrollTop: $('.elements').offset().top}, 500);
			//location.reload();
		}
		//window.location.href = 'https://mirle.ru'+urldata;return false;
		//$(window).load(' https://mirle.ru'+urldata);
		//Временно не работает, причина: не совпадают товары при фильтрации и просто после перезагрузки страницы
		//alert(window.location.search.replace( '?', ''));
		var sect = parseInt($('#section_id').val());
		if(sect > 0){
			$.ajax({ 
				type: "POST",
				url: "/ajax/elementFilter.php",
				data: {SECTION: sect,GET_PARAM: window.location.search.replace( '?', '')},
				beforeSend: function(data){
					//$('.goods').append("<div class='overloadGoods'></div>");
					$("#resAction").html("<div class='overload'><div class='load'></div></div>");
				},
				success: function(data){
					//$('.goods').html(data);
					$('.goods-list').animate({'opacity':'0'},500);
					setTimeout(function() { 
						$('.goods-list').html(data);
						//$(".overloadGoods").fadeOut(500).delay(300).remove();
						$("#resAction").empty();
						$('html, body').animate({scrollTop: $('.elements').offset().top}, 500);
					}, 500);
					$('.goods-list').animate({'opacity':'1'},500);
	
				},
				error: function(data){
					$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
					setTimeout(function(){$('.alert_error_add_item').remove()},5000);
				}
			});
		}
	});
	
	
	$(".openModal").click(function(){
		var id_el = $(this).attr('data-id');
		$(".modalBox").attr('id',id_el);
		$(".modalBox .close, .modalOver").attr('data-id',id_el);
		//$('#'+id_el+' .cont-mod .row').empty();
		/*if(id_el == 'elementSynopsis'){
			$(".modalBox .box-name-win").text('Быстрый просмотр товара');
			$(".modalBox .box-title strong").text('Постельное белье "Ночная сакура"');
			$.getJSON("/ajax/element-synopsis.php", {ELEMENT_ID: ID}, function(data){
		
			});
		}*/
		$("#"+id_el).addClass('shown');
		$(".modalOver").fadeIn(100);
		locationModal();
	});
	$(".close,.modalOver").click(function(){
		var id_el = $(this).attr('data-id');
		$("#"+id_el).removeClass('shown');
		$(".modalOver").fadeOut(100);
		$("#"+id_el).css({
			top: 0
		});
		//$("body").removeAttr("style"); 
	});
	
	$(".sortes").click(function(){
		if(!$("#section_menu").hasClass('shown')){
			$("#section_menu").addClass('shown');
			$("#section_menu_color").removeClass('shown');
		}else{
			$("#section_menu").removeClass('shown');
		}
	});
	$(".sortes-color").click(function(){
		if(!$("#section_menu_color").hasClass('shown')){
			$("#section_menu_color").addClass('shown');
			$("#section_menu").removeClass('shown');
		}else{
			$("#section_menu_color").removeClass('shown');
		}
	});
	$(".department .view-more").click(function(){
		$(".department .elem").each(function(index, element ){
			$(element).slideDown(300).delay(300).removeClass('hidd');
		});
		$(".department .img  img").each(function( index, element ) {
			$(element).css({
				height: $(element).outerWidth()
			});
		});
		$(this).slideUp(300);
		$("img.responsive-img").lazyload({threshold : 0,failurelimit : 6,effect: "fadeIn"});
	});

	//Смена товаров на главной
	if($( window ).width() >= 768 && window.location.pathname == "/"){
		setInterval(function() { 
			indexAdvertising();
		}, 15000);
	}
	if ($("#carousel-new, #carousel-sale, #carousel-similar").size()>0) {
		$("#carousel-new, #carousel-sale, #carousel-similar").slick({
			dots: false,
			infinite: false,
			speed: 500,
			slidesToShow: 4,
			slidesToScroll: 1,
			autoplaySpeed: 5000,
			autoplay: true,
			arrows: false,
			prevArrow: '<div class="carousel-prev"></div>',
			nextArrow: '<div class="carousel-next"></div>',
			adaptiveHeight: true,
			responsive: [
			{
			  breakpoint: 1250,
			  settings: {
				slidesToShow: 4,
			  }
			},
			{
			  breakpoint: 992,
			  settings: {
				slidesToShow: 3,
			  }
			},
			{
			  breakpoint: 768,
			  settings: {
				slidesToShow: 2,
			  }
			},
			{
			  breakpoint: 480,
			  settings: {
				slidesToShow: 1,
			  }
			}
		  ]
		});
	};
	if ($(".index-slids").size()>0) {
		$(".index-slids").slick({
			dots: true,
			infinite: false,
			speed: 1000,
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplaySpeed: 5000,
			autoplay: true,
			arrows: false,
			adaptiveHeight: true
		});
	};
	if ($(".detail-more-img").size()>0) {
		$(".detail-more-img").slick({
			dots: false,
			infinite: false,
			speed: 500,
			slidesToShow: 4,
			slidesToScroll: 1,
			autoplaySpeed: 5000,
			autoplay: true,
			adaptiveHeight: true
		});
	};
	if ($(".more_photo").size()>0) {
		$(".more_photo").slick({
			dots: true,
			infinite: false,
			speed: 500,
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplaySpeed: 5000,
			autoplay: true,
			adaptiveHeight: true,
			arrows: false
		});
	};

	if(!isMobile.any()){
		$(".mask").mask("8(999)999-99-99");
	}
	$(".mask-date").mask("99.99.9999");
	$(".mask-card").mask("9999-9999-9999-9999");
	$(".mask-onlinepay").mask("99999999");

	$('#PASS_REG').keyup(function(){
	  	var value = $(this).val().length;
	  	if(value > 5){
	  		$(this).addClass('success-border');
		} else {
			$(this).removeClass('success-border');
		}
	});
	$('#REPASS_REG').keyup(function(){
		if($(this).val() == $('#PASS_REG').val()){
			$(this).addClass('success-border');
		} else {
			$(this).removeClass('success-border');
		}
	});

	//alert(arCity.length);
	//$('body').html().replace("руб.", "&#8381;");
	//priceZnak('.price');
	//alert($( window ).height())
	
	if(window.location.pathname == "/"){
		elementScroll('body');
	}
	if(window.location.pathname != "/cart/" && window.location.pathname != "/cart/cart-prop/"){
		elementBasketAddDeliveryList();
	}
	
	$("img.responsive-img").lazyload({threshold : 0,failurelimit : 6,effect: "fadeIn"});
});
$(document).mouseup(function (e) {
	var container = $("#section_menu, #section_menu_color");
	if (container.has(e.target).length === 0){
		container.removeClass('shown');
	}
});
$(window).resize(function(){
	
	var top = $(document).scrollTop();
	/*$('.product-img img').css({
		height: $('.product-img img').outerWidth()
	});*/
	$(".product-img img").each(function( index, element ) {
		$(element).css({
			height: $(element).outerWidth()
		});
	});
	$(".index-rev-news img").each(function( index, element ) {
		$(element).css({
			height: $(element).outerWidth()
		});
	});
	$(".news-list img").each(function( index, element ) {
		$(element).css({
			height: $(element).outerWidth()
		});
	});
	$(".department .img  img").each(function( index, element ) {
		$(element).css({
			height: $(element).outerWidth()
		});
	});
	$(".reviews-product img").each(function( index, element ) {
		$(element).css({
			height: $(element).outerWidth()
		});
	});
	
	if($( window ).width() <= 992){
		$(".subModalBox").removeClass('shown').css({top: 0});
		$(".subModalOver").fadeOut(100);
		//if($.cookie('subPromoWindow'))
		    //$.cookie('subPromoWindow', 'Y');
		//$("body").removeAttr("style");
	}
	
	
	/*if($( window ).width() <= 992){
		$(".index-slide img").fadeOut(300);
		var src = $(".index-slide img").attr('src');
		//alert(src);
		$('.index-slide').css({
			backgroundImage: "url("+src+")",
			backgroundRepeat: "no-repeat",
			backgroundPosition: "center center",
			backgroundSize: "cover",
			height: "350px"
		});
	}else{
		$(".index-slide img").fadeIn(300);
		$(".index-slide").removeAttr('style');
	}*/
	
	
	$('.menu-fixed').css({width: "100%"});
	if($( window ).width() > 510){
		$('.menu-fixed .menu').css({width: "450px"});
	}else{
		$('.menu-fixed .menu').css({width: "auto"});
	}
	
	if($( window ).width() <= 992){
		$(".modalOverS").trigger('click');
	}
	locationModal();
	
	
	if($( window ).width() > 992){
		$(".sidebar-filter .sidebar-filter-item").each(function( index, element ) {
			if (index == 0 || index == 1) {
				$(element).children('.filter-head').children('i').removeClass('turn').addClass('expand');
				$(element).children('ul').addClass('open');
			}
		});
	} else {
		$(".sidebar-filter .sidebar-filter-item").each(function( index, element ) {
			$(element).children('.filter-head').children('i').removeClass('expand').addClass('turn');
			$(element).children('ul').removeClass('open');
		});
	}
	if($( window ).width() > 768){
		$('#elementSynopsis .property .item, .slimScrollDiv').css({
			maxHeight: $('#elementSynopsis .picture').outerHeight() -30
		});
	} else {
		$('#elementSynopsis .property .item, .slimScrollDiv').css({
			maxHeight: "none"
		});
	}
	if($( window ).width() <= 769){
	    $('.index-advertising').css({
			height: ($('.el-advert').outerHeight()*3+15)
		});
	} else {
		$('.index-advertising').css({
			height: ($('.el-advert').outerHeight()+15)
		});
    }
});
$(window).scroll(function() {
		
});
$(window).load(function(){
	//$(window).resize();
	/*$('.product-img img').css({
		height: $('.product-img img').outerWidth()
	});*/
	$(".product-img img").each(function( index, element ) {
		$(element).css({
			height: $(element).outerWidth()
		});
	});
	$(".index-rev-news img").each(function( index, element ) {
		$(element).css({
			height: $(element).outerWidth()
		});
	});
	$(".news-list img").each(function( index, element ) {
		$(element).css({
			height: $(element).outerWidth()
		});
	});
	$(".department .img  img").each(function( index, element ) {
		$(element).css({
			height: $(element).outerWidth()
		});
	});
	$(".reviews-product img").each(function( index, element ) {
		$(element).css({
			height: $(element).outerWidth()
		});
	});
	
	
	if($.cookie('subPromoWindow') == null && $( window ).width() > 992){
		setTimeout(function(){
			subPromoWindow();
		},10000);
	}
	//$.cookie('subPromoWindow', '15223');
	if(parseInt($.cookie('subPromoWindow'))>0 && $( window ).width() > 992){
		subPromoTopWindow($.cookie('subPromoWindow'));
	}
	
	/*if($( window ).width() <= 992){
		$(".index-slide img").fadeOut(300);
		var src = $(".index-slide img").attr('src');
		//alert(src);
		$('.index-slide').css({
			backgroundImage: "url("+src+")",
			backgroundRepeat: "no-repeat",
			backgroundPosition: "center center",
			backgroundSize: "cover",
			height: "350px"
		});
	}else{
		$(".index-slide img").fadeIn(300);
		$(".index-slide").removeAttr('style');
	}*/
	/*$('.sidebar-filter-item ul').slimScroll({
		color: '#3a3a3a',
		height: '',
		maxHeight: 350,
		railColor : '#3a3a3a',
	});*/
});
/*function priceZnak(BOX){
	var el = $(BOX);
	el.html(el.html().replace(/р./ig, "<i class='r'>i</i>"));
};*/
function locationModal(){
	if($(window).width() > $('.modalBox.shown').outerWidth() || $(window).width() > 1000){
		$('.modalBox.shown').css({
			left: ($(window).width() - $('.modalBox.shown').outerWidth())/2,
			top: (($(window).height() - $('.modalBox.shown').outerHeight())/2)+$(document).scrollTop()
		});
	}else{
		$('.modalBox.shown').css({
			width: "auto",
			top: (($(window).height() - $('.modalBox.shown').outerHeight())/2)+$(document).scrollTop()
		});
	}
};
function locationModalSynopsis(){
	if($(window).width() > $('#elementSynopsis').outerWidth() || $(window).width() > 1000){
		$('#elementSynopsis').css({
			left: ($(window).width() - $('#elementSynopsis').outerWidth())/2,
			top: (($(window).height() - $('#elementSynopsis').outerHeight())/2)+$(document).scrollTop()
		});
	}else{
		$('#elementSynopsis').css({
			left: "auto",
			top: (($(window).height() - $('#elementSynopsis').outerHeight())/2)+$(document).scrollTop()
		});
	}
};
function resetCeche(){
	$.ajax({
		type: "POST",
		url: "/ajax/resetCeche.php",
		data: {r: "Y"},
		beforeSend: function(data){
			$("#resAction").html("<div class='overload'><div class='load'></div></div>");
		},
		success: function(data){
			$("#resAction").html(data);
		},
		error: function(data){
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});
}
function number_format(number, decimals, dec_point, thousands_sep) {
	  number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	  var n = !isFinite(+number) ? 0 : +number,
		prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
		sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
		dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
		s = '',
		toFixedFix = function(n, prec) {
		  var k = Math.pow(10, prec);
		  return '' + (Math.round(n * k) / k)
			.toFixed(prec);
		};
	  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
	  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
		.split('.');
	  if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	  }
	  if ((s[1] || '')
		.length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1)
		  .join('0');
	  }
	  return s.join(dec);
};
function indexAdvertising(){
	$.getJSON("/ajax/euro/elementIndexAdvertising.php", function(data){
		//alert(data.ITEMS.length);
		if(data.ITEMS.length == 3){
    		if($( window ).width() <= 769){
    		    $('.index-advertising').css({
        			height: ($('.el-advert').outerHeight()*3+15)
        		});
    		} else {
        		$('.index-advertising').css({
        			height: ($('.el-advert').outerHeight()+15)
        		});
		    }
    		var src_res="";
    		var res="";
    		for (i = 0; i < data.ITEMS.length; i++) {
    			src_res+="<div class='col-lg-4 col-md-4 col-sm-4 col-xs-12 el-advert'>";
    				src_res+="<a href='"+data.ITEMS[i].DETAIL_PAGE_URL+"' class='advert'>";
    					if(data.ITEMS[i].AKCII_XML_ID)
							src_res+="<div class='status-goods "+data.ITEMS[i].AKCII_XML_ID+"'><span>"+data.ITEMS[i].AKCII+"</span><i></i></div>";
						
						src_res+="<div class='img'>";
    						src_res+="<span>Посмотреть сейчас</span>";
    						src_res+="<img src='"+data.ITEMS[i].PREVIEW_PICTURE+"' alt='"+data.ITEMS[i].NAME+"' class='responsive-img'>";
    					src_res+="</div>";
    					src_res+="<p>"+data.ITEMS[i].NAME+"</p>";
    				src_res+="</a>";
    			src_res+="</div>";
    		}
    		src_res+="<div class='clear'></div>";
            
    		$('.index-advertising').animate({'opacity':'0'},1000);
    		setTimeout(function() { 
    			$('.index-advertising').html(src_res);
    		}, 1000);
    		$('.index-advertising').animate({'opacity':'1'},1000);
		}
	});
	//window.history.pushState(null, null, window.location);
};
function elementSynopsis(ID){
	$('#q-'+ID).text("Подождите...");
	$.getJSON("/ajax/euro/elementSynopsis.php", {ELEMENT_ID: ID}, function(data){
		var view = '';
		if(data.ID){
			//alert(data.ID);
			var src_res="";
			src_res+="<a href='javascript:void(0)' class='close-s'></a>";
			src_res+="<div id='elementSynopsis'>";
				src_res+="<div class='picture'>";
					src_res+="<div class='img'>";
						src_res+="<a href='"+data.DETAIL_PAGE_URL+"'><img src='"+data.DETAIL_PICTURE+"' class='responsive-img'></a>";
					src_res+="</div>";
					src_res+="<div class='img-prop'>";
						src_res+="<a href='javascript:void(0)' onClick='elementLikes("+data.ID+")' class='icon likes-prod' title='Нажмите, если Вам нравится этот товар'><strong>"+data.LIKES+"</strong> нравится</a>";
						src_res+="<a href='"+data.DETAIL_PAGE_URL+"#commentProduct' class='icon comm-prod' title='Отзывы'><strong>"+data.COMMENT+"</strong></a>";
					src_res+="</div>";
				src_res+="</div>";
				src_res+="<div class='property'>";
					src_res+="<div class='item'>";
						src_res+="<h2 class='name'>"+data.NAME+"</h2>";
						
						src_res+="<div class='detail-property'>";
							if(data.MANUFACTURER){
							src_res+="<div class='prop'>";
								src_res+="<p class='key-array'>Производитель:</p>";
								src_res+="<p class='res-array'>";
									src_res+="<a href='"+data.MANUFACTURER_DETAIL_PAGE_URL+"' target='_blank'><strong>"+data.MANUFACTURER+"</strong></a>";
								src_res+="</p>";
							src_res+="</div>";
							}
							if(data.MANUFACTURER_COUNTRY){
							src_res+="<div class='prop'>";
								src_res+="<p class='key-array'>Страна:</p>";
								src_res+="<p class='res-array'><strong>"+data.MANUFACTURER_COUNTRY+"</strong></p>";
							src_res+="</div>";
							}
							if(data.SIZE){
							src_res+="<div class='prop'>";
								src_res+="<p class='key-array'>Размер:</p>";
								src_res+="<p class='res-array'><strong>"+data.SIZE+"</strong></p>";
							src_res+="</div>";
							}
							if(data.MATERIAL){
							src_res+="<div class='prop'>";
								src_res+="<p class='key-array'>Материал:</p>";
								src_res+="<p class='res-array'><strong>"+data.MATERIAL+"</strong></p>";
							src_res+="</div>";
							}
							if(data.DRAWING){
							src_res+="<div class='prop'>";
								src_res+="<p class='key-array'>Рисунок:</p>";
								src_res+="<p class='res-array'><strong>"+data.DRAWING+"</strong></p>";
							src_res+="</div>";
							}
							if(data.COLOR_NAME){
							src_res+="<div class='prop'>";
								src_res+="<p class='key-array'>Цвет <span>( "+data.COLOR_NAME+" )</span></p>";
								src_res+="<p class='res-array'>";
									src_res+="<strong style='color:"+data.COLOR+"'>"+data.COLOR_NAME+"</strong>";
								src_res+="</p>";
							src_res+="</div>";
							}
						src_res+="</div>";
						
						src_res+="<div class='detail-price'>";
							src_res+="<div class='price'>Цена: <strong class='bender-font'>"+data.PRICE_BASE+"</strong></div>";
							if(!data.INBASKET_CNT){
								src_res+="<div class='price itog'>Итого: <strong id='pp' class='bender-font'>"+data.PRICE+"</strong></div>";
								data.INBASKET_CNT = 1;
							} else {
								src_res+="<div class='price itog'>Итого: <strong id='pp' class='bender-font'>"+data.PRICE_BASKET+"</strong></div>";
							}
						src_res+="</div>";
						
						src_res+="<div class='btn-box'>";
							if(data.QUANTITY > 0){
								src_res+="<div class='quantity'>";
									src_res+="<a href='javascript:void(0);' onclick=\"minus('quantity','"+data.PRICE_INT+"',0)\" class='minus'><strong><i class='glyphicon glyphicon-minus'></i></strong></a>";
									src_res+="<input class='counter' id='quantity' readonly='readonly' name='quantity' value='"+data.INBASKET_CNT+"' type='text'>";
									src_res+="<a href='javascript:void(0);' onclick=\"plus('quantity','"+data.PRICE_INT+"','"+data.QUANTITY+"',0)\" class='plus'><strong><i class='glyphicon glyphicon-plus'></i></strong></a>";
								src_res+="</div>";
								src_res+="<div class='add-buy'>";
									src_res+="<a href='javascript:void(0);' rel='nofollow' id='btn-adds-"+data.ID+"' onclick='yaCounter43070549.reachGoal(\"ADD_BASKET\"); basketElementAdd("+data.ID+",quantity.value); return false;'>"+data.INBASKET+"</a>";
								src_res+="</div>";
							} else {
								src_res+="<div class='not-available'>";
									src_res+="<a href='javascript:void(0);' rel='nofollow'"+data.INSUB_ONCLC+" id='btn-avs-"+data.ID+"'>"+data.INSUB+"</a>";
								src_res+="</div>";
							}
						src_res+="</div>";
						
						src_res+="<div class='detail-text'>";
							src_res+=data.DETAIL_TEXT;
						src_res+="</div>";
						
					src_res+="</div>";
				src_res+="</div>";
				src_res+="<div class='clear'></div>";
			src_res+="</div>";
			src_res+="<div class='modalOverS'></div>";
			
			$('#resActionHidden').html(src_res);

			//priceZnak('.detail-price .price, .detail-price #pp');
			setTimeout(function(){
				/*$("#elementSynopsis").css({
					maxHeight: $('#elementSynopsis img').outerHeight()+40
				});*/
				
				
				$(".modalOverS").attr('title','Закрыть окно').css({cursor: 'pointer'});
				$(".modalOverS, #elementSynopsis").fadeIn(300);
				
				var hh = '';
				if($('#elementSynopsis img').outerHeight() > $('#elementSynopsis').outerHeight()){
					hh = $('#elementSynopsis').outerHeight();
					$('#elementSynopsis .img').slimScroll({
						color: '#3a3a3a',
						height: '',
						maxHeight: $('#elementSynopsis').outerHeight()-40,
						railColor : '#3a3a3a',
						distance:'0px'
					});
				}else{
					hh = $('#elementSynopsis img').outerHeight()+40;
				}
				
				
				$('#elementSynopsis .property .item').slimScroll({
					color: '#3a3a3a',
					height: '',
					maxHeight: hh,
					railColor : '#3a3a3a',
					borderRadius:'3px',
					railBorderRadius:'3px',
					distance:'0px'
				});
				
				locationModalSynopsis();
				$('.close-s').css({
					top: $(document).scrollTop()+15
				});
				$('#q-'+ID).text("Быстрый просмотр");
			},1000);

			$(".close-s, .modalOverS").click(function(){
				$("#elementSynopsis, .modalOverS").fadeOut(300);
				setTimeout(function(){$('#resActionHidden').empty();},300);
			});
		} else {
			$('#q-'+ID).text("Быстрый просмотр");
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);	
		}
	});
	//window.history.pushState(null, null, window.location);
}
function sendData(URL,FORM_ID,RES_ID,BTN_ID,BTN_TEXT){
	$.ajax({
		type: "POST",
		url:  "/ajax/euro/"+URL,
		data: $("#"+FORM_ID).serialize(),
		beforeSend: function(data){
			if(FORM_ID == "subscribeForm"){
				$("#"+BTN_ID+" strong").html('<i class="glyphicon glyphicon-send" aria-hidden="true"></i>');
			} else {
				$("#"+BTN_ID+" strong").text("Подождите...");
			}
		},
		success: function(data){
			$("#"+RES_ID).html(data);
		},
		error: function(data){
			$("#"+BTN_ID+" strong").text(BTN_TEXT);
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){
				$('.alert_error_add_item').slideUp(300);
				$("#resAction, #"+RES_ID).empty();
			},5000);
		}
	});
	//window.history.pushState(null, null, window.location);
};
function elementSize(ID){
	//alert(ID);
	$.ajax({
		type: "POST",
		url: "/ajax/euro/elementSize.php",
		data: {ELEMENT_ID: ID},
		beforeSend: function(data){
			$("#resAction").html("<div class='overload'><div class='load'></div></div>");
		},
		success: function(data){
			setTimeout(function() { 
				$('.product-detail').html(data);
				$("#resAction").empty();
			}, 500);
			$('.product-detail').animate({'opacity':'0'},500);
			$('.product-detail').animate({'opacity':'1'},500);
		},
		error: function(data){
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});
}
function elementLikes(ID){
	$.ajax({
		type: "POST",
		url: "/ajax/euro/elementLikes.php",
		data: {ELEMENT_ID: ID},
		success: function(data){
			$("#resAction").html(data);
		},
		error: function(data){
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});
	//window.history.pushState(null, null, window.location);
}
function minus(FIELD_ID,PRICE,BASKET_ID){
	var currentVal = parseInt($('#'+FIELD_ID).val());
	if ( !isNaN(currentVal) && currentVal > 1 ) {
		var curval = currentVal - 1;
		$('#'+FIELD_ID).val(curval);
		if(BASKET_ID > 0){
			//alert(basket);
			$.ajax({
				type: "POST", 
				url:  "/ajax/euro/elementBasketPM.php",
				data: {
					BASKET_ID: BASKET_ID,
					QUANTITY: curval
				}, 
				success: function(data){
					$("#basket").html(data);
				}
			});
		}
		if(PRICE > 0 && BASKET_ID == 0){
			var dec_point = ".";
			var thousands_sep = " ";
			var pp = PRICE*curval;
			var decimals = 0;
			var itog = number_format(pp, decimals, dec_point, thousands_sep);
			$('.price #pp, #ppclk').html(itog+' <span class="rouble">P<i>-</i></span>');//<i class="r">i</i>
		}
	}
	//window.history.pushState(null, null, window.location);
}
function plus(FIELD_ID,PRICE,MAX_Q,BASKET_ID){
	var currentVal = parseInt($('#'+FIELD_ID).val());
	if ( !isNaN(currentVal) ) {
		var curval = currentVal + 1;
		$('#'+FIELD_ID).val(curval);
		if(BASKET_ID > 0){
			//alert(basket);
			$.ajax({
				type: "POST", 
				url:  "/ajax/euro/elementBasketPM.php",
				data: {
					BASKET_ID: BASKET_ID,
					QUANTITY: curval
				}, 
				success: function(data){
					$("#basket").html(data);
				}
			});
		}
		if(PRICE > 0 && BASKET_ID == 0){
			var dec_point = ".";
			var thousands_sep = " ";
			var decimals = 0;
			var pp = '';
			var itog = '';
			if(MAX_Q >= curval){
				pp = PRICE*curval;
				itog = number_format(pp, decimals, dec_point, thousands_sep);
				$('.itog #pp, #ppclk').html(itog+' <span class="rouble">P<i>-</i></span>');
			} else {
				pp = PRICE*MAX_Q;
				itog = number_format(pp, decimals, dec_point, thousands_sep);
				$('.itog #pp, #ppclk').html(itog+' <span class="rouble">P<i>-</i></span>');
				$('#'+FIELD_ID).val(MAX_Q);
				$('#resAction').html('<div class="alert_error_add_item">Достигнут предел кол-ва добавляемого товара!</div>');
				setTimeout(function(){$('.alert_error_add_item').slideUp(300)},5000);
			}
		}
	}
	//window.history.pushState(null, null, window.location);
}
function elementFavorites(LIKE_ID,ID,ACTION){
	$.ajax({
		type: "POST",
		url: "/ajax/euro/elementFavorites.php",
		data: {ELEMENT_ID: ID, ACTION: ACTION, LIKE_ID: LIKE_ID},
		success: function(data){
			$("#resAction").html(data);
		},
		error: function(data){
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});
	//window.history.pushState(null, null, window.location);
}
function elementScroll(FIELD_ID){
	$('html, body').animate({scrollTop: $(FIELD_ID).offset().top}, 500);
}
function basketElementAdd(ID,QUANTITY){
	
	if(!QUANTITY)
		QUANTITY = 1;
	$.ajax({
		type: "POST", 
		url:  "/ajax/euro/elementBasketAdd.php",
		data: {
			PRODUCT_ID: ID,
			QUANTITY: QUANTITY
		}, 
		beforeSend: function(data){
			$("#btn-add-"+ID+", #btn-adds-"+ID).text("Подождите...");
		},
		success: function(data){
			$("#resAction").html(data);
		},
		error: function(data){
			$("#btn-add-"+ID+", #btn-adds-"+ID).text("В корзину");
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});
	//window.history.pushState(null, null, window.location);
}

function subscribeElementAdd(ID){
	$("#btn-av-"+ID+", #btn-avs-"+ID).text("Подождите...");
	$.getJSON("/ajax/euro/elementSubscribeInfo.php", {ELEMENT_ID: ID}, function(data){
		if(data.ID){
			$(".modalBox").attr('id',"subElement");
			$(".modalBox .close, .modalOver").attr('data-id',"subElement");
			$(".modalBox .box-name-win").text('Сообщить о поступлении');
			$(".modalBox .box-title strong").text(data.NAME);
			
			var src_res='<form action="javascript:void(0);" method="post" id="elementSubForm">';
				src_res+='<input name="ID_ITEM_S" id="ID_ITEM_S" value="'+data.ID+'" type="hidden">';
				src_res+='<div class="col-sm-6 col-xs-12">';
					src_res+='<input name="FIO_S" id="FIO_S" placeholder="Ваше имя*" value="'+data.USER_NAME+'" type="text">';
					src_res+='<input name="MAIL_S" id="MAIL_S" placeholder="Ваша эл. почта*" value="'+data.USER_EMAIL+'" type="text">';
					src_res+='<input name="PHONE_S" id="PHONE_S" class="mask" placeholder="Ваш номер телефона 8(___)_______" value="" type="tel">';
				src_res+='</div>';
				src_res+='<div class="col-sm-6 col-xs-12">';
					src_res+='<textarea name="TEXT_S" id="TEXT_S" placeholder="Текст сообщения"></textarea>';
				src_res+='</div>';
				src_res+='<div class="col-xs-12">';
					
					src_res+='<div class="sog">Нажимая на кнопку <strong>"Отправить"</strong>, вы даете согласие на обработку персональных данных <a href="/legal/" target="_blank">в соответствии с условиями</a></div>';
					src_res+='<button name="submit" type="button" class="add-element" id="btnSub" onclick="javascript:sendData(\'elementSubscribeAdd.php\',\'elementSubForm\',\'resAction\',\'btnSub\',\'Отправить\'); return false;"><strong>Отправить</strong></button>';
				src_res+='</div>';
				src_res+='<div class="clear"></div>';
			src_res+="</form>";
			
			$('.modalBox .cont-mod').html(src_res).addClass('row');
			
			
			$(".modalBox").addClass('shown');
			$(".modalOver").fadeIn(100);
			locationModal();
			$("#btn-av-"+ID+", #btn-avs-"+ID).text("Оповестить");
			$(".close, .modalOver").click(function(){
				$("#subElement").removeClass('shown');
				$(".modalOver").fadeOut(100);
				$('.modalBox .cont-mod').removeClass('row');
			});
			$(".mask").mask("8(999)999-99-99");
			
		}else{
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
			$("#btn-av-"+ID+", #btn-avs-"+ID).text("Оповестить");
		}
	});
	//window.history.pushState(null, null, window.location);
}
function elementBasketDel(ID){
	if(ID){
		$.ajax({
			type: "POST", 
			url:  "/ajax/euro/elementBasketDel.php",
			data: {
				BASKET_ID: ID
			}, 
			beforeSend: function(data){
				$("#btn-del-"+ID).html('<i class="glyphicon glyphicon-trash"></i> Подождите...');
			},
			success: function(data){
				$("#basket").html(data);
			},
			error: function(data){
				$("#btn-del-"+ID).html('<i class="glyphicon glyphicon-trash"></i> Удалить');
				console.log(data);
				$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
				setTimeout(function(){$('.alert_error_add_item').remove()},5000);
			}
		});
	} else {
		$("#btn-del-"+ID).html('<i class="glyphicon glyphicon-trash"></i> Удалить');
		console.log(data);
		$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
		setTimeout(function(){$('.alert_error_add_item').remove()},5000);
	}
	//window.history.pushState(null, null, window.location);
}
function basketPropertyFormSession(){
	$.ajax({
		type: "POST",
		url:  "/ajax/euro/basketPropertySession.php",
		data: $("#orderForm").serialize(),
		beforeSend: function(data){
			$("#resAction").html("<div class='overload'><div class='load'></div></div>");
		},
		success: function(data){
			//$("#basket").html(html);
			location.reload();
		}
	});
	//window.history.pushState(null, null, window.location);
}
function basketSearchCity(CITY){
	$.ajax({
		type: "POST",
		url:  "/ajax/euro/basketSearchCity.php",
		data: {
			CITY: CITY
		}, 
		success: function(data){
			$("#resAction").html(data);
		}
	});
	//window.history.pushState(null, null, window.location);
}
function orderOneClickBasket(){
	$(".modalBox").attr('id',"orderAddOneClk");
	$(".modalBox .close, .modalOver").attr('data-id',"orderAddOneClk");
	$(".modalBox .box-name-win").text('');
	$(".modalBox .box-title strong").text('Заказ в один клик');
	
	var src_res='<form action="javascript:void(0);" method="post" id="orderAddOneClkForm">';
		src_res+='<input name="FIO_O" id="FIO_O" placeholder="Ваше имя*" value="" type="text">';
		src_res+='<input name="PHONE_O" id="PHONE_O" class="mask" placeholder="Ваш номер телефона 8(___)_______ *" value="" type="tel">';
		src_res+='<div class="sog">Нажимая на кнопку <strong>"Оформить заказ"</strong>, вы соглашаетесь с условиями продажи товаров и даете согласие на обработку персональных данных <a href="/legal/" target="_blank">в соответствии с условиями.</a></div>';
		src_res+='<button name="submit" type="button" class="add-element" id="btnClk" onclick="javascript:sendData(\'orderAddOneClk.php\',\'orderAddOneClkForm\',\'resAction\',\'btnClk\',\'Оформить заказ\'); return false;"><strong>Оформить заказ</strong></button>';
		
	src_res+="</form>";
	
	$('.modalBox .cont-mod').html(src_res);
	$(".modalBox").addClass('shown');
	$(".modalOver").fadeIn(100);
	locationModal();
	$(".close, .modalOver").click(function(){
		$("#orderAddOneClk").removeClass('shown');
		$(".modalOver").fadeOut(100);
	});
	$(".mask").mask("8(999)999-99-99");
	//window.history.pushState(null, null, window.location);
}
function userOrderDetail(ID){
	$.ajax({
		type: "POST",
		url: "/ajax/euro/userOrderDetail.php",
		data: {
			ORDER_ID: ID
		},
		success: function(data){
			$('.detail').slideUp(300).empty();
			$(".list").slideDown(300);
			$('#elOrder_'+ID).slideUp(300);
			$("#elOrderD_"+ID).html(data).delay(300).slideDown(300);
			//$('html, body').animate({scrollTop: $("#elOrderD_"+ID).offset().top}, 300);
		},
		error: function(data){
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});
	//window.history.pushState(null, null, window.location);
}
function elementFavoritesDel(ID){
	$.ajax({
		type: "POST",
		url: "/ajax/euro/elementFavoritesDel.php",
		data: {
			ELEMENT_ID: ID
		},
		success: function(data){
			$("#resAction").html(data);
		},
		error: function(data){
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});
	//window.history.pushState(null, null, window.location);
}
function modifAjaxPagen(PAGE,NUM){
	//alert(NUM);
	$("#resAction").html("<div class='overload'><div class='load'></div></div>");
	$('html, body').animate({scrollTop: $('section').offset().top}, 500);
	var ress = '';
	var strGet = window.location.search.replace( '?', '');
	var params = strGet.split("&");
	
	for(var i = 0; i < params.length; i++) { 
		keyval = params[i].split("="); 
		if(keyval[0] !== PAGE) { 
			ress += "&"+params[i];
		}
	}
	ress = ress.substr(1);

	var strNoGet = window.location.pathname;
	var new_url = '';
	
	if(ress != ''){
		if(NUM > 1)
			new_url = "?"+ress+"&"+PAGE+"="+NUM;
		else
			new_url = "?"+ress;
	}else{
		if(NUM > 1)
			new_url = "?"+PAGE+"="+NUM;
	}
	window.history.pushState(null, null, strNoGet+""+new_url);	
	setTimeout(window.location.href = strNoGet+""+new_url, 10);
}
function sortSect(S,PARAM,SORT,NAME){
	//alert(NAME);
	if(NAME){
		$("#resAction").html("<div class='overload'><div class='load'></div></div>");
		$('.sortes').text(NAME);
		var name = $(this).attr('id');
		var ress = '';
		var strGet = window.location.search.replace( '?', '');
		var strNoGet = window.location.pathname;
		var params = strGet.split("&");
		for(var i = 0; i < params.length; i++) { 
			keyval = params[i].split("="); 
			if(keyval[0] !== "price" && keyval[0] !== "stock" && keyval[0] !== "sort") { 
				ress += "&"+params[i];
			}
		}
		//alert(ress);
		ress = ress.substr(1);
		if(ress != ''){
			if(PARAM != "sort")
				new_url = "?"+ress+"&"+PARAM+"="+SORT;
			else
				new_url = "?"+ress;
		}else{
			if(PARAM != "sort")
				new_url = "?"+PARAM+"="+SORT;
			else
				new_url = "";
		}
		//window.history.pushState(null, null, strNoGet+""+new_url);
		setTimeout(window.location.href = location.pathname+""+new_url, 10);
	}
}
function userOrderParam(ID,URL){
	$.ajax({
		type: "POST",
		url: "/ajax/euro/"+URL,
		data: {
			ORDER_ID: ID
		},
		beforeSend: function(data){
			$("#resAction").html("<div class='overload'><div class='load'></div></div>");
		},
		success: function(data){
			$("#resAction").html(data);
		},
		error: function(data){
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});
	//window.history.pushState(null, null, window.location);
}
function searchDelivery(){
	var strNoGet = window.location.pathname;
	var city = $('#ORDER_PROP_4').val();
	if(city){
		var urldata = strNoGet+"?city="+city+"#result_delivery";
		$("#resAction").html("<div class='overload'><div class='load'></div></div>");
		window.history.pushState(null, null, urldata);
		location.reload();
	}else{
		$("#resAction").html($("<div class='alert_error_add_item'>Выберите город! Повторите попытку.</div>").slideDown(200));
		setTimeout(function(){$('.alert_error_add_item').remove()},5000);
	}
	//window.history.pushState(null, null, window.location);
	/*$.ajax({
		type: "POST",
		url: "/ajax/euro/searchDelivery.php",
		data: {
			CITY: $('#ORDER_PROP_4').val()
		},
		beforeSend: function(data){
			$("#resAction").html("<div class='overload'><div class='load'></div></div>");
		},
		success: function(data){
			$('.result_delivery').animate({'opacity':'0'},500);
			setTimeout(function() { 
				$(".result_delivery").html(data);
				$("#resAction").empty();
			}, 500);
			$('.result_delivery').animate({'opacity':'1'},500);
		},
		error: function(html){
			console.log(html);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});*/
}
function basketPromo(){
	$.ajax({
		type: "POST",
		url: "/ajax/euro/basketPromo.php",
		data: {
			PROMOCOD: $('#promo').val()
		},
		beforeSend: function(data){
			$("#promoAdd strong").html('<i class="glyphicon glyphicon-transfer"></i>');
		},
		success: function(data){
			$("#resAction").html(data);
		},
		error: function(data){
			$("#promoAdd strong").html('<i class="glyphicon glyphicon-refresh"></i>');
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});
	//window.history.pushState(null, null, window.location);
}
function basketPromoDel(){
	$.ajax({
		type: "POST",
		url: "/ajax/euro/basketPromoDel.php",
		data: {
			PROMOCOD: $('#promo').val()
		},
		beforeSend: function(data){
			$("#promoAdd strong").html('<i class="glyphicon glyphicon-transfer"></i>');
		},
		success: function(data){
			$("#resAction").html(data);
			location.reload();
		},
		error: function(data){
			$("#promoAdd strong").html('<i class="glyphicon glyphicon-refresh"></i>');
			console.log(data);
			$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
			setTimeout(function(){$('.alert_error_add_item').remove()},5000);
		}
	});
	//window.history.pushState(null, null, window.location);
}
function elementBasketAddDelivery(){
	$.ajax({
		type: "POST",
		url: "/ajax/euro/elementBasketAddDelivery.php",
		data: {
			P: 'Y'
		},
		success: function(data){
			$(".delivery-box").html(data);
		}
	});
}
function elementBasketAddDeliveryList(){
	$.ajax({
		type: "POST",
		url: "/ajax/euro/elementBasketAddDeliveryList.php",
		data: {
			P: 'Y'
		},
		success: function(data){
			$("#resActionHidden").html(data);
		}
	});
}
function subPromoWindow(){
	$.ajax({
		type: "POST",
		url:  "/ajax/euro/subPromoWindow.php",
		success: function(data){
			$("#resActionHidden").html(data);
		}
	});
};
function subModalDiscont(ID){
	var validMail = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,6})+$/;
	var email = $('#subwinemail').val();
	if((email.trim() == '') || (!validMail.test(email.trim()))){
		$('#subwinemail').addClass('error-border1');
		setTimeout(function(){
			$('#subwinemail').removeClass('error-border1');
		},5000);
	}else{
		$('#subwinemail').removeClass('error-border1');
		$.ajax({
			type: "POST",
			url:  "/ajax/euro/subModalDiscont.php",
			data: {
				ID: ID, EMAIL: email
			},
			beforeSend: function(data){
				$("#resAction").html("<div class='overload'><div class='load'></div></div>");
			},
			success: function(data){
				$("#resAction").html(data);
			},
			error: function(data){
				console.log(data);
				$("#resAction").html($("<div class='alert_error_add_item'>Ошибка ajax запроса! Повторите попытку.</div>").slideDown(200));
				setTimeout(function(){$('.alert_error_add_item').remove()},5000);
			}
		});
	}
};
function subPromoTopWindow(ID){
	$.ajax({
		type: "POST",
		url:  "/ajax/euro/subPromoTopWindow.php",
		data: {ID: ID},
		success: function(data){
			$("body").prepend(data);
		}
	});
}