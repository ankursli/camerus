@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')
    @loop
        @template('parts.content', get_post_type())
    @endloop
@endsection