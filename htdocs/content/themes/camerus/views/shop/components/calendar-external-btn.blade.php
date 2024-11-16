<a class="btn"
   target="@if(!empty($link['target'])) {{ $link['target'] }} @else _blank @endif"
   href="{{ $link['url'] ?? '#' }}">
    <span><?php echo ! empty($link['title']) ? $link['title'] : __('Choisir cet évenement', THEME_TD) ?></span>
</a>