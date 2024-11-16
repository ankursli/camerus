/**
 * Copyright © Lyra Network and contributors.
 * This file is part of Systempay plugin for WooCommerce. See COPYING.md for license details.
 *
 * @author    Lyra Network (https://www.lyra.com/)
 * @author    Geoffrey Crofte, Alsacréations (https://www.alsacreations.fr/)
 * @copyright Lyra Network and contributors
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL v2)
 */

/**
 * External dependencies.
 */
import { registerPaymentMethod } from '@woocommerce/blocks-registry';
import { decodeEntities } from '@wordpress/html-entities';

/**
 * Internal dependencies.
 */
import { getSystempayServerData } from './systempay-utils';

const PAYMENT_METHOD_NAME = 'systempaystd';
var systempay_data = getSystempayServerData(PAYMENT_METHOD_NAME);

var submitButton = '.wc-block-components-checkout-place-order-button';
var smartbuttonMethod = '';
var smartbuttonAll = false;
var hideSmart = systempay_data?.hide_smartbutton && (systempay_data?.hide_smartbutton === 'true');
var hideButton = false;

var savedData = false;
var newData = null;

const Content = () => {
    if (systempay_data?.payment_fields) {
        var fields = <div dangerouslySetInnerHTML={{__html: systempay_data?.payment_fields}} />;

        return (
            <div>
                { fields }
            </div>
        );
    } else {
        return decodeEntities(systempay_data?.description);
    }
};

var Label = () => {
    const styles = {
        divWidth: {
            width: '95%'
        },
        imgFloat: {
            float: 'right'
        }
    }

    return (
        <div style={ styles.divWidth }>
            <span>{ systempay_data?.title}</span>
            <img
                style={ styles.imgFloat }
                src={ systempay_data?.logo_url }
                alt={ systempay_data?.title }
            />
        </div>
    );
};

registerPaymentMethod({
    name: PAYMENT_METHOD_NAME,
    label: <Label />,
    ariaLabel: 'Systempay payment method',
    canMakePayment: () => true,
    content: <Content />,
    edit: <Content />,
    supports: {
        features: systempay_data?.supports ?? [],
    },
});

var displayFields = function () {
    delete(window.FORM_TOKEN);
    delete(window.IDENTIFIER_FORM_TOKEN);
    delete(window.SYSTEMPAY_HIDE_SINGLE_BUTTON);

    switch (systempay_data?.payment_mode) {
        case 'REST':
        case 'SMARTFORM':
        case 'SMARTFORMEXT':
        case 'SMARTFORMEXTNOLOGOS':
            if (systempay_data?.vars) {
                window.SYSTEMPAY_BUTTON_TEXT = jQuery(submitButton).text();
                eval(systempay_data?.vars);

                hideButton = (window.SYSTEMPAY_HIDE_SINGLE_BUTTON == true);

                KR.onFormReady(() => {
                    if (hideSmart) {
                        let element = jQuery(".kr-smart-button");
                        if (element.length > 0) {
                            smartbuttonMethod = element.attr("kr-payment-method");
                            element.hide();
                        } else {
                            element = jQuery(".kr-smart-form-modal-button");
                            if (element.length > 0) {
                                smartbuttonMethod = "all";
                                element.hide();
                            }
                        }
                    }
                });
            }

            break; 

         default:
            break;
    }

    systempayUpdatePaymentBlock(true);
};

var onButtonClick = function (e) {
    if (! jQuery("#radio-control-wc-payment-method-options-systempaystd").is(":checked")) {
        return true;
    }

    // In case of form validation error, let WooCommerce deal with it.
    if (jQuery('div.wc-block-components-validation-error')[0]) {
        return true;
    }

    jQuery('.kr-form-error').html('');
    window.SYSTEMPAY_BUTTON_TEXT = jQuery(submitButton).text();

    document.cookie = 'systempay_force_redir=; Max-Age=0; path=/; domain=' + location.host;
    document.cookie = 'systempay_use_identifier=; Max-Age=0; path=/; domain=' + location.host;

    if (jQuery("#systempay_use_identifier")) {
        let use_identifier = jQuery("#systempay_use_identifier").val();
        document.cookie = 'systempay_use_identifier=' + use_identifier + '; path=/';
    }

    switch (systempay_data?.payment_mode) {
        case 'MERCHANT':
            systempaystd_get_card();
            break;

        case 'IFRAME':
             window.IFRAME_LINK = systempay_data?.link;
             window.IFRAME_SRC = systempay_data?.src;
             systempaystd_show_iframe();

             break;

        case 'REST':
        case 'SMARTFORM':
        case 'SMARTFORMEXT':
        case 'SMARTFORMEXTNOLOGOS':
            if (typeof window.FORM_TOKEN == 'undefined') {
                document.cookie = 'systempay_force_redir="true"; path=/; domain=' + location.host;
                break;
            }

            e.stopPropagation();
            block();

            var useIdentifier = jQuery("#systempay_use_identifier").length && jQuery("#systempay_use_identifier").val() === "true";
            var popin = (jQuery(".kr-smart-form-modal-button").length > 0) || (jQuery(".kr-popin-button").length > 0);

            if (! useIdentifier && ! popin && ! hideButton && ! hideSmart) {
                return validateKR(KR);
            } else {
                submitForm(KR, popin, useIdentifier);
                break;
            }

         default:
            break;
    }
};

var submitForm = function (KR, popin = false, useIdentifier = false) {
    jQuery.ajaxPrefilter(function(options, originalOptions, jqXHR) {
        newData = options.data;
    });

    var registerCard = jQuery('input[name="kr-do-register"]').is(':checked');

    if (savedData && (newData === savedData)) {
        // Data in checkout page has not changed, no need to calculate token again.
       submitKR(KR, popin);
    } else {
        savedData = newData;
        jQuery.ajax({
            method: 'POST',
            url: systempay_data?.token_url,
            data: { 'use_identifier': useIdentifier },
            success: function(data) {
                var parsed = JSON.parse(data);
                KR.setFormConfig({
                    language: window.SYSTEMPAY_LANGUAGE,
                    formToken: parsed.formToken
                }).then(function(v) {
                    KR = v.KR;
                    if (registerCard) {
                        jQuery('input[name="kr-do-register"]').attr('checked','checked');
                    }

                    return submitKR(KR, popin);
                });
            }
        });

        return true;
    }
};

var submitKR = function (KR, popin) {
    if (hideButton) {
        let element = jQuery('.kr-smart-button');

        if (element.length > 0) {
             smartbuttonMethod = element.attr('kr-payment-method');
        } else {
            element = jQuery('.kr-smart-form-modal-button');

            if (element.length > 0) {
                smartbuttonAll = true;
            }
        }
     }

     if (popin || smartbuttonAll) {
         KR.openPopin();
         unblock();
     } else if (hideButton) {
          KR.openSelectedPaymentMethod();
          unblock();
     } else if (smartbuttonMethod.length > 0) {
         KR.openPaymentMethod(smartbuttonMethod);
         unblock();
     } else {
         jQuery('#systempaystd_rest_processing').css('display', 'block');
         jQuery('ul.systempaystd-view-top li.block').hide();
         jQuery('ul.systempaystd-view-bottom').hide();

         KR.submit();
      }

      return true;
};

var block = function() {
    jQuery('form.wc-block-components-form wc-block-checkout__form').block();
    jQuery(submitButton).prop("disabled", true);
};

var unblock = function() {
    jQuery('form.wc-block-components-form wc-block-checkout__form').unblock();
    jQuery(submitButton).prop("disabled", false);
    jQuery('.wc-block-components-button__text').text("").text(window.SYSTEMPAY_BUTTON_TEXT);
};

var validateKR = function(KR) {
    KR.validateForm().then(function(v) {
        submitForm(v.KR);
    }).catch(function(v) {
        // Display error message.
        var result = v.result;
        return result.doOnError();
    });

    return true;
};

var first = true;
var initFields = function() {
    if (! first) {
        displayFields();

        jQuery(submitButton).on('click', onButtonClick);
        jQuery('input[type=radio][name=radio-control-wc-payment-method-options]').change(function(e) {
            if (this.value === 'systempaystd') {
                displayFields();
            }
        });
    }

    first = false;
};

jQuery(document).on('ready', initFields);
jQuery(window).on('load', initFields);
