<?php
$active = get_field('app_home_popup_active', 'option');
$message = get_field('app_home_popup', 'option');
?>
@if(!empty($active) && !empty($message))
    <div id="modal-custom-message" class="no-close modal-custom-message" data-uk-modal>
        <div class="uk-modal-dialog uk-modal-body">
            <a href="#" class="uk-modal-close custom-close" rel="nofollow"><i class="icon icon-modal-close"></i></a>
            <div class="block block-event__modal">
                <form class="block-content" action="#">
                    <div class="block-body custom-message-body">
                        <div class="summary">
                            {!! $message !!}
                        </div>
                    </div><!-- .block-body -->
                </form><!-- .block-content -->
            </div><!-- .block-event__modal -->
        </div>
    </div><!-- #modal-event -->
@endif