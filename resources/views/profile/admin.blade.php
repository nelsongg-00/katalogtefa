{{-- Profil untuk admin_jurusan — memakai navbar & sidebar layouts.admin --}}
@extends('layouts.admin')

@section('title', 'Profile Saya')

@section('content')
    @include('profile.partials.card')
@endsection
