import * as html2canvas from 'html2canvas'
import * as jsPDF from 'jspdf'
import print from 'print-js'

import '../../node_modules/flatpickr/dist/flatpickr.min.css'
import '../../node_modules/flatpickr/dist/themes/airbnb.css'

import flatpickr from 'flatpickr'
import {French} from "flatpickr/dist/l10n/fr.js"

import 'jquery';
import 'slick-carousel'

/**
 * Main components function
 * @type {{telephone_reg: RegExp, form_error_messages: {error: {zipcode: string, telephone: string, email: string}, required: string}, listen_submit(*=, *=, *=): void, chunkArray(*, *): *, ui_select_default_values: string[], fields_selectors: string, send_text: string, do_ajax(*=, *=, *=, *=, *=): void, zipcode_reg: RegExp, email_reg: RegExp, icon_submit_button_forms: string[], validate_form(*): *, process_form(*=, *, *=): void}}
 */
let component = {
    capitalize(word) {
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    },
    chunkArray(arr, n) {
        var chunkLength = Math.max(arr.length / n, 1);
        var chunks = [];
        for (var i = 0; i < n; i++) {
            if (chunkLength * (i + 1) <= arr.length) chunks.push(arr.slice(chunkLength * i, chunkLength * (i + 1)))
        }
        return chunks
    },

    telephone_reg: new RegExp(/^(?=.*[0-9])[- +()0-9]+$/),

    email_reg: new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i),

    zipcode_reg: new RegExp(/^[0-9]*$/),

    fields_selectors: 'input[type=\'text\'], input[type=\'email\'], input[type=\'radio\'], textarea, select',

    ui_select_default_values: [
        'Votre demande*',
        'Dépannage',
        'Quel est votre demande ?'
    ],

    icon_submit_button_forms: [
        'trouver-agence-locale',
        'trouver-agence-locale-sidebar'
    ],

    send_text: 'Envoi en cours...',

    form_error_messages: {
        required: 'Ce champ est requis',
        error: {
            telephone: 'Numéro de téléphone invalide',
            email: 'Adresse email invalide',
            zipcode: 'Code postal invalide'
        }
    },

    do_ajax(dataType, data, done, fail = null, always = null) {
        $.ajax({
            url: themosis.ajaxurl,
            type: 'POST',
            dataType: dataType,
            data: data,
            async: true
        }).done(done).fail(fail).always(always)
    },

    detect_ajax(callbackStart, callbackEnd) {

        let s_ajaxListener = {};
        s_ajaxListener.tempOpen = XMLHttpRequest.prototype.open;
        s_ajaxListener.tempSend = XMLHttpRequest.prototype.send;

        function stateChanged(event) {
            if (this.readyState === 1) {
                callbackStart(event)
            }
        }

        function stateChangedEnd(event) {
            if (this.readyState === 4) {
                callbackEnd(event)
            }
        }

        XMLHttpRequest.prototype.open = function (a, b) {
            if (!a) var a = '';
            if (!b) var b = '';
            this.addEventListener('readystatechange', stateChanged);
            s_ajaxListener.tempOpen.apply(this, arguments);
            s_ajaxListener.method = a;
            s_ajaxListener.url = b;
            if (a.toLowerCase() == 'get') {
                s_ajaxListener.data = b.split('?');
                s_ajaxListener.data = s_ajaxListener.data[1]
            }
        };

        XMLHttpRequest.prototype.send = function (a, b) {
            if (!a) var a = '';
            if (!b) var b = '';
            s_ajaxListener.tempSend.apply(this, arguments);
            if (s_ajaxListener.method.toLowerCase() == 'post') s_ajaxListener.data = a;
            this.addEventListener('readystatechange', stateChangedEnd);
            s_ajaxListener.callback()
        };

        s_ajaxListener.callback = function () {
            // this.method :the ajax method used
            // this.url    :the url of the requested script (including query string, if any) (urlencoded)
            // this.data   :the data sent, if any ex: foo=bar&a=b (urlencoded)
        }
    },

    validate_form(form) {
        let fields = form.find(component.fields_selectors);
        let error_open = '<span class=\'error\' style=\'color: red; text-align: right;\'>';
        let error_close = '</span>';
        let is_valid = true;

        form.find('.alert').remove();
        form.find('.error').remove();
        form.find('.message').remove();

        fields.each(function () {
            let value = $(this).val() || $(this).html();
            let parent = $(this).closest('.form-group') || $(this).closest('.block-body');
            let field_type = $(this).attr('type');

            // console.log('field_type : ', field_type)

            if (value === '' || $(this).data('required')) {
                parent.append(
                    error_open +
                    component.form_error_messages.required +
                    error_close
                );
                is_valid = false
            } else if (field_type === 'tel' && !component.telephone_reg.test(value)) {
                parent.append(
                    error_open +
                    component.form_error_messages.error.telephone +
                    error_close
                );
                is_valid = false
            } else if (field_type === 'email' && !component.email_reg.test(value)) {
                parent.append(
                    error_open +
                    component.form_error_messages.error.email +
                    error_close
                );
                is_valid = false
            } else if (field_type === 'zipcode' && !component.zipcode_reg.test(value)) {
                parent.append(
                    error_open +
                    component.form_error_messages.error.zipcode +
                    error_close
                );
                is_valid = false
            }
        });

        return is_valid
    },

    process_form(form, submit_button, action) {
        let security = {
            wpnonce: form.find('[name=\'_wpnonce\']').val(),
            wp_http_referer: form.find('[name=\'_wp_http_referer\']').val()
        };

        let form_name = form.find('[name=form_name]').val();
        let form_identifier = form.find('[name=form_name]').data('form-identifier');
        let infos = form.find(':not([name=\'form_name\'], [name=\'_wpnonce\'], [name=\'_wp_http_referer\'])').serializeArray();
        let current_button_text = submit_button.html() || submit_button.val();

        let message_open = '<div class="row"><div class="col-xs-12 col"><div class="alert alert-warning">';
        let message_close = '</div></div></div>';

        let zipcode = '';

        //Change button behaviour

        if (submit_button.html()) {
            submit_button.html(component.send_text)
        } else {
            submit_button.val(component.send_text)
        }

        submit_button.attr('disabled', 'disabled');

        //Add placeholder inside infos
        for (let key in infos) {
            let field = $(form).find('[name=' + infos[key].name + ']');
            let placeholder = field.attr('placeholder') || field.data('placeholder') || '';

            infos[key]['placeholder'] = placeholder
        }

        //Zipcode
        if ($.inArray(action, ['redirect_form', 'redirect_zipcode']) !== -1) {
            zipcode = form.find('input[name=zipcode_redirect]').val() || ''
        }

        //Process form
        let data = {
            action: action,
            origin: window.location.hostname,
            security: security,
            infos: infos,
        };

        component.do_ajax('json', data, function (response) {
            if (submit_button.html()) {
                submit_button.html(current_button_text)
            } else {
                submit_button.val(current_button_text)
            }

            submit_button.removeAttr('disabled');

            if (response.processed) {
                switch (action) {
                    case 'redirect_form':
                        window.location.href = response.permalink;
                        break;

                    case 'redirect_zipcode':
                        window.location.href = response.local_site_url;
                        break;

                    default:
                        form.trigger('reset');
                        break
                }
            }

            submit_button.parent().prepend(
                message_open +
                response.message +
                message_close
            )

            // console.log(response.data)
        }, function (response) {
            console.log(response)
        })
    },

    listen_submit(form, submit_button, action) {
        // form.bind("keypress", function (e) {
        //     let target = $(e.target).data("type");
        //
        //     if (e.keyCode == 13 && target !== "textarea") {
        //         submit_button.click();
        //         return false;
        //     }
        // });

        form.on('submit', function (e) {
            e.preventDefault();

            if (component.validate_form(form)) {
                component.process_form(form, submit_button, action)
            }
        })

        // submit_button.on("click", function () {
        //     if (component.validate_form(form)) {
        //         component.process_form(form, submit_button, action);
        //     }
        // });
    },

    createCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            let expires = "; expires=" + date.toUTCString();
        }

        document.cookie = name + "=" + value + expires + "; path=/";
    },

    readCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    },

    eraseCookie(name) {
        component.createCookie(name, "", -1);
    }
};

/**
 * Manage Contact page
 * @type {{listen_form_submit: contact.listenFormSubmit}}
 */
let contact = {
    listenFormSubmit: function () {
        let form = $('#contact__form');
        let submit_button = $('#contact__form_submit');

        component.listen_submit(form, submit_button, 'contact_form_submit')
    }
};

/**
 * Manage Agenda
 * @type {{sendFilter: agenda.sendFilter, filter_form: agenda.filterForm}}
 */
let agenda = {
    initAgendaList() {
        if ($('#calendar').length > 0) {
            $('#calendar').addClass('loading');
            setTimeout(agenda.sendFilter, 100)
        }
    },
    filterForm: function () {
        $('#salon_filter_form .select').on('change', function () {
            $('#calendar').addClass('loading');
            setTimeout(agenda.sendFilter, 100)
        })
    },

    sendFilter: function () {
        let form = $('#salon_filter_form');
        let dataForm = form.serializeArray();
        let ticket_container = $('#calendar .block-calendar__slider .block-container');
        let ticket_switcher = $('#calendar .uk-switcher');
        let current_lang = $(document).find('html').attr('lang');


        dataForm.push({name: 'action', value: 'salon_filter_form'});
        dataForm.push({name: 'lang', value: current_lang});

        function done(response, textStatus, jqXHR) {
            if (response.success === true && response.data.post_number > 0) {
                let slider = $(document).find('.slick-content-slide');
                if (response.data.salon_ticket !== undefined) {
                    slider.slick('unslick');
                    ticket_container.find('.slick-content-slide').remove();
                    ticket_switcher.empty();
                    ticket_container.prepend(response.data.salon_ticket);
                    ticket_switcher.prepend(response.data.salon_switcher);
                    slick_carrousel.init();
                }
                if (ui !== undefined) {
                    ui.wrap.articles__preview()
                }
                $('#calendar').removeClass('loading')
                // console.log(response);
            } else if (response.success === true && response.data.post_number === 0) {
                ticket_container.find('.uk-slider-items').remove();
                ticket_switcher.empty();
                ticket_switcher.prepend('<div class="inner uk-active no-results">\n' +
                    '        <div class="row">\n' +
                    '            <div class="col-sm-10 col-sm-offset-1">\n' +
                    '                <div class="block block-calendar__event uk">\n' +
                    '                    <div class="block-content">\n' +
                    '                        <div class="block-body uk-grid uk-grid-large uk-flex-center">\n' +
                    '                            <div class="uk-width-2-3@m col-left uk-text-center">\n' +
                    '                                <div class="alert alert-warning">' + response.data.message + '</div>\n' +
                    '                            </div>\n' +
                    '                        </div>\n' +
                    '                    </div>\n' +
                    '                </div>\n' +
                    '            </div>\n' +
                    '        </div>\n' +
                    '    </div>');

                $('#calendar').removeClass('loading')
            } else {
                console.log(response);
                $('#calendar').removeClass('loading')
            }
        }

        function fail(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown)
        }

        component.do_ajax('json', dataForm, done, fail)
    },

    onChangeFavoris: function () {
        $(document).find('#calendar').on('click', '.agenda-btn-favoris', function (e) {
            $(this).css('pointer-events', 'none');
            $('#calendar').addClass('loading');

            if ($(this).hasClass('agenda-add-favoris')) {
                agenda.addToFavoris($(this))
                // console.log('add to favoris')
            } else if ($(this).hasClass('agenda-delete-favoris')) {
                agenda.deleteToFavoris($(this))
                // console.log('delete to favoris')
            }
        })
    },

    addToFavoris: function (btn) {
        // console.log('add_f: ', btn.attr('class'))

        let data_salon = btn.attr('data-salon');
        let data_secu = btn.attr('data-secu');
        data_salon = data_salon.split('-');

        let salon = parseInt(data_salon[1]);

        if (Number.isInteger(salon) && salon > 0 && data_secu !== undefined) {
            let dataForm = [
                {name: 'action', value: 'salon_add_favoris'},
                {name: 'security', value: data_secu},
                {name: 'agenda', value: salon},
                {name: 'lang', value: $('html')[0].lang}
            ];

            function done(response, textStatus, jqXHR) {
                if (response.success === true) {
                    let _agenda = response.data.datas.agenda;

                    $(document).find('.agenda-btn .star.agenda-' + _agenda).removeClass('agenda-add-favoris').addClass('active agenda-delete-favoris');
                    $(document).find('.agenda-btn .star.agenda-' + _agenda + ' i').removeClass('icon-product-star-1').addClass('icon-product-star-2');
                    UIkit.tooltip('.agenda-btn .star.agenda-' + salon + '[data-uk-tooltip]').title = 'Supprimer de mes favoris'
                } else {
                    console.log('Fail: ', response)
                }
                btn.css('pointer-events', 'auto');
                $('#calendar').removeClass('loading')
            }

            function fail(jqXHR, textStatus, errorThrown) {
                btn.css('pointer-events', 'auto');
                $('#calendar').removeClass('loading');
                console.log('Error: ', errorThrown)
            }

            component.do_ajax('json', dataForm, done, fail)
        }
    },

    deleteToFavoris: function (btn) {
        // console.log('delete_f: ', $(this).attr('class'))

        let data = {
            title: 'Supprimer mon salon favoris',
            text: 'Êtes-vous sure de vouloir supprimer ce salon de votre favoris ?',
        };

        modal_manager.modalConfirm(btn, data, function (confirm) {
            if (confirm) {
                let data_salon = btn.attr('data-salon');
                let data_secu = btn.attr('data-secu');
                data_salon = data_salon.split('-');

                let salon = parseInt(data_salon[1]);

                if (Number.isInteger(salon) && salon > 0 && data_secu !== undefined) {
                    let dataForm = [
                        {name: 'action', value: 'salon_delete_favoris'},
                        {name: 'security', value: data_secu},
                        {name: 'agenda', value: salon},
                        {name: 'lang', value: $('html')[0].lang}
                    ];

                    function done(response, textStatus, jqXHR) {
                        if (response.success === true) {
                            $('.card.agenda-' + salon).hide(800);
                            $(document).find('.agenda-btn .star.agenda-' + salon).removeClass('active agenda-delete-favoris').addClass('agenda-add-favoris agenda-' + salon);
                            $(document).find('.agenda-btn .star.agenda-' + salon + ' i').removeClass('icon-product-star-2').addClass('icon-product-star-1');
                            UIkit.tooltip('.agenda-btn .star.agenda-' + salon + '[data-uk-tooltip]').title = 'Ajouter aux favoris'
                        } else {
                            console.log('Fail: ', response)
                        }
                        btn.css('pointer-events', 'auto');
                        $('#calendar').removeClass('loading')
                    }

                    function fail(jqXHR, textStatus, errorThrown) {
                        btn.css('pointer-events', 'auto');
                        $('#calendar').removeClass('loading');
                        console.log('Error: ', errorThrown)
                    }

                    component.do_ajax('json', dataForm, done, fail)
                }
            }
        })
    },

    onChangeSalonCity() {
        let btn = $(document).find('.salon-change-city-btn');

        btn.on('click', function (e) {
            e.preventDefault();

            let form = $(document).find('#modal-select-salon-city');
            let form_data = form.serializeArray();
            let target_url = $(this).attr('data-target-url');

            let searchParams = new URLSearchParams('');

            if (form_data !== null) {
                form_data.forEach(function (el, i) {
                    searchParams.append(el.name, el.value)
                })
            }

            let next_url = target_url + '?' + searchParams.toString();

            $('#modal-salon-change .salon-next-btn').attr('href', next_url);

            UIkit.modal('#modal-salon-change', {'bgClose': false}).show()
        });

        let form = $('#modal-select-salon-event');

        form.on('submit', function (e) {
            e.preventDefault();

            let current_salon = form.find('#salon_current_selected').val();
            let selected_salon = form.find('#event__modal-showroom').val();
            // let href = form.find('#product_listing_page').val() + '?event_salon=' + selected_salon;
            let event_url = window.location.href;
            let product_listing_href = $(document).find('input[name="product_listing_page"]').val();

            if (product_listing_href == event_url) {
                event_url = form.find('input.cmrs_homepage').val() + '/showroom/' + selected_salon;
            }

            if (selected_salon.length > 0) {
                if (current_salon === selected_salon) {
                    form[0].submit()
                } else {
                    $('#modal-salon-change .salon-next-btn').attr('href', event_url);
                    UIkit.modal('#modal-salon-change', {'bgClose': false}).show()
                }
            }
        })
    },

    onSelectSalonPopup() {
        let modal = $('#modal-event-warning, #modal-event');
        let salon_filter = modal.find('input[name="salon-filter"]');

        modal.on('change', '#event__modal-showroom', function () {
            let salon_slug = $(this).find(':selected').val();
            if (salon_slug.length > 0) {
                salon_filter.val(salon_slug)
            }
        })
    },

    saveEventDataLocalStorage(key, value) {
        key = 'cmrs-' + key;
        if (value == null || value == '' || typeof value == 'undefined') {
            value = 0
        }
        localStorage.setItem(key, value);
        sessionStorage.setItem('cmrs-check', '1');
    },

    checkEventSessionStorage() {
        return sessionStorage.getItem('cmrs-check');
    },

    getEventDataLocalStorage(key) {
        key = 'cmrs-' + key;
        return localStorage.getItem(key);
    },

    getEventDataTypeLocalStorage() {
        if (agenda.isValidEventDataLocalStorage()) {
            return localStorage.getItem("cmrs-event_type");
        }
        return null;
    },

    getEventDataSalonLocalStorage() {
        if (agenda.isValidEventDataLocalStorage()) {
            return localStorage.getItem("cmrs-event_salon");
        }
        return null;
    },

    getEventDataProLocalStorage() {
        if (agenda.isValidEventDataLocalStorage()) {
            return localStorage.getItem("cmrs-event_pro");
        }
        return null;
    },

    getEventDataTemplateViewLocalStorage() {
        if (agenda.isValidEventDataLocalStorage()) {
            return localStorage.getItem("cmrs-event_template_view");
        }
        return null;
    },

    isValidEventDataLocalStorage() {
        let event_time = parseInt(localStorage.getItem("cmrs-event_time"));

        if (typeof event_time !== 'undefined' && event_time > 0) {
            let max_date = new Date(event_time);
            let current_date = new Date();
            let x_date = max_date.getDate() + '-' + max_date.getMonth() + '-' + max_date.getFullYear();
            let c_date = current_date.getDate() + '-' + current_date.getMonth() + '-' + current_date.getFullYear();

            if (x_date === c_date) {
                return true;
            }
        }

        return false;
    },

    ajaxSendEventData(event_salon, event_type, event_url, new_time = true) {
        let dataForm = [
            {name: 'action', value: 'event_data_sending'},
            {name: 'event_salon', value: event_salon},
            {name: 'event_type', value: event_type},
            {name: 'clang', value: $('html')[0].lang}
        ];

        let productDataForm = single_product.getProductAddToCartData();
        if (productDataForm != null) {
            dataForm = dataForm.concat(productDataForm);
            single_product.deleteProductAddToCartData();
        }

        $('#calendar').addClass('loading');

        function done(response, textStatus, jqXHR) {
            if (response.success === true) {
                let save_event = true;
                let is_success_cart = false;
                let is_add_to_cart_query = false;
                let single_add_to_cart_btn = $('.variations_form-2.cart button[type="submit"]');

                if (productDataForm != null && response.data.is_add_to_cart != null) {
                    let mini_cart = $('#topbar .mini-cart-container');
                    is_add_to_cart_query = true;
                    is_success_cart = response.data.success_add_to_cart;
                    mini_cart.empty();
                    mini_cart.append(response.data.cart);
                    if (response.data.notices_html) {
                        $.each(response.data.notices_html, function (index, value) {
                            UIkit.notification(value, {
                                pos: 'top-right',
                                status: 'primary',
                                timeout: 35000
                            });
                        })
                    }

                    if (!is_success_cart) {
                        save_event = false;
                    }
                }

                if (save_event) {
                    agenda.saveEventDataLocalStorage('event_salon', response.data.event_salon);
                    agenda.saveEventDataLocalStorage('event_template_view', response.data.event_template_view);
                    agenda.saveEventDataLocalStorage('event_type', response.data.event_type);
                    agenda.saveEventDataLocalStorage('event_pro', response.data.event_pro);
                    if (new_time) {
                        agenda.saveEventDataLocalStorage('event_time', response.data.event_time);
                    }

                    agenda.setSearchFormEventType(response.data.event_type);
                }

                if (is_success_cart) {
                    single_product.load_price_single();
                    single_product.load_price_loop();
                    UIkit.modal('#modal-event-warning', {'bgClose': false}).hide();
                } else {
                    $('#modal-event-warning .uk-modal-body').removeClass('loading');
                    UIkit.modal('#modal-event-warning', {'bgClose': false}).hide();
                }

                if (is_add_to_cart_query === false && event_url !== null) {
                    window.location.href = event_url;
                }

                if (single_add_to_cart_btn.length > 0) {
                    single_add_to_cart_btn.loading(false);
                    single_add_to_cart_btn.removeAttr('style');
                }
                $('#calendar .select-salon').loading(false);
                $('#modal-salon-change .salon-next-btn').loading(false);
                $('#calendar').removeClass('loading');
                $('.add_to_cart_button ').loading(false);
            } else {
                console.log(response);
                $('.variations_form-2.cart button[type="submit"]').loading(false);
                $('#calendar .select-salon').loading(false);
                $('#modal-salon-change .salon-next-btn').loading(false);
                $('#calendar').removeClass('loading');
                $('.add_to_cart_button ').loading(false);
            }
        }

        function fail(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            $('#calendar').removeClass('loading');
        }

        component.do_ajax('json', dataForm, done, fail)
    },

    onChangeSalon() {
        let btn_home = $(document).find('#calendar');
        btn_home.on('click', '.select-salon', function (e) {
            e.preventDefault();

            btn_home.loading(true);

            let event_url = $(this).attr('data-href');
            let event_salon = $(this).attr('data-event-salon');
            let event_type = $(this).attr('data-event-type');

            if (event_type && event_salon) {
                if (agenda.isValidEventDataLocalStorage()) {
                    let modal_change_salon = $('#modal-salon-change .salon-next-btn');
                    modal_change_salon.attr('data-href', event_url);
                    modal_change_salon.attr('data-event-salon', event_salon);
                    modal_change_salon.attr('data-event-type', event_type);

                    UIkit.modal('#modal-salon-change', {'bgClose': false}).show()
                } else {
                    agenda.ajaxSendEventData(event_salon, event_type, event_url)
                }
            }
        });

        let form_event_salon = $(document).find('body');
        form_event_salon.on('change', '.modal-select-salon-event select[name="event_salon"]', function (e) {
            e.preventDefault();

            let modal_loader = $(this).parents('.uk-modal-dialog');
            let event_salon = $(this).find('option:selected').val();
            let event_data_type = $(this).find('option:selected').attr('data-type');
            let event_type = null;
            let event_url = window.location.href;
            let product_listing_href = $(document).find('input[name="product_listing_page"]').val();

            if (event_data_type == 'event-type') {
                event_type = event_salon;
                event_salon = null;
            }

            if (product_listing_href == event_url) {
                event_url = $(document).find('.modal-select-salon-event input.cmrs_homepage').val() + '/showroom/' + event_salon;
            }
            let old_query = $(document).find('.modal-select-salon-event input[name="old-query"]');

            if (typeof old_query != 'undefined' && old_query.val() == '1') {
                event_url = window.location.href + '?old-query=1';
            }

            if ((event_salon != null && event_salon.length > 0) || (event_type != null && event_type.length > 0)) {
                if (agenda.isValidEventDataLocalStorage()) {
                    let modal_change_salon = $('#modal-salon-change .salon-next-btn');
                    modal_change_salon.attr('data-href', event_url);
                    modal_change_salon.attr('data-event-salon', event_salon);
                    modal_change_salon.attr('data-event-type', event_type);

                    UIkit.modal('#modal-salon-change', {'bgClose': false}).show()
                } else {
                    modal_loader.addClass('loading');
                    agenda.ajaxSendEventData(event_salon, event_type, event_url)
                }
            }
        });

        let form_event_type = $(document).find('body');
        form_event_type.on('change', '.modal-select-salon-city select[name="event_city"]', function (e) {
            e.preventDefault();

            let modal_loader = $(this).parents('.uk-modal-dialog');
            let event_salon = null;
            let event_type = $(this).val();
            let event_url = window.location.href;
            let old_query = $(document).find('.modal-select-salon-city input[name="old-query"]');

            if (typeof old_query != 'undefined' && old_query.val() == '1') {
                event_url = window.location.href + '?old-query=1';
            }

            if (event_type.length > 0) {
                if (agenda.isValidEventDataLocalStorage()) {
                    let modal_change_salon = $('#modal-salon-change .salon-next-btn');
                    modal_change_salon.attr('data-href', event_url);
                    modal_change_salon.attr('data-event-salon', event_salon);
                    modal_change_salon.attr('data-event-type', event_type);

                    UIkit.modal('#modal-salon-change', {'bgClose': false}).show()
                } else {
                    modal_loader.addClass('loading');
                    agenda.ajaxSendEventData(event_salon, event_type, event_url)
                }
            }
        });

        let modal_btn = $(document).find('#modal-salon-change');
        modal_btn.on('click', '.salon-next-btn', function (e) {
            e.preventDefault();

            $(this).loading(true);
            $(this).attr('disabled', 'disabled');
            let event_url = $(this).attr('data-href');
            let event_salon = $(this).attr('data-event-salon');
            let event_type = $(this).attr('data-event-type');

            if (event_type) {
                agenda.ajaxSendEventData(event_salon, event_type, event_url)
            }
        });

        let side_bar_product = $(document).find('.product-sidebar-list');
        side_bar_product.on('click', '#cmrs-pro-change-event', function (e) {
            e.preventDefault();

            let event_url = $(this).attr('data-href');
            let event_salon = $(this).attr('data-event-salon');
            let event_type = $(this).attr('data-event-type');

            if (event_type.length > 0) {
                if (agenda.isValidEventDataLocalStorage()) {
                    let modal_change_salon = $('#modal-salon-change .salon-next-btn');
                    modal_change_salon.attr('data-href', event_url);
                    modal_change_salon.attr('data-event-salon', event_salon);
                    modal_change_salon.attr('data-event-type', event_type);

                    UIkit.modal('#modal-salon-change', {'bgClose': false}).show()
                } else {
                    $(this).loading(true);
                    $(this).attr('disabled', 'disabled');
                    agenda.ajaxSendEventData(event_salon, event_type, event_url, true)
                }
            }
        });
    },

    onPageNeedSalon() {
        let event_type = agenda.getEventDataTypeLocalStorage();

        if (event_type !== null) {
            if (agenda.checkEventSessionStorage() == null) {
                agenda.refreshServerSideEventData(event_type);
            } else {
                agenda.setSearchFormEventType(event_type);
            }
        } else {
            if ($('#cmrs-no-salon').length) {
                agenda.showModalEventWarning()
            }
        }
    },

    isValidModalEventWarningLocalStorage() {
        let event_time = parseInt(localStorage.getItem("cmrs-event_modal_warning_time"));

        if (typeof event_time !== 'undefined' && event_time > 0) {
            let max_date = new Date(event_time);
            let current_date = new Date();
            let x_date = max_date.getDate() + '-' + max_date.getMonth() + '-' + max_date.getFullYear();
            let c_date = current_date.getDate() + '-' + current_date.getMonth() + '-' + current_date.getFullYear();

            if (x_date === c_date) {
                return true;
            }
        }

        return false;
    },

    ajaxGetEventWarningTemplate(show_modal = false) {
        let dataForm = [
            {name: 'action', value: 'event_modal_warning_template'},
            {name: 'lang', value: $('html')[0].lang},
        ];

        $('#modal-event-warning .uk-modal-body').addClass('loading');

        function done(response, textStatus, jqXHR) {
            if (response.success === true) {
                agenda.saveModalEventWarningLocalStorage(response.data.event_modal_warning_template, response.data.event_modal_time);
                agenda.setModalEventWarningTemplate();
                ui.jquerUi.selectmenu();
                if (show_modal) {
                    agenda.activePopupEventWarning()
                }
            } else {
                console.log(response);
                $('#calendar').removeClass('loading');
                $('#calendar .select-salon').loading(false);
                $('#modal-salon-change .salon-next-btn').loading(false);
            }
            $('#modal-event-warning .uk-modal-body').removeClass('loading');
        }

        function fail(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            $('#calendar').removeClass('loading');
        }

        component.do_ajax('json', dataForm, done, fail)
    },

    saveModalEventWarningLocalStorage(value, time) {
        if (value == null || value == '' || typeof value == 'undefined') {
            value = 0
        }
        localStorage.setItem('cmrs-event_modal_warning', value);
        localStorage.setItem('cmrs-event_modal_warning_time', time);
    },

    getModalEventWarningLocalStorage() {
        if (agenda.isValidModalEventWarningLocalStorage()) {
            return localStorage.getItem("cmrs-event_modal_warning");
        }
        return null;
    },

    setModalEventWarningTemplate(show_modal = false) {
        let modal = $(document).find('#modal-event-warning .form-container');
        let new_modal_content = agenda.getModalEventWarningLocalStorage();

        if (modal.length > 0 && new_modal_content !== null) {
            modal.empty();
            modal.append(new_modal_content);
            ui.jquerUi.selectmenu();
            if (show_modal) {
                agenda.activePopupEventWarning()
            }
        }
    },

    loadModalEventWarning() {
        let modal = $(document).find('#modal-event-warning');

        if (modal.length > 0) {
            if (agenda.isValidModalEventWarningLocalStorage()) {
                agenda.setModalEventWarningTemplate()
            } else {
                agenda.ajaxGetEventWarningTemplate()
            }
        }
    },

    showModalEventWarning() {
        let modal = $(document).find('#modal-event-warning');

        if (modal.length > 0) {
            if (agenda.isValidModalEventWarningLocalStorage()) {
                agenda.setModalEventWarningTemplate(true)
            } else {
                agenda.ajaxGetEventWarningTemplate(true)
            }
        } else {
            window.location.href = window.location.origin
        }
    },

    activePopupEventWarning() {
        UIkit.modal('#modal-event-warning', {'bgClose': false}).show().then(function () {
            let search_page = $(document).find('.search-product-list');

            if (search_page.length > 0) {
                let modal_form = $(document).find('body .modal-select-salon-event, .modal-select-salon-city');
                modal_form.prepend('<input type="hidden" name="old-query" value="1">');
            }
        })
    },

    setSearchFormEventType(event_type) {
        let search_form_type = $(document).find('#custom-product-search-form input[name="event_type"]');
        if (search_form_type.length > 0) {
            search_form_type.val(event_type)
        }
    },

    setCheckoutFormEventType() {
        let checkout_form_type = $(document).find('#cmrs-event-type-select');
        if (checkout_form_type.length > 0) {
            let the_event_type = checkout_form_type.val();
            if (the_event_type.length < 1 || the_event_type === 'null') {
                checkout_form_type.val(agenda.getEventDataTypeLocalStorage())
            }
        }

        let checkout_form_slug = $(document).find('#form__event-slug');
        if (checkout_form_slug.length > 0) {
            let the_event_slug = checkout_form_slug.val();
            if (the_event_slug.length < 1 || the_event_slug === 'null') {
                checkout_form_slug.val(agenda.getEventDataSalonLocalStorage())
            }
        }
    },

    refreshServerSideEventData(event_type) {
        let event_salon = agenda.getEventDataSalonLocalStorage();
        if (event_type !== null) {
            agenda.ajaxSendEventData(event_salon, event_type, null, false)
        }
    },

    printToPdf() {
        let btn = $('.print-to-pdf');

        btn.on('click', function (e) {
            btn.loading(true);
            let form = $('#salon_filter_form');
            let dataForm = form.serializeArray();
            let current_lang = $(document).find('html').attr('lang');

            dataForm.push({name: 'action', value: 'salon_filter_form'});
            dataForm.push({name: 'lang', value: current_lang});
            dataForm.push({name: 'print', value: 1});

            function done(response, textStatus, jqXHR) {
                if (response.success === true) {
                    if (response.data.pdf_url !== undefined) {
                        print(response.data.pdf_url)
                    }
                }
                btn.loading(false)
            }

            function fail(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                btn.loading(false)
            }

            component.do_ajax('json', dataForm, done, fail)
        })

    },
    switcher_manage() {
        $(document).on('click', '[data-uk-slider] [data-uk-switcher] li', function () {
            const $this = $(this);
            const index = $this.index();
            const $siblings = $this.siblings();
            const $body = $this.closest('.block-body');

            if (index == 0) {
                $(document).find('.uk-switcher .inner').removeClass('uk-active');
                $(document).find('.uk-switcher .inner').each(function (i) {
                    if (i === 0) {
                        $(this).addClass('uk-active');
                    }
                });
            } else {
                UIkit.switcher($body).show(index)
            }
        })
    }
};

/**
 * SHOP Function
 * @type {{loop_quantity: shop.loopQuantity}}
 */
let shop = {
    loopQuantity: function () {
        let el_quantity = $(document).find('.block.product .num-spinner input, .card.card-suggestions__product input, .block-productlist__item .num-spinner input');
        let quantity = 1;
        el_quantity.on('change', function () {
            quantity = $(this).val();
            let btn = $(this).closest('.block-content, .card-content').find('.add_to_cart_button');
            btn.attr('data-quantity', quantity);
            if (btn.hasClass('custom-variation-type')) {
                let product_id = btn.attr('data-product_id');
                let variation_id = btn.attr('data-variation_id');
                let pa_city = btn.attr('data-pa_city');
                let pa_color = btn.attr('data-pa_color');

                let the_url = location.protocol + '//' + location.host + location.pathname + '?add-to-cart=' + product_id + '&variation_id=' + variation_id + '&quantity=' + quantity + '&attribute_pa_city=' + pa_city + '&attribute_pa_color=' + pa_color;

                btn.attr('href', the_url)
            }
        })
    },
    loopAddToCartBtn() {
        let btn = $(document).find('.custom-variation-type');

        btn.on('click', function (e) {
            $(this).loading(true)
        })
    },
    loaderAddToCart: function () {
        $('.single_add_to_cart_button, .btn-cart-to-checkout, .btn-to-cart, .btn-search').on('click', function () {
            $(this).loading(true);
            $(this).css('pointer-events', 'none')
        })
    },
    onChangeFilter: function () {
        $('#product__filter-filter').on('change', function () {
            window.location.href = $(this).find('option:selected').attr('data-url')
        })
    },
    onSelectPagination: function () {
        let p_loop = $(document).find('.product-loop');

        p_loop.on('click', '.block-product__pagination.pagination-ajax li a', function () {
            let selected_page = parseInt($(this).attr('data-page'));
            shop.filterProductLoop(false, selected_page)
        })
    },
    onSelectFilter: function () {
        let form = $('.cmrs-custom-filter');

        form.on('change', '.c-selector', function () {
            shop.filterProductLoop()
        });
        form.on('change', '.c-checkbox', function () {
            shop.filterProductLoop()
        });
        form.on('change', '.default-check', function () {
            form.find('.c-checkbox').prop('checked', false);
            shop.filterProductLoop()
        })
    },
    isScrolledIntoView(elem) {
        if ($(elem).length > 0) {
            let docViewTop = $(window).scrollTop();
            let docViewBottom = docViewTop + $(window).height();

            let elemTop = $(elem).offset().top;
            let elemBottom = elemTop + $(elem).height();

            return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop))
        }
        return false
    },
    isAjaxLoading: function (el) {
        let page_loader = $(document).find(el);

        if (page_loader.length > 0) {
            let data = page_loader.attr('data-load');

            if (data === '1') {
                return true
            }
            if (data === '0') {
                return false
            }
        }
    },
    setAjaxLoading: function (data, el) {
        let page_loader = $(document).find(el);

        if (page_loader.length > 0) {
            page_loader.attr('data-load', data)
        }
    },
    onScrollDownProductList: function () {
        $(window).on('scroll', function () {
            let element = document.querySelector('#product-ajax-load-more');
            if (shop.isAjaxLoading('#page-load-more') === false && shop.isScrolledIntoView(element)) {
                let form = $(document).find('.cmrs-custom-filter');
                let paged = parseInt(form.find('input[name="page-load-more"]').val());
                let params = new window.URLSearchParams(window.location.search);
                let is_all_view = params.get('view');

                if (is_all_view === 'all') {
                    shop.filterProductLoop(true, paged + 1)
                }
            }
        })
    },
    onScrollDownProductSearch: function () {
        $(window).on('scroll', function () {
            let element = document.querySelector('#product-ajax-load-more');
            if (shop.isAjaxLoading('#page-search-load-more') === false && shop.isScrolledIntoView(element)) {
                let form = $(document).find('#custom-product-search-form');
                let paged = parseInt(form.find('input[name="page-search-load-more"]').val());
                let params = new window.URLSearchParams(window.location.search);
                let is_all_view = params.get('view');

                if (is_all_view === 'all') {
                    shop.filterSearchProductLoop(true, paged + 1)
                }
            }
        })
    },
    filterProductLoop: function (load_more = false, paged = 1) {
        let form = $('.cmrs-custom-filter');
        let p_loop = $(document).find('.product-loop');
        let formData = form.serializeArray();
        let notice = $(document).find('.block-product__list .block.block-notifications');
        let load_more_el = form.find('input[name="page-load-more"]');

        shop.setAjaxLoading('1', '#page-load-more');

        if (formData.length > 0) {
            p_loop.addClass('loading');
            $('.block-product__filter').removeClass('active');

            formData.push({name: 'action', value: 'product_filter_form'});
            formData.push({name: 'paged', value: paged});
            formData.push({name: 'current_url', value: window.location.href});
            formData.push({name: 'lang', value: $('html')[0].lang});

            function done(response, textStatus, jqXHR) {
                let load_more_ancre = $(document).find('.block-product__list .product-ajax-load-more');
                notice.remove();
                if (response.success === true) {
                    if (response.data.products !== undefined) {
                        if (load_more) {
                            load_more_el.val(response.data.current_page);
                            load_more_ancre.remove()
                        } else {
                            p_loop.find('.block.block-product__link').remove();
                            p_loop.find('.woocommerce-info').remove();
                            if (load_more_el.length > 0) {
                                load_more_el.val(1)
                            }
                        }
                        p_loop.find('.block-product__pagination').remove();
                        p_loop.append(response.data.products);
                        p_loop.append(response.data.pagination)
                    }
                } else {
                    console.log(response)
                }
                p_loop.removeClass('loading');
                shop.setAjaxLoading('0', '#page-load-more')
            }

            function fail(jqXHR, textStatus, errorThrown) {
                p_loop.removeClass('loading');
                console.log(errorThrown);
                shop.setAjaxLoading('0', '#page-load-more')
            }

            component.do_ajax('json', formData, done, fail)
        }
    },
    filterSearchProductLoop: function (load_more = false, paged = 1) {
        let form = $('#custom-product-search-form, #custom-product-ordering-form');
        let p_loop = $(document).find('.search-product-list .block-product__list ');
        let formData = form.serializeArray();
        let load_more_el = form.find('input[name="page-search-load-more"]');

        shop.setAjaxLoading('1', '#page-search-load-more');

        if (formData.length > 0) {
            p_loop.addClass('loading');

            formData.push({name: 'action', value: 'product_search_filter_form'});
            formData.push({name: 'paged', value: paged});
            formData.push({name: 'current_url', value: window.location.href});
            formData.push({name: 'lang', value: $('html')[0].lang});

            function done(response, textStatus, jqXHR) {
                let load_more_ancre = $(document).find('.search-product-list .block-product__list .product-ajax-load-more');
                if (response.success === true) {
                    if (response.data.products !== undefined) {
                        if (load_more) {
                            load_more_el.val(response.data.current_page);
                            load_more_ancre.remove()
                        } else {
                            p_loop.find('.block.block-product__link').remove();
                            if (load_more_el.length > 0) {
                                load_more_el.val(1)
                            }
                        }
                        p_loop.find('.block-product__pagination').remove();
                        if (response.data.count > 0) {
                            p_loop.append(response.data.products);
                            p_loop.append(response.data.pagination)
                        }
                        p_loop.find('.block-product__list .block-notifications').remove()
                    }
                } else {
                    console.log(response)
                }
                p_loop.removeClass('loading');
                shop.setAjaxLoading('0', '#page-search-load-more')
            }

            function fail(jqXHR, textStatus, errorThrown) {
                p_loop.removeClass('loading');
                console.log(errorThrown);
                shop.setAjaxLoading('0', '#page-search-load-more')
            }

            component.do_ajax('json', formData, done, fail)
        }
    }
};

/**
 * jQuery Ajax Csrf
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $(document).find('meta[name="csrf-token"]').attr('content')
    }
});

/**
 * Refresh security token
 * @type {{refresh_csrf_token(): void}}
 */
const secu_manager = {
    refresh_csrf_token() {
        let cookie_token = component.readCookie('XSRF-TOKEN');
        if (cookie_token && cookie_token != false && typeof cookie_token !== 'undefined') {
            $('meta[name="csrf-token"]').attr('content', cookie_token);
            $('input[name="_token"]').attr('value', cookie_token);
            $(document).find('.header-menu-btn-search').removeAttr('disabled');
            let btn_text = $(document).find('.header-menu-btn-search span').attr('data-btn-text');
            $(document).find('.header-menu-btn-search span').text(btn_text)
        } else {
            let formData = [];
            let token = $('meta[name="csrf-token"]').attr('content');
            if (token.length > 0) {
                formData.push({name: 'action', value: 'refresh_csrf_token'});
                formData.push({name: 'token', value: token});

                function done(response, textStatus, jqXHR) {
                    if (response.data.token != false) {
                        $('meta[name="csrf-token"]').attr('content', response.data.token);
                        $('input[name="_token"]').attr('value', response.data.token)
                    }
                    $(document).find('.header-menu-btn-search').removeAttr('disabled');
                    let btn_text = $(document).find('.header-menu-btn-search span').attr('data-btn-text');
                    $(document).find('.header-menu-btn-search span').text(btn_text)
                }

                function fail(jqXHR, textStatus, errorThrown) {
                    console.log(response)
                }

                component.do_ajax('json', formData, done, fail)
            }
        }
    }
};

/**
 * Dynamic content
 */
const dynamic_content = {
    refresh_header_panel() {
        let formData = [];
        let account_panel = $('#topbar .account');
        let search_panel = $('#topbar .search');
        let mini_cart = $('#topbar .mini-cart-container');

        if (account_panel.length > 0 && search_panel.length > 0) {
            formData.push({name: 'action', value: 'cmrs_dynamic_content_header_panel'});
            formData.push({name: 'lang', value: $('html')[0].lang});

            function done(response, textStatus, jqXHR) {
                if (typeof response.data.renew_event != 'undefined' && response.data.renew_event != false) {
                    agenda.saveEventDataLocalStorage('event_salon', response.data.event_salon);
                    agenda.saveEventDataLocalStorage('event_template_view', response.data.event_template_view);
                    agenda.saveEventDataLocalStorage('event_type', response.data.event_type);
                    agenda.saveEventDataLocalStorage('event_time', response.data.event_time);
                    agenda.setSearchFormEventType(response.data.event_type);
                }
                if (response.data.account_panel != false) {
                    search_panel.after(response.data.account_panel);
                    account_panel.remove()
                }
                if (mini_cart.length > 0) {
                    mini_cart.empty();
                    mini_cart.append(response.data.cart)
                }
            }

            function fail(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown)
            }

            component.do_ajax('json', formData, done, fail)
        }
    }
};

/**
 * SHOP single
 */
let shop_single = {
    singleProductQuantity: function () {
        let custom_quantity = $('input[name="product__characteristics-quantity"]');
        let orignal_quantity = $('input[name="quantity"]');
        let quantity = 1;
        custom_quantity.on('change', function () {
            quantity = $(this).val();
            orignal_quantity.val(quantity)
        })
    },
    onChangeColor: function () {
        $('#product_pa_color').on('change', function () {
            let url = $(this).find('option:selected').attr('data-url');
            if (url !== undefined) {
                window.location.replace(url)
            }
        })
    },
    imageLightbox: function () {
        $(document).on('click', '.size-single-product-thumbnail', function (e) {
            e.preventDefault();
            shop_single.makeLightbox('.woocommerce-product-gallery__wrapper')
        });
        $(document).on('click', '.schema-block img', function (e) {
            e.preventDefault();
            shop_single.makeLightbox('.show-in-lightbox-schema')
        });
        $(document).on('click', '.show-in-lightbox-slide img', function (e) {
            e.preventDefault();
            shop_single.makeLightbox('.show-in-lightbox-slide')
        })
    },
    makeLightbox: function (box_class) {
        UIkit.lightbox(box_class).show();
        setTimeout(function () {
            let lang = $(document).find('html').attr('lang');
            let btn_text = "Télécharger l'image";
            if (lang != 'fr-FR') {
                btn_text = 'Download image'
            }
            $(document).find('.uk-lightbox .uk-lightbox-items li').each(function (el, i) {
                let url = $(this).find('img').attr('src');
                $(this).append('<a href="' + url + '" class="lightbox-link" target="_blank" download>' + btn_text + '</a>')
            })
        }, 2000)
    }
};

/**
 * handle paiment behaviour
 */

let checkout_process = {
    //load-2-3 load-3-4 load 4-5
    stape_handler: function (_elmt) {
        $(document).find('.woocommerce-checkout').on('click', _elmt, function (e) {
            e.preventDefault();
            if (_elmt == '#load-2-3') {
                /*
                let is_logged = $("#is_logged").val();
                if(is_logged == '0'){
                  $("html, body").animate({ scrollTop: 0 }, "slow");
                  return false;
                }
                */
                /********* Disable not used **************************************
                 let checkoutForm = $('form.woocommerce-checkout');
                 let dataFormCheckout = checkoutForm.serializeArray();
                 function done(response, textStatus, jqXHR) {
                 console.log(response);
                 if (response.success === true) {

                 } else {
                 console.log(response);
                 }
                 }
                 function fail(jqXHR, textStatus, errorThrown) {
                 console.log(errorThrown);
                 }
                 dataFormCheckout.push({name: 'action', value: 'validate_form_checkout'});
                 component.do_ajax('json', dataFormCheckout, done, fail)
                 ******** disable not used **************************************/
                $('.stape-2').hide();
                $('.stape-3').show();
                $('#current_checkout_stape').val(3);
                checkout_process.behaviour('#current_checkout_stape');
                $('html, body').animate({scrollTop: 0}, 'slow');
                checkout_process.init_stape_nav();
                $('ul.nav-checkout-stape li:nth-child(3)').addClass('active')
            }

            if (_elmt == '#load-3-4') {
                $('.stape-3').hide();
                $('.stape-4-1').show();
                checkout_process.event_form_handler();
                $('.stape-4-2').show();
                $('#current_checkout_stape').val(4);
                checkout_process.behaviour('#current_checkout_stape');
                $('html, body').animate({scrollTop: 0}, 'slow');
                checkout_process.init_stape_nav();
                $('ul.nav-checkout-stape li:nth-child(4)').addClass('active')
            }

            if (_elmt == '#is_stape_4') {
                if ($('#current_checkout_stape').val() == '4') {
                    $('button#place_order').hide();
                    $('.stape-4-1').hide();
                    $('.stape-4-2').hide();
                    $('.stape-5').show();
                    $(_elmt).hide();
                    $('html, body').animate({scrollTop: 0}, 'slow');
                    $('#launch-order').on('click', function () {
                        $('#place_order').trigger('click')
                    });
                    $('#current_checkout_stape').val(5);
                    checkout_process.init_stape_nav();
                    $('ul.nav-checkout-stape li:nth-child(5)').addClass('active');
                    checkout_process.listen_error_checkout()
                }
            }
        })
    },
    behaviour: function (_stape) {
        //$('#current_checkout_stape').val()
        if ($(_stape).val() == '2') {
            $('#is_stape_4').hide();
            $('#edit_cart').show();
            $('#billing_dematerialized_invoice_field .woocommerce-input-wrapper').find('*').css({
                'display': 'inline',
                'padding': '10px'
            });
            $('button[name="login_custom"]').on('click', function (e) {
                e.preventDefault();
                let username_custom = $('#username_custom').val();
                let password_custom = $('#password_custom').val();
                $('#username').val(username_custom);
                $('#password').val(password_custom);
                $('button[name="login"]').trigger('click')
            })
        }
        if ($(_stape).val() == '3') {
            $('#is_stape_4').hide();
            $('#edit_cart').show()
        }

        if ($(_stape).val() == '4') {
            $('#is_stape_4').show();
            $('#edit_cart').hide()
        }
    },
    callback: function () {

    },
    listen_error_checkout: function () {
        $(document.body).on('checkout_error', function () {
            if ($('ul.woocommerce-error').length != 0) {
                $('.stape-2').show();
                $('#edit_cart').show();
                $('.stape-3').hide();
                $('.stape-4-1').hide();
                $('.stape-4-2').hide();
                $('.stape-5').hide()
            }
        })
    },
    init_stape_nav: function () {
        $('ul.nav-checkout-stape').find('li').each(function (e, elm) {
            $(elm).removeAttr('class')
        })
    },
    event_form_handler: function () {
        let event_name = $('#form__event-name').val();
        let event_place = $('#form__event-place').val();
        let event_date = $('#form__event-date').val();
        let event_city = $('#form__event-city').val();
        let event_stand = $('#form__event-stand').val();
        let event_hall = $('#form__event-hall').val();
        let event_wing = $('#form__event-wing').val();
        let event_number = $('#form__event-number').val();

        $('#event_name_detail').text(event_name);
        $('#event_lieu_detail').text(event_city + ' ' + event_place);
        $('#event_date_detail').text(event_date);
        $('#event_stand_detail').text(event_stand);
        $('#event_hall_detail').text(event_hall);
        $('#event_wing_detail').text(event_wing);
        $('#event_num_detail').text(event_number)
    },
    handle_process_checkout: function () {
        $(document).find('body').on('click', 'a.nav-process', function () {

            let stape = parseInt($(this).attr('data-process'));

            if (stape == 2) {
                $('.stape-2').show();
                $('#edit_cart').show();
                $('.stape-3').hide();
                $('.stape-4-1').hide();
                $('.stape-4-2').hide();
                $('#is_stape_4').hide();
                $('.stape-5').hide();
                checkout_process.init_stape_nav();
                $('ul.nav-checkout-stape li:nth-child(2)').addClass('active');
                $('#current_checkout_stape').val(2)
            }

            if (stape == 3) {
                $('.stape-2').hide();
                $('#is_stape_4').hide();
                $('#edit_cart').show();
                $('.stape-3').show();
                $('.stape-4-1').hide();
                $('.stape-4-2').hide();
                $('.stape-5').hide();
                checkout_process.init_stape_nav();
                $('ul.nav-checkout-stape li:nth-child(3)').addClass('active');
                $('#current_checkout_stape').val(3)
            }

            if (stape == 4) {
                $('.stape-2').hide();
                $('#is_stape_4').show();
                $('#edit_cart').hide();
                $('.stape-3').hide();
                $('.stape-4-1').show();
                $('.stape-4-2').show();
                $('.stape-5').hide();
                checkout_process.init_stape_nav();
                $('ul.nav-checkout-stape li:nth-child(4)').addClass('active');
                $('#current_checkout_stape').val(4)
            }

            if (stape == 5) {
                $('.stape-2').hide();
                $('#is_stape_4').hide();
                $('#edit_cart').show();
                $('.stape-3').hide();
                $('.stape-4-1').hide();
                $('.stape-4-2').hide();
                $('.stape-5').show();
                $('button#place_order').hide();
                $('#launch-order').on('click', function () {
                    $('#place_order').trigger('click')
                });
                checkout_process.init_stape_nav();
                $('ul.nav-checkout-stape li:nth-child(5)').addClass('active');
                $('#current_checkout_stape').val(5)
            }

        })
    },
    loader: function () {

        function callbackStart(event) {
            $('#main').addClass('loading')
        }

        function callbackEnd(event) {
            $('#main').removeClass('loading')
        }

        $('#launch-order').on('click', function (e) {
            e.stopPropagation();
            component.detect_ajax(callbackStart, callbackEnd)
        })
    },

    onChangeCheckoutCompany: function () {
        $('#billing_company').on('change', function () {
            $('#form__event-stand').val($(this).val())
        })
    },

    eventDateManager: function () {
        let minDate = new Date();

        let optional_config = {
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "d-m-Y",
            minDate: minDate,
            locale: French
        };

        $(".cpm-flatpickr").flatpickr(optional_config);
    }
};

/**
 * Modal manager
 */
let modal_manager = {
    reset(modal) {
        modal.find('.modal-title').text('Confirmation');
        modal.find('.modal-text').text('Êtes-vous sure de vouloir faire cette action ?');
        modal.find('.modal-next-btn').attr('href', '');
        modal.find('.modal-next-btn').attr('class', 'btn btn-2 btn-w_a modal-next-btn')
    },
    modalConfirm(btn, data = {title: 'Confirmation', text: '', next_link: '', add_class: ''}, callback) {
        let modal = $('#modal-warning');

        modal.find('.modal-title').text(data.title);
        modal.find('.modal-text').text(data.text);
        modal.find('.modal-next-btn').attr('href', data.next_link);
        modal.find('.modal-next-btn').addClass(data.add_class);

        UIkit.modal('#modal-warning', {'bgClose': false}).show();

        modal.find('.modal-next-btn').on('click', function (e) {
            e.preventDefault();
            callback && callback(true);
            UIkit.modal('#modal-warning').hide();
            modal_manager.reset(modal)
        });

        modal.find('.modal-cancel-btn').on('click', function (e) {
            e.preventDefault();
            callback && callback(false);
            UIkit.modal('#modal-warning').hide();
            modal_manager.reset(modal);

            btn.css('pointer-events', 'auto')
        })
    },
    cancelBtn() {
        $('.modal-manager .modal-cancel-btn').on('click', function (e) {
            e.preventDefault();

            UIkit.modal('#modal-warning').hide();
            modal_manager.reset($('#modal-warning'))
        })
    },
    warning($title = 'Confirmation', $text = '', $next_link = '', $add_class = '') {
        let modal = $('#modal-warning');

        modal.find('.modal-title').text($title);
        modal.find('.modal-text').text($text);
        modal.find('.modal-next-btn').attr('href', $next_link);
        modal.find('.modal-next-btn').addClass($add_class)

        // UIkit.modal('#modal-warning').show()
    },

    warning_close() {
        let modal = $('#modal-event-warning');

        modal.on('click', '.uk-modal-close', function (e) {
            let btn = $(document).find('.add_to_cart_button');
            let single_btn = $(document).find('.single_add_to_cart_button');

            btn.removeAttr('style');
            btn.loading(false);
            btn.removeAttr('disabled');

            single_btn.removeAttr('style');
            single_btn.loading(false);
            single_btn.removeAttr('disabled');
        })
    },

    homeMessage() {
        let modal = $(document).find('#modal-custom-message');

        if (modal.length > 0) {
            let cookie_name = 'camerus-ph-vs';
            let cookie_date = component.readCookie(cookie_name);
            let todayDate = new Date().toISOString().slice(0, 10);

            if (cookie_date == null || cookie_date !== todayDate) {
                component.createCookie(cookie_name, todayDate, 1);

                UIkit.modal('#modal-custom-message', {'bgClose': false}).show()
            }
        }
    }
};

/**
 * My account
 */
let my_account = {
    beforeDeleteAccount() {
        let btn = $(document).find('#cmrs-btn-delete-user-account');

        let data = {
            title: 'Supprimer mon compte',
            text: 'Êtes-vous sure de vouloir supprimer complètement votre compte sur Camerus ?',
            next_link: btn.attr('href')
        };

        btn.on('click', function (e) {
            e.preventDefault();

            modal_manager.modalConfirm(btn, data)
        })
    }
};

/**
 * Manage PDF
 */
let pdf = {

    pdfPrint(quality = 1, filename = 'Camerus-product.pdf') {
        html2canvas(document.querySelector('#pdf-inner'),
            {scale: quality}
        ).then(canvas => {
            let contentWidth = canvas.width;
            let contentHeight = canvas.height;

            let pdf = new jsPDF('', 'pt', 'a4');
            let width = pdf.internal.pageSize.getWidth();
            let height = pdf.internal.pageSize.getHeight();
            let nWidth = height * (contentWidth / contentHeight);
            //One page pdf shows the canvas height generated by html pages.
            // let pageHeight = contentWidth / 592.28 * 841.89
            //html page height without pdf generation
            // let leftHeight = contentHeight
            //Page offset
            // let position = 0
            //a4 paper size [595.28, 841.89], html page generated canvas in pdf picture width
            // let imgWidth = 595.28
            // let imgHeight = 592.28 / contentWidth * contentHeight
            let pageData = canvas.toDataURL('image/jpeg', 1.0);

            //There are two heights to distinguish, one is the actual height of the html page, and the page height of the generated pdf (841.89)
            //When the content does not exceed the range of pdf page display, there is no need to paginate
            if (contentHeight > height) {
                pdf.addImage(pageData, 'JPEG', 0, 0, nWidth, height)
            } else {
                pdf.addImage(pageData, 'JPEG', 0, 0, contentWidth, contentHeight)
            }
            // if (leftHeight < pageHeight) {
            //     pdf.addImage(pageData, 'JPEG', 0, 0, nWidth, height)
            // } else {
            //     while (leftHeight > 0) {
            //         pdf.addImage(pageData, 'JPEG', 0, position, nWidth, height)
            //         leftHeight -= pageHeight
            //         position -= 841.89
            //         //Avoid adding blank pages
            //         if (leftHeight > 0) {
            //             pdf.addPage()
            //         }
            //     }
            // }
            pdf.save(filename)
            // $(document).find('#pdf-inner').hide()
        }).then((canvas) => {
            $('.pdf-notice .onload').addClass('hide');
            $('.pdf-notice .success').removeClass('hide')
        })
    },

    onGeneratePdf() {
        $('#generate-pdf').click(function () {
            pdf.pdfPrint(2)
        })
    }
};

/**
 * Search page
 */
const search_page = {
    onSelectPagination: function () {
        let p_loop = $(document).find('.search-product-list');

        p_loop.on('click', '.block-product__pagination.pagination-ajax li a.paginate', function () {
            let selected_page = parseInt($(this).attr('data-page'));
            let form = $('#custom-product-search-form');
            let ordeby = $('#custom-product-ordering-form select.select').val();
            form.find('.custom-product-search-form-paged').val(selected_page);
            form.find('.custom-product-search-form-ordeby').val(ordeby);
            form.submit()
        })
    },
    onClickProductSearch() {
        let item_btn = $(document).find('#page');

        item_btn.on('click', '.search-product-list .block-content .block-header', function (e) {
            e.preventDefault();

            let url = $('#cmrs-search-prev-url').val();
            if (url.length > 0) {
                url = url + '/?old-query=1';
                search_page.savePrevSearchSession(url);
                window.location = $(this).attr('href');
            }
        })
    },
    showPrevBtnSearchProduct() {
        let url = search_page.getPrevSearchSession();

        if (url !== null && $('.prev-btn-search').length > 0) {
            $('.prev-btn-search').removeClass('hide');
            $('.prev-btn-search .prev-url').attr('href', url)
        }
        sessionStorage.removeItem('cmrs-search-prev-url')
    },
    savePrevSearchSession(url) {
        sessionStorage.setItem('cmrs-search-prev-url', url)
    },
    getPrevSearchSession() {
        return sessionStorage.getItem('cmrs-search-prev-url')
    }
};

/**
 * Reed : dotation, product, salon
 * @type {{viewDotationItem: reed.viewDotationItem}}
 */
const reed = {
    viewDotationItem: function () {
        let btn = $('.dotation-products');
        btn.on('click', '.dotation-item', function (e) {
            e.preventDefault();
            let p_id = parseInt($(this).attr('data-p_id'));
            let collection = $('#layout .productlist__item-collection');
            if (p_id !== 0) {
                collection.addClass('loading');
                let dataForm = [
                    {name: 'action', value: 'get_reed_dotation_item'},
                    {name: 'product', value: p_id}
                ];

                function done(response, textStatus, jqXHR) {
                    let container = $('.modal-dotation-product-item');
                    if (response.success === true) {
                        if (response.data.product !== undefined) {
                            container.empty().append(response.data.product);
                            UIkit.modal('#modal-product').show();
                            console.log(response.data.product)
                        }
                        collection.removeClass('loading')
                    } else {
                        console.log(response);
                        collection.removeClass('loading')
                    }
                }

                function fail(jqXHR, textStatus, errorThrown) {
                    collection.removeClass('loading');
                    console.log(errorThrown)
                }

                component.do_ajax('json', dataForm, done, fail)
            }
        })
    },
    checkTotalLimit: function (quantity) {
        let d_list = $('.dotation-pr-list');
        let item_limit = parseInt($('#dotation_add_limit').val());
        let all_item = d_list.find('.dotation_add_product_input');
        let q_total = 0;
        if (all_item.length > 0) {
            all_item.each(function (i) {
                let _q = parseInt($(this).attr('data-pr-q'));
                q_total = q_total + _q
            })
        }
        q_total = q_total + quantity;

        return q_total <= item_limit
    },
    checkExistProduct: function (product_id) {
        let d_list = $('.dotation-pr-list');
        let add_items = d_list.find('.dotation-add-item');
        let dt_product = $('.dotation-products');

        if (add_items.length > 0) {
            add_items.each(function () {
                let p_id = parseInt($(this).attr('data-p_id'));

                if (p_id == product_id) {
                    dt_product.find('.dt-pr-item-' + product_id).remove();
                    return false
                }
            })
        }
    },
    addProductToDotation: function () {
        let btn = $('.dt-btn-add');
        let d_list = $('.dotation-pr-list');
        let dt_product = $('.dotation-products');
        let dt_list_product = $('.dotation-item-comp');

        btn.on('click', function (e) {
            e.stopPropagation();
            let product_id = parseInt($(this).attr('data-dtid'));
            let dotation_id = parseInt($('#dotation_id').val());
            let quantity_input = $(this).attr('data-dtq');
            let quantity = parseInt($('.' + quantity_input).val());

            if (product_id > 0 && quantity > 0 && reed.checkTotalLimit(quantity)) {
                let pr_bloc_data = $(this).attr('data-dtp');
                let pr_bloc = $('.' + pr_bloc_data);
                let pr_title = $(this).attr('data-dtp-title');
                let pr_img_url = $(this).attr('data-dtp-img');

                let item_template = `
                 <div class="block block-productlist__item uk-width-1-1 uk dt-pr-item-${product_id}">
                    <div class="block-content dotation-item dotation-add-item" data-p_id="${product_id}">
                    <input type="hidden" 
                            class="dotation_add_product_input" 
                            data-pr-q="${quantity}" 
                            name="dotation_added_product[]" 
                            value="${product_id}:${quantity}">
                        <div class="uk-grid uk-grid-small">
                            <div class="block-aside uk-width-1-3">
                                <div class="img-container img-middle" title="${pr_title}">
                                    <img src="${pr_img_url}" width="69" height="91" class="" alt="${pr_title}" srcset="${pr_img_url}"/>
                                </div>
                            </div><!-- .block-header -->
                            <div class="block-body uk-width-2-3">
                                <div class="top">
                                    <div class="left">
                                        <h2 class="title">
                                            ${pr_title}
                                        </h2>
                                    </div>
                                    <div class="right">
                                        <strong>
                                            Quantité: X${quantity}
                                        </strong>
                                    </div>
                                </div><!-- .block-body -->
                                <div class="bottom">
                                    <div>
                                    </div>
                                    <div>
                                        <div class="cta-container">
                                            <button type="button" style="z-index: 500;" class="btn btn-c_line btn-bdc_line btn-tt_u btn-remove dt-btn-remove" data-prid="${product_id}">
                                                <span class="visible-xs">x</span>
                                                <span class="hidden-xs">Supprimer</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .block-body -->
                        </div>
                    </div><!-- .block-content -->
                </div>
                `;
                dt_product.addClass('loading');
                dt_list_product.addClass('loading');
                let dataForm = [
                    {name: 'action', value: 'add_reed_dotation_item'},
                    {name: 'dotation', value: dotation_id},
                    {name: 'product', value: product_id},
                    {name: 'quantity', value: quantity}
                ];

                function done(response, textStatus, jqXHR) {
                    if (response.success === true) {
                        reed.checkExistProduct(product_id);
                        d_list.append(item_template)
                    } else {
                        console.log(response)
                    }
                    dt_product.removeClass('loading');
                    dt_list_product.removeClass('loading')
                }

                function fail(jqXHR, textStatus, errorThrown) {
                    dt_product.removeClass('loading');
                    dt_list_product.removeClass('loading');
                    console.log(errorThrown)
                }

                component.do_ajax('json', dataForm, done, fail)
            }
        })
    },
    removeFromDotation: function () {
        let bloc = $('.dotation-products');
        let dt_list_product = $('.dotation-item-comp');

        bloc.on('click', '.dt-btn-remove', function (e) {
            e.stopPropagation();
            bloc.addClass('loading');
            dt_list_product.addClass('loading');
            let product_id = parseInt($(this).attr('data-prid'));

            if (product_id) {
                let dataForm = [
                    {name: 'action', value: 'remove_reed_dotation_item'},
                    {name: 'product', value: product_id},
                ];

                function done(response, textStatus, jqXHR) {
                    if (response.success === true) {
                        $('.dt-pr-item-' + product_id).remove()
                    } else {
                        console.log(response)
                    }
                    bloc.removeClass('loading');
                    dt_list_product.removeClass('loading')
                }

                function fail(jqXHR, textStatus, errorThrown) {
                    bloc.removeClass('loading');
                    dt_list_product.removeClass('loading');
                    console.log(errorThrown)
                }

                component.do_ajax('json', dataForm, done, fail)
            }
        })
    }
};

const cart = {
    sendProOrder() {
        let btn = $('#procustomer-order-btn');

        btn.on('click', function () {
            $(this).loading(true);
            $(this).css('pointer-events', 'none');

            let dataForm = [
                {name: 'action', value: 'pro_user_quotation'},
            ];

            function done(response, textStatus, jqXHR) {
                if (response.success === true) {
                    btn.text(response.data.message);
                    window.location.href = response.data.redirect_link
                } else {
                    btn.text('Erreur');
                    console.log(response)
                }
                $(this).loading(false)
            }

            function fail(jqXHR, textStatus, errorThrown) {
                $(this).loading(false);
                btn.text('Erreur');
                console.log(errorThrown)
            }

            component.do_ajax('json', dataForm, done, fail)
        })
    },

    makeReduceCredit() {
        let btn = $('#credit-reduce-amount-btn');
        let form = $('#custom-credit-amount-reduce');

        btn.on('click', function () {
            let amount_reduce = form.find('#credit-reduce-amount').val();
            if (amount_reduce != 0 && amount_reduce != 'undefined') {
                let dataForm = [
                    {name: 'action', value: 'cart_reduce_credit'},
                    {name: 'amount', value: amount_reduce},
                    {name: 'lang', value: $('html')[0].lang},
                ];

                if (btn.hasClass('btn-delete')) {
                    dataForm.push({name: 'delete_action', value: '1'})
                }

                btn.loading(true);
                btn.css('pointer-events', 'none');

                function done(response, textStatus, jqXHR) {
                    if (response.success === true) {
                        btn.text(response.data.message);
                        window.location.href = response.data.redirect_link
                    } else {
                        btn.text('Erreur');
                        console.log(response)
                    }
                    btn.loading(false);
                    btn.css('pointer-events', 'unset')
                }

                function fail(jqXHR, textStatus, errorThrown) {
                    btn.loading(false);
                    btn.text('Erreur');
                    console.log(errorThrown)
                }

                component.do_ajax('json', dataForm, done, fail)
            }
        })
    }
};

const download_manager = {
    onClickZipCategory() {
        let btn = $('.zip-category-file');

        btn.on('click', function (e) {
            e.preventDefault();

            let cat_slug = $(this).attr('data-slug');
            if (cat_slug.length > 0) {
                let dataForm = [
                    {name: 'action', value: 'get_category_zip_files'},
                    {name: 'cat_slug', value: cat_slug},
                ];

                function done(response, textStatus, jqXHR) {
                    if (response.success === true) {
                        let popup = $(document).find('#category-zip-download');

                        popup.empty();
                        popup.append(response.data.zip_view_item);

                        UIkit.modal('#modal-category-zip-download', {'bgClose': false}).show()
                    } else {
                        console.log(response)
                    }
                }

                function fail(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown)
                }

                component.do_ajax('json', dataForm, done, fail)
            }
        })
    },
    onClickDownloadLink() {
        $(document).find('body').on('click', '.down-stat-link', function (e) {
            e.preventDefault();

            let _btn = $(this);
            let name = $(this).attr('title');
            let link = $(this).attr('href');
            if (name.length > 0) {
                let dataForm = [
                    {name: 'action', value: 'set_download_stat'},
                    {name: 'name', value: name},
                    {name: 'link', value: link},
                ];

                function done(response, textStatus, jqXHR) {
                    if (response.success === true) {
                        location.href = _btn.attr('href');
                        console.log(response)
                    } else {
                        console.log(response)
                    }
                }

                function fail(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown)
                }

                component.do_ajax('json', dataForm, done, fail)
            }
        })
    }
};

const view_manager = {
    o_hide() {
        let block = $(document).find('.o-hide');
        if (block.length > 0) {
            block.removeClass('o-hide')
        }
    },
    v_none() {
        let block = $(document).find('.v-none');
        if (block.length > 0) {
            block.removeClass('v-none')
        }
    }
};

const home_manager = {
    video_volume() {
        let btn = $(document).find('.block .block-content');

        btn.on('click', '.volume', function (e) {
            let video = $(document).find('#home-video');

            if (video.length > 0) {
                let src = video.attr('src');


                if (video.hasClass('sound-active')) {
                    src = src.replace('muted=0', 'muted=1');
                    video.removeClass('sound-active')
                } else {
                    src = src.replace('muted=1', 'muted=0');
                    video.addClass('sound-active')
                }
                video.attr('src', src);
            }
        })
    },
    iframe_defer() {
        let iframeElem = document.getElementsByTagName('iframe');
        for (let i = 0; i < iframeElem.length; i++) {
            if (iframeElem[i].getAttribute('defer-src')) {
                iframeElem[i].setAttribute('src', iframeElem[i].getAttribute('defer-src'));
            }
        }
    }
};

const single_product = {
    getProductAddToCartDataKey() {
        return 'cmrs-pr-atc-dt';
    },
    saveProductAddToCartData(value) {
        sessionStorage.setItem(single_product.getProductAddToCartDataKey(), JSON.stringify(value));
    },
    getProductAddToCartData() {
        let data = sessionStorage.getItem(single_product.getProductAddToCartDataKey());
        if (data != null) {
            return JSON.parse(data);
        }
        return data;
    },
    deleteProductAddToCartData() {
        sessionStorage.removeItem(single_product.getProductAddToCartDataKey());
    },
    before_add_to_cart(dataForm) {
        single_product.saveProductAddToCartData(dataForm);
        agenda.activePopupEventWarning();
    },
    single_add_to_cart() {
        let single_form_product = $('.variations_form-2.cart');
        single_form_product.on('submit', function (e) {
            e.preventDefault();

            let this_btn = $(this).find('button[type="submit"]');
            let product_id = $(this).find('[name="product_id"]').val();
            let variation_id = $(this).find('[name="variation_id"]').val();
            let quantity = $(this).find('[name="quantity"]').val();
            let color = $(this).find('[name="attribute_pa_color"]').val();
            let event_type = $(this).find('[name="attribute_pa_city"]').val();
            let mini_cart = $('#topbar .mini-cart-container');

            if (product_id && variation_id && quantity) {

                let dataForm = [
                    {name: 'action', value: 'cmrs_product_add_to_cart'},
                    {name: 'product_id', value: product_id},
                    {name: 'variation_id', value: variation_id},
                    {name: 'quantity', value: quantity},
                    {name: 'color', value: color},
                    {name: 'event_type', value: event_type},
                    {name: 'clang', value: $('html')[0].lang},
                ];

                if (agenda.getEventDataTypeLocalStorage() == null) {
                    let productDataForm = [
                        {name: 'product_id', value: product_id},
                        {name: 'variation_id', value: variation_id},
                        {name: 'quantity', value: quantity},
                        {name: 'color', value: color},
                        {name: 'clang', value: $('html')[0].lang},
                    ];
                    single_product.before_add_to_cart(productDataForm);
                    return false;
                }

                this_btn.loading(true);

                function done(response, textStatus, jqXHR) {
                    if (response.success === true) {
                        mini_cart.empty();
                        mini_cart.append(response.data.cart);
                        if (response.data.notices_html) {
                            $.each(response.data.notices_html, function (index, value) {
                                UIkit.notification(value, {
                                    pos: 'top-right',
                                    status: 'primary',
                                    timeout: 35000
                                });
                            })
                        }
                    } else {
                        console.log(response)
                    }
                    this_btn.addClass('single_add_to_cart_button');
                    this_btn.loading(false);
                    this_btn.removeAttr('disabled');
                    this_btn.removeAttr('style');
                }

                function fail(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                    this_btn.addClass('single_add_to_cart_button');
                    this_btn.loading(false);
                    this_btn.removeAttr('disabled');
                    this_btn.removeAttr('style');
                }

                component.do_ajax('json', dataForm, done, fail)
            }
        })
    },
    add_to_cart() {
        let btn = $(document).find('#page');

        btn.on('click', '.add_to_cart_button', function (e) {
            e.preventDefault();

            let this_btn = $(this);
            let product_id = $(this).attr('data-product_id');
            let variation_id = $(this).attr('data-variation_id');
            let quantity = $(this).attr('data-quantity');
            let color = $(this).attr('data-pa_color');
            let event_type = $(this).attr('data-pa_city');
            let mini_cart = $('#topbar .mini-cart-container');

            if (product_id && variation_id && quantity) {

                let dataForm = [
                    {name: 'action', value: 'cmrs_product_add_to_cart'},
                    {name: 'product_id', value: product_id},
                    {name: 'variation_id', value: variation_id},
                    {name: 'quantity', value: quantity},
                    {name: 'color', value: color},
                    {name: 'event_type', value: event_type},
                    {name: 'clang', value: $('html')[0].lang}
                ];

                if (agenda.getEventDataTypeLocalStorage() == null) {
                    let productDataForm = [
                        {name: 'product_id', value: product_id},
                        {name: 'variation_id', value: variation_id},
                        {name: 'quantity', value: quantity},
                        {name: 'color', value: color},
                        {name: 'clang', value: $('html')[0].lang}
                    ];
                    single_product.before_add_to_cart(productDataForm);
                    return false;
                }

                this_btn.removeClass('add_to_cart_button');
                this_btn.attr('style', 'pointer-events: none;');
                this_btn.loading(true);
                this_btn.attr('disabled', 'disabled');

                function done(response, textStatus, jqXHR) {
                    if (response.success === true) {
                        mini_cart.empty();
                        mini_cart.append(response.data.cart);
                        UIkit.notification(response.data.notices_html, {
                            pos: 'top-right',
                            status: 'primary',
                            timeout: 35000
                        });
                    } else {
                        console.log(response)
                    }
                    this_btn.addClass('add_to_cart_button');
                    this_btn.loading(false);
                    this_btn.removeAttr('disabled');
                    this_btn.removeAttr('style');
                }

                function fail(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                    this_btn.addClass('add_to_cart_button');
                    this_btn.loading(false);
                    this_btn.removeAttr('disabled');
                    this_btn.removeAttr('style');
                }

                component.do_ajax('json', dataForm, done, fail)
            }
        });
    },
    load_price_single() {
        let variation_form = $(document).find('.block-product__characteristics .variations_form-2');

        if (variation_form.length > 0) {
            let event_type = agenda.getEventDataTypeLocalStorage();

            if (event_type !== null) {
                let p_city = variation_form.find('[name="attribute_pa_city"]');
                let p_variation_id = variation_form.find('[name="variation_id"]');
                let p_price = $(document).find('.block-product__characteristics .price');
                let variation_data = single_product.get_variation_data(event_type);
                let p_price_tooltip = p_price.attr('data-uk-tooltip');
                let lang = $(document).find('html').attr('lang');
                let price_name = 'HT';
                if (lang != 'fr-FR') {
                    price_name = 'Excl tax';
                }

                p_city.val(variation_data.attributes.attribute_pa_city);
                p_variation_id.val(variation_data.variation_id);
                p_price.empty();
                p_price.append('<span class="woocommerce-Price-amount amount">' + variation_data.display_price + '<span class="woocommerce-Price-currencySymbol">&euro;</span><strong class="price_type">&nbsp;' + price_name + '</strong></span>');

                let event_type_name = component.capitalize(variation_data.attributes.attribute_pa_city);
                event_type_name = event_type_name.replace('_', ' ');
                p_price_tooltip = p_price_tooltip.replace('salon', event_type_name.toUpperCase());
                p_price.attr('data-uk-tooltip', p_price_tooltip);

                $(document).find('.block-load-data').removeClass('load');

                single_product.set_suggestion_data(event_type);
            }
        }
    },
    load_price_loop() {
        let variation_form = $(document).find('.block-product__list');

        if (variation_form.length > 0) {
            let event_type = agenda.getEventDataTypeLocalStorage();
            let event_template_view = agenda.getEventDataTemplateViewLocalStorage();

            if (event_type !== null) {
                let sidebar = $(document).find('.product-sidebar-list');
                let salon_block = sidebar.find('.block-widget__event');
                if (salon_block.length > 0) {
                    salon_block.remove()
                }

                if (event_template_view != '0' && event_template_view !== null) {
                    sidebar.prepend(event_template_view);
                }
                sidebar.find('.block-widget__event').removeClass('load');

                single_product.set_loop_product_data(event_type);
            }
        }
    },
    replaceUrlParam(url, paramName, paramValue) {
        if (paramValue == null) {
            paramValue = '';
        }
        let pattern = new RegExp('\\b(' + paramName + '=).*?(&|#|$)');
        if (typeof url != 'undefined' && url.search(pattern) >= 0) {
            return url.replace(pattern, '$1' + paramValue + '$2');
        }
        url = url.replace(/[?#]$/, '');
        return url + (url.indexOf('?') > 0 ? '&' : '?') + paramName + '=' + paramValue;
    },
    set_suggestion_data(city) {
        let suggestion_product = $(document).find('.card-suggestions__product');

        if (suggestion_product.length > 0) {
            suggestion_product.each(function () {
                let _this = $(this);
                let card = $(this).find('.card-content');
                let content = $(this).find('.card-content').attr('data-product-info');
                content = JSON.parse(content);

                if (content) {
                    let card_item = null;
                    for (let i = 0; i < content.length; i++) {
                        let elem = content[i];
                        if (typeof elem.attribute_pa_city != 'undefined' && elem.attribute_pa_city == city) {
                            card_item = elem;
                        }
                    }

                    if (card_item) {
                        let lang = $(document).find('html').attr('lang');
                        let btn_text = 'Ajouter au panier';
                        let price_name = 'HT';
                        if (lang != 'fr-FR') {
                            btn_text = 'Add to cart';
                            price_name = 'Excl tax';
                        }
                        card.find('.card-body .cart .price').remove();
                        card.find('.card-body .cart').prepend('<div class="price"><strong><span class="woocommerce-Price-amount amount">' + card_item.price + '<span class="woocommerce-Price-currencySymbol">&euro;</span></span></strong><strong class="price_type">&nbsp;' + price_name + '</strong></div>');

                        let btn_container = _this.find('.card-footer');
                        let quantity = 1;
                        let the_url = location.protocol + '//' + location.host + location.pathname + '?add-to-cart=' + card_item.product_id + '&variation_id=' + card_item.variation_id + '&quantity=' + quantity + '&attribute_pa_city=' + card_item.attribute_pa_city + '&attribute_pa_color=' + card_item.attribute_pa_color;

                        btn_container.empty();
                        btn_container.append('<a href="' + the_url + '" class="button product_type_variable add_to_cart_button add_to_cart_button custom-variation-type btn" title="' + btn_text + '" data-quantity="' + quantity + '" data-product_id="' + card_item.product_id + '" data-variation_id="' + card_item.variation_id + '" data-pa_city="' + card_item.attribute_pa_city + '" data-pa_color="' + card_item.attribute_pa_color + '" aria-label="' + btn_text + '" rel="nofollow">' + btn_text + '</a>');

                        card.parent().removeClass('load')
                    } else {
                        card.parent().remove()
                    }
                }
            })
        }
    },
    set_loop_product_data(city) {
        let loop_product = $(document).find('.block.block-product__link');

        if (loop_product.length > 0) {
            loop_product.each(function () {
                let _this = $(this);
                let card = $(this).find('.block-content');
                let content = $(this).find('.block-content').attr('data-product-info');
                content = JSON.parse(content);

                if (content) {
                    let card_item = null;
                    for (let i = 0; i < content.length; i++) {
                        let elem = content[i];
                        if (typeof elem.attribute_pa_city != 'undefined' && elem.attribute_pa_city == city) {
                            card_item = elem;
                        }
                    }

                    if (card_item) {
                        let lang = $(document).find('html').attr('lang');
                        let btn_text = 'Ajouter au panier';
                        let price_name = 'HT';
                        if (lang != 'fr-FR') {
                            btn_text = 'Add to cart';
                            price_name = 'Excl tax';
                        }
                        card.find('.block-body .cart .price').remove();
                        card.find('.block-body .cart').prepend('<div class="price"><strong><span class="woocommerce-Price-amount amount">' + card_item.price + '<span class="woocommerce-Price-currencySymbol">&euro;</span></span></strong><strong class="price_type">&nbsp;' + price_name + '</strong></div>');

                        let btn_container = _this.find('.block-footer');
                        let quantity = 1;
                        let the_url = location.protocol + '//' + location.host + location.pathname + '?add-to-cart=' + card_item.product_id + '&variation_id=' + card_item.variation_id + '&quantity=' + quantity + '&attribute_pa_city=' + card_item.attribute_pa_city + '&attribute_pa_color=' + card_item.attribute_pa_color;

                        btn_container.find('.product_type_variable').remove();
                        btn_container.append('<a href="' + the_url + '" class="button product_type_variable add_to_cart_button custom-variation-type btn" title="' + btn_text + '" data-quantity="' + quantity + '" data-product_id="' + card_item.product_id + '" data-variation_id="' + card_item.variation_id + '" data-pa_city="' + card_item.attribute_pa_city + '" data-pa_color="' + card_item.attribute_pa_color + '" aria-label="' + btn_text + '" rel="nofollow">' + btn_text + '</a>');

                        card.parent().removeClass('load')
                    } else {
                        card.parent().remove()
                    }
                }
            })
        }
    },
    get_variation_data(city) {
        let variation_form = $(document).find('.block-product__characteristics .variations_form-2');
        let variation = null;
        if (variation_form.length > 0) {
            let data = variation_form.attr('data-product_variations');
            data = JSON.parse(data);
            for (let i = 0; i < data.length; i++) {
                let elem = data[i];
                if (typeof elem.attributes.attribute_pa_city != 'undefined' && elem.attributes.attribute_pa_city == city) {
                    variation = elem;
                }
            }
        }

        return variation;
    }
};

const slick_carrousel = {
    init() {
        let slider = $(document).find('.slick-content-slide');

        if (slider.length > 0) {
            slider.slick({
                dots: false,
                arrows: true,
                prevArrow: $('.arrow-salon.arrow-left'),
                nextArrow: $('.arrow-salon.arrow-right'),
                infinite: false,
                slidesToShow: 10,
                slidesToScroll: 10,
                centerMode: false,
                speed: 500,
                responsive: [
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: 8,
                            slidesToScroll: 8,
                        }
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 6,
                            slidesToScroll: 6,
                        }
                    },
                    {
                        breakpoint: 900,
                        settings: {
                            slidesToShow: 5,
                            slidesToScroll: 5
                        }
                    },
                    {
                        breakpoint: 780,
                        settings: {
                            slidesToShow: 4,
                            slidesToScroll: 4
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3
                        }
                    }
                ]
            })
        }
    },
    onClickTicket() {
        $(document).find('body').on('click', '.slick-content-slide .ticket', function (e) {
            let index = $(this).closest('.slick-slide').index();
            let block_index = $(document).find('body .section-footer.uk-switcher .inner');
            block_index.removeClass('uk-active');
            block_index.eq(index).addClass('uk-active')
        })
    },
    styleRoomSlideBanner() {
        let slider = $(document).find('.style-top-bg-slide');

        if (slider.length > 0) {
            slider.slick({
                dots: false,
                arrows: false,
                infinite: true,
                draggable: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                fade: true,
                autoplay: true,
                autoplaySpeed: 3000,
                speed: 500,
            })
        }
    },
    styleRoomSlide3d() {
        let slider = $(document).find('.style-top-bg-slide-3d');

        if (slider.length > 0) {
            slider.slick({
                dots: true,
                arrows: true,
                prevArrow: $('.style-product-3d-arrow .arrow-left'),
                nextArrow: $('.style-product-3d-arrow .arrow-right'),
                infinite: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                // fade: true,
                autoplay: true,
                autoplaySpeed: 5000,
                speed: 500,
            })
        }
    }
};

/**
 * $ document ready
 */
$(document).ready(function () {
    view_manager.o_hide();
    view_manager.v_none();

    single_product.load_price_single();
    single_product.load_price_loop();
    single_product.add_to_cart();
    single_product.single_add_to_cart();

    agenda.filterForm();
    agenda.onChangeFavoris();
    agenda.onChangeSalon();
    agenda.onPageNeedSalon();
    agenda.onSelectSalonPopup();
    agenda.setCheckoutFormEventType();
    // agenda.initAgendaList();
    // agenda.onChangeSalonCity();
    agenda.printToPdf();
    // agenda.switcher_manage();
    search_page.onSelectPagination();
    search_page.onClickProductSearch();
    search_page.showPrevBtnSearchProduct();
    shop.loopQuantity();
    shop.loaderAddToCart();
    // shop.onChangeFilter()
    shop.onSelectPagination();
    shop.onSelectFilter();
    shop.loopAddToCartBtn();
    shop.onScrollDownProductList();
    shop.onScrollDownProductSearch();
    shop_single.singleProductQuantity();
    shop_single.onChangeColor();
    shop_single.imageLightbox();
    checkout_process.stape_handler('#load-2-3');
    checkout_process.stape_handler('#load-3-4');
    checkout_process.stape_handler('#is_stape_4');
    checkout_process.behaviour('#current_checkout_stape');
    checkout_process.handle_process_checkout();
    checkout_process.loader();
    checkout_process.onChangeCheckoutCompany();
    checkout_process.eventDateManager();
    cart.sendProOrder();
    cart.makeReduceCredit();
    contact.listenFormSubmit();
    my_account.beforeDeleteAccount();
    modal_manager.cancelBtn();
    modal_manager.homeMessage();
    modal_manager.warning_close();
    reed.viewDotationItem();
    reed.addProductToDotation();
    reed.removeFromDotation();
    download_manager.onClickZipCategory();
    download_manager.onClickDownloadLink();

    home_manager.video_volume();

    slick_carrousel.init();
    slick_carrousel.onClickTicket();
    slick_carrousel.styleRoomSlideBanner();
    slick_carrousel.styleRoomSlide3d();

    /**
     * Modal popup
     */
    if ($('#cmrs-reed-change-user').length) {
        UIkit.modal('#modal-reed-change-user', {'bgClose': false}).show()
    }

    /**
     * PDF generate
     */
    if ($('#pdf-inner').length) {
        let filename = $(this).find('.product-title').text();
        filename = filename + '-Camerus-product.pdf';
        pdf.pdfPrint(2, filename)
    }
});

/**
 * Do after all content load
 */
$(window).on('load', function () {
    home_manager.iframe_defer();
    /**
     * Do ajax after all load
     */
    setTimeout(function () {
        dynamic_content.refresh_header_panel();
        agenda.loadModalEventWarning();
        secu_manager.refresh_csrf_token();

        if ($('.home-btn-event').length) {
            let dataForm = [
                {name: 'action', value: 'product_get_home_event_btn'},
                {name: 'lang', value: $('html')[0].lang},
            ];

            function done(response, textStatus, jqXHR) {
                if (response.success === true) {
                    let btn = $(document).find('.home-btn-event');
                    let home_btn = response.data.home_btn;

                    if (home_btn !== false) {
                        btn.empty();
                        btn.append(home_btn)
                    } else {
                        btn.empty()
                    }
                } else {
                    console.log(response)
                }
            }

            function fail(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown)
            }

            component.do_ajax('json', dataForm, done, fail)
        }
    }, 3500);
});