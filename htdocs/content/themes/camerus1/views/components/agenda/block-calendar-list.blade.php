@if(!empty($salons) && is_array($salons))
    @foreach($salons as $salon)
        @include('components.agenda.block-calendar')
    @endforeach
@else
    <div class="inner uk-active">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="block block-calendar__event uk">
                    <div class="block-content">
                        <div class="block-body uk-grid uk-grid-large">
                            <div class="uk-width-1-3@m col-left uk-text-right@m uk-text-center">
                                <div class="alert alert-warning">Pas de salon pour votre selection</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif