var ez_flipclock_clock;

/* --------------- Scroll to anchor jquery - BEGIN --------------- */
// Scroll active
/*$(window).scroll(function () {
    var scrollDistance = $(window).scrollTop();
    $('section').each(function (i) {
        if ($(this).position().top - 51 <= scrollDistance) {
            $('a[href*="#"]:not([href="#"]).active').removeClass('active');
            $('a').eq(i).addClass('active');
        }
    });
}).scroll();*/
//Scroll to anchor
$(function () {
    $('a[href*="#"]:not([href="#"])').click(function () {
        //        Если не использовать scroll active
        //        $('a').each(function () {$(this).removeClass('active');})
        //        $(this).addClass('active');
        if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top - 50
                }, 1200);
                return false;
            }
        }
    });
});
/* --------------- Scroll to anchor jquery - END --------------- */
jQuery( document ).ready(function() {
	new WOW().init();
    jQuery("#formZakazSimpleContactPhone").mask("+7(999)999-99-99");
	jQuery("#zakazFormCallMeContactPhone").mask("+7(999)999-99-99");
	/*console.log('jQuery - app: READY');*/
	var currentDate = new Date(); // Grab the current date
	var ye = currentDate.getFullYear();
	var mo = currentDate.getMonth()+1;
	var futureDate  = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 1);
	// Calculate the difference in seconds between the future and current date
	var diff = futureDate.getTime() / 1000 - currentDate.getTime() / 1000;
	ez_flipclock_clock = jQuery('.ez-flipclock-clock').FlipClock(diff, {
		clockFace: 'DailyCounter',
		countdown: true,
		language: 'ru'
	});
    /*-------------------------------------------------------------------------*/
    /*--- Вызов модальной формы #modalFormCallMe из карточки товара         ---*/
    /*--- Определение значений data-form из карточки товара                 ---*/
    /*--- Установка data-form для внутренней формы заказа #zakazFormCallMe  ---*/
    /*-------------------------------------------------------------------------*/
    jQuery('.ez-goods-one-card-call-me,#buttonTopCallMe').click(function (event) {
        //var form_modal_id  = '#modalFormCallMe';
        //var form_zakaz_id  = '#zakazFormCallMe'; // внутренняя форма с POST'ом
        // Определение значений data-form из карточки товара
        var data_form_modal_id  = jQuery(this).attr("data-form-modal-id");
        var data_form_zakaz_id  = jQuery(this).attr("data-form-zakaz-id");
        var data_zakaz_good_name= jQuery(this).attr("data-zakaz-good-name");
        //console.log('jQuery - call-me: data_form_modal_id='+data_form_modal_id);
        //console.log('jQuery - call-me: data_form_zakaz_id='+data_form_zakaz_id);
        //console.log('jQuery - call-me: data_zakaz_good_name='+data_zakaz_good_name);
        // Установка data-form для внутренней формы заказа
        var form_zakaz_obj = jQuery('#'+data_form_zakaz_id);
        form_zakaz_obj.attr("data-form-modal-id",   data_form_modal_id);
        form_zakaz_obj.attr("data-form-zakaz-id",   data_form_zakaz_id);
        form_zakaz_obj.attr("data-zakaz-good-name", data_zakaz_good_name);
        // Вызов модальной формы с внутренней формой заказа
        // jQuery('#modalFormCallMe').modal();
        jQuery('#'+data_form_modal_id).modal();
        event.preventDefault();
    });
    /*-----------------------------------------------------------------------------*/
    /*--- Обработка SUBMIT (POST) для внутренней формы заказа #zakazFormCallMe  ---*/
    /*--- Передача значений data-form для обработки на сервер                   ---*/
    /*-----------------------------------------------------------------------------*/
    jQuery('#zakazFormCallMe').submit(function(event) { 
        // Определение значений data-form из внутренней формы заказа #zakazFormCallMe
        var data_form_modal_id  = jQuery(this).attr("data-form-modal-id");
        var data_form_zakaz_id  = jQuery(this).attr("data-form-zakaz-id");
        var data_zakaz_good_name= jQuery(this).attr("data-zakaz-good-name");
        //console.log('jQuery - call-me: SUBMIT : data_form_modal_id='    +data_form_modal_id);
        //console.log('jQuery - call-me: SUBMIT : data_form_zakaz_id='    +data_form_zakaz_id);
        //console.log('jQuery - call-me: SUBMIT : data_zakaz_good_name='  +data_zakaz_good_name);
        /// Формирование опций сабмита, том числе и формирование data 
        /// полученных из внутренней формы заказа #zakazFormCallMe
        var loc_ajaxSubmitOptionszakazFormCallMe = { 
            data:{  'formModalId'  : ''  +data_form_modal_id+'',
                    'formZakazId'  : ''  +data_form_zakaz_id+'',
                    'zakazGoodName': ''  +data_zakaz_good_name+'' },
            success:showResponseFormZakaz,  // post-submit callback 
            url:    'php/ez-form-zakaz.php',// override for form's 'action' attribute 
        };
        jQuery(this).ajaxSubmit(loc_ajaxSubmitOptionszakazFormCallMe);  
        // !!! Important !!! always return false to prevent standard browser submit and page navigation 
        return false; 
    }); 


});

// post-submit callback 
function showResponseFormZakaz(responseText, statusText, xhr, data_form_name)  { 
    //console.log('showResponseFormZakaz: statusText='+statusText);
    //console.log('showResponseFormZakaz: responseText='+responseText);
    var ajaxStatus	= statusText;
	var jsonObj		= JSON.parse(responseText);
	var orderKey 	= jsonObj['order_key'];
	var errCode 	= jsonObj['err_code'];
    var errMsgT 	= jsonObj['err_msg_t'];
    var errMsgS 	= jsonObj['err_msg_s'];
    var errMsgL 	= jsonObj['err_msg_l'];
    var clientName 	= jsonObj['client_name']; 
    var clientPhone	= jsonObj['client_phone'];
    var carVin		= jsonObj['car_vin'];
    var carMark		= jsonObj['car_mark'];
    var carModel	= jsonObj['car_model'];
    var carGener	= jsonObj['car_gener'];
    var carPart		= jsonObj['car_part'];
    var clientID	= jsonObj['client_id']; 
    var carID		= jsonObj['car_id'];
    var orderID		= jsonObj['order_id'];
    var orderNum    = jsonObj['order_num'];
    var formModalId = jsonObj['form_modal_id'];
    var formZakazId = jsonObj['form_zakaz_id'];
    //console.log('showResponseFormZakaz: formModalId='+formModalId);
    //console.log('showResponseFormZakaz: formZakazId='+formZakazId);
    jQuery('#'+formModalId).modal('hide');
    //
	if (errCode == '0') {
		$('#modalFormZakazSuccessTitleText').html(errMsgT);	
		$('#modalFormZakazSuccessBodyText').html(errMsgS);
		$('#modalFormZakazSuccess').modal();
		var orderDateTime = formatDate('YYYYMMDDHHmmss'); //используется - /js/formatdate.js
		/*
		Cookies.set('orderDateTime',orderDateTime, 	{ expires: 100 });
		Cookies.set('clientName',  	clientName, 	{ expires: 100 });
		Cookies.set('clientPhone',  clientPhone,	{ expires: 100 });
		Cookies.set('carVin',  		carVin, 		{ expires: 100 });
		Cookies.set('carMark',  	carMark, 		{ expires: 100 });
		Cookies.set('carModel',  	carModel, 		{ expires: 100 });
		Cookies.set('carGener',  	carGener, 		{ expires: 100 });
		*/
	} else {
		if (errCode == -2001||errCode == -2002||errCode == -2003||errCode == -2005) {   
			$('#modalFormZakazWarningTitleText').html(errMsgT);
			$('#modalFormZakazWarningBodyText').html(errMsgS);
    		$('#modalFormZakazWarning').modal();
		} else if (errCode < -9999) {
			$('#modalFormZakazDangerTitleText').html(errMsgT);
			$('#modalFormZakazDangerBodyText').html(errMsgS);
    		$('#modalFormZakazDanger').modal();
		} else {
			$('#modalFormZakazInfoTitleText').html(errMsgT);
			$('#modalFormZakazInfoBodyText').html(errMsgS);
    		$('#modalFormZakazInfo').modal();
		}
	}
} 