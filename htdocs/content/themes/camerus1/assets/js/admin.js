import Papa from 'papaparse'

(function ($) {
    /**
     * Main components function
     * @type {{telephone_reg: RegExp, form_error_messages: {error: {zipcode: string, telephone: string, email: string}, required: string}, listen_submit(*=, *=, *=): void, chunkArray(*, *): *, ui_select_default_values: string[], fields_selectors: string, send_text: string, do_ajax(*=, *=, *=, *=, *=): void, zipcode_reg: RegExp, email_reg: RegExp, icon_submit_button_forms: string[], validate_form(*): *, process_form(*=, *, *=): void}}
     */
    let component = {
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

        empty(data) {
            if (typeof (data) == 'number' || typeof (data) == 'boolean') {
                return false
            }
            if (typeof (data) == 'undefined' || data === null) {
                return true
            }
            if (typeof (data.length) != 'undefined') {
                return data.length == 0
            }
            let count = 0;
            for (let i in data) {
                if (data.hasOwnProperty(i)) {
                    count++
                }
            }
            return count == 0
        },

        do_ajax(dataType, data, done, fail = null, always = null) {
            $.ajax({
                url: cmrs_admin_ajax,
                type: 'POST',
                dataType: dataType,
                data: data,
                contentType: false,
                processData: false
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
            for (key in infos) {
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
        }
    };

    let dotation = {
        totalStock() {
            let btn = $('.dotation_total_stock_btn');

            btn.on('click', function () {
                let input = $(this).attr('data-total');
                let quantity = parseInt($('#' + input).val());
                let type = $('#' + input).attr('data-type');
                let table = $('.ssm_subtable.' + type);

                if (quantity > 0) {
                    table.find(('.dotation_quantity')).val(quantity)
                }
            })
        }
    };

    let import_product = {
        do_import() {
            let btn_import = $('#import_btn');
            let alert = $('.import-alert');
            let counter = 1;

            btn_import.on('click', function () {
                $(this).attr('disabled', 'disabled');
                let file_url = $('#import_file_path');
                let import_type = $('#camerus_form_import_type');
                btn_import.find('.spinner-border').removeClass('d-none');
                $('#import_btn').text("Import en cours ...");

                Papa.parse(file_url.val(), {
                    download: true,
                    header: true,
                    // dynamicTyping: true,
                    encoding: 'UTF-8',
                    skipEmptyLines: true,
                    step: function (results, parser) {
                        let data = results.data;

                        if (data && !component.empty(data.Reference)) {
                            let formData = new FormData();
                            console.log('Row step meta:', results.meta);
                            parser.pause();

                            formData.append('action', 'import_products_data_camerus');
                            formData.append('product', JSON.stringify(data));
                            formData.append('import_type', import_type.val());

                            function done(response, textStatus, jqXHR) {
                                if (response.success) {
                                    console.log('repons: ', response.data.product);
                                    $('.import-alert').append('<div class="alert alert-info" role="alert">\n' +
                                        ++counter + ' - ' + response.data.product.Nom + ' ' + response.data.product.Reference + ' : ' +
                                        'LANG: ' + response.data.product.language + ' -- ID:' + response.data.product.parent_product_id +
                                        ' -- STATUS: ' + response.data.product.product_import + ' -- LINK: ' + response.data.product.product_link +
                                        '</div>');
                                    parser.resume()
                                } else {
                                    parser.abort();
                                    alert.find('.alert-danger').text('Erreur lors de l\'importation, veuillez réessayer plus tard');
                                    alert.find('.alert-danger').removeClass('d-none')
                                }
                            }

                            function fail(jqXHR, textStatus, errorThrown) {
                                parser.abort();
                                btn_import.removeAttr('disabled');
                                console.log(errorThrown)
                            }

                            component.do_ajax(false, formData, done, fail)
                        }
                    },
                    complete: function (results, file) {
                        console.log('Parsing complete:', results, file);
                        btn_import.removeAttr('disabled');
                        btn_import.find('.spinner-border').addClass('d-none');
                        alert.find('.alert-success').text('Fin de l\'importation');
                        alert.find('.alert-success').removeClass('d-none');
                        $('#import_btn').text("Import arrêter");
                    }
                })
            })
        },
        load_file() {
            let form = $('#camerus_form_import');
            let file_input = $('#camerus_form_file');
            let btn_load = $('#camerus_form_import_btn');

            btn_load.on('click', function () {
                let dataForm = form.serializeArray();
                let formData = new FormData();

                btn_load.attr('disabled', 'disabled');
                btn_load.find('.spinner-border').removeClass('d-none');

                $.each(form.find('input[type="file"]'), function (i, tag) {
                    $.each($(tag)[0].files, function (i, file) {
                        formData.append(tag.name, file)
                    })
                });

                $.each(dataForm, function (i, val) {
                    formData.append(val.name, val.value)
                });

                formData.append('action', 'import_products_camerus');

                function done(response, textStatus, jqXHR) {
                    if (response.success === true && response.data.file_url !== undefined) {
                        let file_path = response.data.file_url;
                        $('#import_file_path').val(file_path);
                        $('#import_btn').removeAttr('disabled');
                        $('#import_btn').text("Commencer l'import");
                    } else {
                        console.log('Error load', response)
                    }

                    btn_load.removeAttr('disabled');
                    btn_load.find('.spinner-border').addClass('d-none')
                }

                function fail(jqXHR, textStatus, errorThrown) {
                    btn_load.find('.spinner-border').addClass('d-none');
                    console.log(errorThrown)
                }

                component.do_ajax(false, formData, done, fail)
            })
        },
        file_name() {
            let file_input = $('#camerus_form_file');
            let btn_load = $('#camerus_form_import_btn');

            file_input.on('change', function () {
                let fileName = file_input[0].files[0].name;
                let extension = fileName.split('.').pop();

                if (extension === 'csv') {
                    $(this).next('.custom-file-label').html(fileName);
                    btn_load.removeAttr('class').addClass('btn btn-success');
                    btn_load.removeAttr('disabled')
                } else {
                    console.log('Format de fichier non reconnu')
                }
            })
        }
    };

    /**
     * $ document ready
     */
    $(document).ready(function () {
        dotation.totalStock();
        import_product.load_file();
        import_product.file_name();
        import_product.do_import()
    })

})(jQuery);