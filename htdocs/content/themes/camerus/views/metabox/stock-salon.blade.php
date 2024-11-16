<div class="acf-label">
    <h2><?php _e('Gestion de stock', THEME_TD) ?></h2>
    <span><?php _e('Actualiser la page pour charger les dotations des familles que vous avez selectionné après avoir enregistrer', THEME_TD) ?></span>
</div>

<table class="widefat ssm ssm_table" style="margin-bottom: 20px;">
    <input type="hidden" name="ssm_salon_ref" value="{{ $salon_ref }}">
    <thead>
    <tr>
        <th><?php _e('Choisir les types de dotation', THEME_TD) ?></th>
        <th style="text-align:center"><?php _e('Dotations', THEME_TD) ?></th>
    </tr>
    </thead>
    <tbody>

    @if(!empty($types) && is_array($types))
        @foreach($types as $type)

            <tr>
                <td>
                    <label>
                        <input name="ssm_types[]" class="ssm_type_{{ $type->slug }}"
                               @if(!empty($type->is_checked)) checked @endif
                               type="checkbox" value="{{ $type->slug }}">&nbsp;<strong class="ssm_title">{!! $type->name !!}</strong>
                    </label>

                    @if(!empty($type->is_checked) && !empty($type->dotations))
                        <div>
                            <br>
                            <label><?php _e('Quantité pour chaque dotation:', THEME_TD) ?>
                                <br>
                                <input id="ssm_total_{{ $type->slug }}" data-type="{{ $type->slug }}" min="0" type="number" value="0">
                                <br>
                                <button data-total="ssm_total_{{ $type->slug }}" class="dotation_total_stock_btn" type="button"><?php _e('Tous Modifier',
                                        THEME_TD); ?></button>
                            </label>
                        </div>
                    @endif
                </td>

                <td>
                    @if(!empty($type->is_checked))
                        <table class="widefat ssm_subtable {{ $type->slug }}">
                            <thead>
                            <tr>
                                <th>Type: {!! $type->name !!}</th>
                                <td style="text-align:center"><?php _e('Stock(s)', THEME_TD); ?></td>
                            </tr>
                            </thead>

                            <tbody>

                            @if(!empty($type->dotations) && is_array($type->dotations))
                                @foreach($type->dotations as $dotation)
                                    <tr>
                                        <td>
                                            <div><strong>{!! $dotation->post_title !!}</strong></div>
                                            <div>REF: {!! $dotation->dotation_ref !!}</div>
                                        </td>
                                        <td align="right">
                                            <label><?php _e('Quantité', THEME_TD) ?>:&nbsp;
                                                <input name="{{ $type->slug.':'.$dotation->dotation_ref.':'.$dotation->ID }}" type="number"
                                                       class="dotation_quantity"
                                                       value="{{ $dotation->dotation_quantity }}">
                                                <input name="ssm_dotations[]" type="hidden"
                                                       value="{{ $type->slug.':'.$dotation->dotation_ref.':'.$dotation->ID }}">
                                            </label>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                            </tbody>
                        </table>
                    @endif
                </td>
            </tr>

        @endforeach
    @endif
    </tbody>
</table>