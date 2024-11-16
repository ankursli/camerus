@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')
    @loop
    <main id="main">
        <div data-uk-spinner="ratio: 2"></div>
        {!! Loop::content() !!}
    </main>
    @endloop
@endsection
