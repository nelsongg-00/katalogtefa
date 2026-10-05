{{-- Profil untuk worker — memakai navbar & sidebar layouts.worker --}}
@extends('layouts.worker')

@section('title', 'Profile Saya')

@section('content')
    @include('profile.partials.card')
@endsection
