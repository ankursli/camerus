@if(isset($zip_data) && !empty($zip_data && is_array($zip_data)))
    @foreach($zip_data as $key => $zip)
        <a href="{{ $zip['zip_url'] }}" class="zip-item down-stat-link" title="{{ $zip['name'] }}" download>
            <img src="{{ $zip['img_url'] }}" class="zip-img" width="50px" height="50px" alt="{{ $zip['name'] }}">
            <div class="zip-title">{{ $zip['name'] }}</div>
        </a>
    @endforeach
@endif