jQuery(function($){var input_timer;var input_timer_company;var done_input_interval=1000;var vat_input=$('input[name="billing_eu_vat_number"]');var vat_paragraph=$('p[id="billing_eu_vat_number_field"]');if('yes'==alg_wc_eu_vat_ajax_object.add_progress_text){vat_paragraph.append('<div id="alg_wc_eu_vat_progress"></div>');var progress_text=$('div[id="alg_wc_eu_vat_progress"]')}
alg_wc_eu_vat_validate_vat();vat_input.on('input',function(){clearTimeout(input_timer);input_timer=setTimeout(alg_wc_eu_vat_validate_vat,done_input_interval)});$('#billing_country').on('change',alg_wc_eu_vat_validate_vat);if(alg_wc_eu_vat_ajax_object.do_check_company_name){$('#billing_company').on('input',function(){clearTimeout(input_timer_company);input_timer_company=setTimeout(alg_wc_eu_vat_validate_vat,done_input_interval)})}
function alg_wc_eu_vat_validate_vat(){vat_paragraph.removeClass('woocommerce-invalid');vat_paragraph.removeClass('woocommerce-validated');var vat_number_to_check=vat_input.val();if(''!=vat_number_to_check){if('yes'==alg_wc_eu_vat_ajax_object.add_progress_text){progress_text.text(alg_wc_eu_vat_ajax_object.progress_text_validating)}
var data={'action':'alg_wc_eu_vat_validate_action','alg_wc_eu_vat_to_check':vat_number_to_check,'billing_country':$('#billing_country').val(),'billing_company':$('#billing_company').val(),};$.ajax({type:"POST",url:alg_wc_eu_vat_ajax_object.ajax_url,data:data,success:function(response){response=response.trim();if('1'==response){vat_paragraph.addClass('woocommerce-validated');if('yes'==alg_wc_eu_vat_ajax_object.add_progress_text){progress_text.text(alg_wc_eu_vat_ajax_object.progress_text_valid)}}else if('0'==response){vat_paragraph.addClass('woocommerce-invalid');if('yes'==alg_wc_eu_vat_ajax_object.add_progress_text){progress_text.text(alg_wc_eu_vat_ajax_object.progress_text_not_valid)}}else{vat_paragraph.addClass('woocommerce-invalid');if('yes'==alg_wc_eu_vat_ajax_object.add_progress_text){progress_text.text(alg_wc_eu_vat_ajax_object.progress_text_validation_failed)}}
$('body').trigger('update_checkout')},})}else{if('yes'==alg_wc_eu_vat_ajax_object.add_progress_text){progress_text.text('')}
if(vat_paragraph.hasClass('validate-required')){vat_paragraph.addClass('woocommerce-invalid')}else{vat_paragraph.addClass('woocommerce-validated')}
$('body').trigger('update_checkout')}}})