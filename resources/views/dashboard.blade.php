@extends('layouts.app2')

@section('title', 'Dashboard')

@section('content')

    Dashboard
    device = {{ isDesktop() }}
    {{-- device {{ $device }} --}}
@endsection
