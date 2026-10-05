{{-- Profil untuk pelanggan / publik — memakai navbar & footer layouts.public --}}
@extends('layouts.public')

@section('title', 'Profile Saya')

@section('content')
    @include('profile.partials.card')
@endsection
