@extends('layouts.main')

@section('og')

    @include('common.og')

@endsection

@section('content')
    @loop
        @template('parts.content', 'home')
    @endloop
@endsection