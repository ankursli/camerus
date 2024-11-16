@if(!empty($languages) && is_array($languages))
    <div class="block block-topbar__lang uk">
        <div class="block-content uk-navbar-container uk-navbar-transparent" data-uk-navbar>
            <ul class="block-body uk-navbar-nav p-0-mob">
                @foreach($languages as $lang)
                    <li class="{{ $lang['active'] ? 'uk-active' : '' }}">
                        @if(!empty($post_type) && $post_type == 'post')
                            @if($lang['language_code'] == 'fr')
                                <a href="{{ str_replace('/en/', '/', apply_filters( 'wpml_permalink', $lang['url'] , $lang['language_code'] )).$url_parameter }}"
                                   title="{{ get_the_title(getPostTranslatedID(get_the_ID(), $lang['language_code'])) }}">{{ $lang['language_code'] }}</a>
                            @else
                                <a href="{{ str_replace($_SERVER['SERVER_NAME'].'blog/', $_SERVER['SERVER_NAME'].'blog/en/', apply_filters('wpml_permalink', $lang['url'] , $lang['language_code'])).$url_parameter }}"
                                   title="{{ get_the_title(getPostTranslatedID(get_the_ID(), $lang['language_code'])) }}">{{ $lang['language_code'] }}</a>
                            @endif
                        @else
                            <a href="{{ $lang['url'].$url_parameter }}"
                               title="{{ $lang['translated_name'] }}">{{ $lang['language_code'] }}</a>
                        @endif
                    </li>
                @endforeach
            </ul><!-- .block-body -->
        </div><!-- .block-content -->
    </div><!-- .block-topbar__lang -->
@endif