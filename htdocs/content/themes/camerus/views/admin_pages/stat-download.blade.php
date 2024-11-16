<div class="wrap">
    <h2><?php _e("Statistique des Téléchargements de fichier", THEME_TD) ?></h2>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 mb-2"><?php _e("Date", THEME_TD) ?> :</div>
            <div class="col-sm-12">
                <form id="camerus_form_import" name="camerus_form_import" method="get" enctype="multipart/form-data"
                      action="">
                    <input type="hidden" name="page" value="{{ $page }}">
                    <div class="form-group">
                        <div class="input-group">
                            <select name="stat_date" class="form-control">
                                @for($m=1; $m<=12; $m++)
                                    <?php
                                    setlocale(LC_TIME, array('fr_FR.UTF-8', 'fr_FR@euro', 'fr_FR', 'french'));
                                    $month = date('Y-m', mktime(0, 0, 0, $m, 1, date('Y')));
                                    $month_title = strftime('%B %Y', mktime(0, 0, 0, $m, 1, date('Y')));
                                    ?>
                                    @if(isset($_GET['stat_date']) && $_GET['stat_date'] == $month)
                                        <option value="{{ $month }}" selected>{!! ucfirst($month_title) !!}</option>
                                    @else
                                        @if($month == date('Y-m'))
                                            <option value="{{ $month }}" selected>{!! ucfirst($month_title) !!}</option>
                                        @else
                                            <option value="{{ $month }}">{!! ucfirst($month_title) !!}</option>
                                        @endif
                                    @endif
                                @endfor
                            </select>
                            <button type="submit" id="stat_btn" class="btn btn-primary btn-lg ml-3">
                                <span class="spinner-border spinner-border-sm d-none" role="status"
                                      aria-hidden="true"></span>
                                <?php _e('Trouver', THEME_TD) ?>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <table class="table table-bordered table-dark">
                            <thead>
                            <tr>
                                <th colspan="2" scope="col">Titre</th>
                                <th scope="col">Compteur</th>
                            </tr>
                            </thead>
                            <tbody>

                            @if(!empty($stats) && is_array($stats))
                                @foreach($stats as $stat)
                                    <tr>
                                        <td colspan="2"><a href="{{ $stat['link'] }}">{!! $stat['name'] !!}</a></td>
                                        <td>{{ $stat['count'] }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
            <div class="col-sm-12 mt-5 stat-alert">
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