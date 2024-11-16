<div class="wrap">
    <h2><?php echo sprintf("%s %s", __("Gestionnaire d'import de produit", THEME_TD), SITE_MAIN_SYS_NAME) ?></h2>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 mb-2"><?php _e("Choisir le type d'importation", THEME_TD) ?></div>
            <div class="col-sm-12">
                <form id="camerus_form_import" name="camerus_form_import" method="post" enctype="multipart/form-data"
                      action>
                    <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <div class="input-group">
                            <select name="camerus_form_import_type" id="camerus_form_import_type" class="form-control">
                                <option value="product_mobilier" selected>Mobilier</option>
{{--                                <option value="product_dotation">Dotation</option>--}}
{{--                                <option value="product_dotation_mobilier">Dotation => Mobilier</option>--}}
{{--                                <option value="attachment_delete_duplicate">Média => Suppression duplication</option>--}}
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 mb-2"><?php _e('Choisir le fichier des produits à importer.', THEME_TD) ?>
                        (<b>.csv</b>)
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" accept=".csv,text/csv" class="custom-file-input"
                                       id="camerus_form_file" name="camerus_form_file"
                                       aria-describedby="camerus_form_file">
                                <label class="custom-file-label"
                                       for="camerus_form_file"><?php _e('Choisir un fichier', THEME_TD) ?></label>
                            </div>
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="button" id="camerus_form_import_btn"
                                        disabled>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                          aria-hidden="true"></span>
                                    <?php _e('Charger le fichier', THEME_TD) ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-sm-12 mt-5 center-block">
                <input type="hidden" id="import_file_path">
                <button type="button" id="import_btn" class="btn btn-primary btn-lg" disabled>
                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <?php _e('Importer', THEME_TD) ?>
                </button>
            </div>
            <div class="col-sm-12 mt-5">
                <div class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar"
                         aria-valuenow="0" aria-valuemin="0"
                         aria-valuemax="100" style="width: 0%"></div>
                </div>
            </div>
            <div class="col-sm-12 mt-5 import-alert">
                <div class="alert alert-success d-none" role="alert">
                    A simple success alert—check it out!
                </div>
                <div class="alert alert-danger d-none" role="alert">
                    A simple danger alert—check it out!
                </div>
                <div class="alert alert-warning d-none" role="alert">
                    A simple warning alert—check it out!
                </div>
            </div>
        </div>
    </div>
</div>