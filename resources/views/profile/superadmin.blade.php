{{-- Profil untuk super_admin — memakai navbar & sidebar layouts.superadmin --}}
@extends('layouts.superadmin')

@section('title', 'Profile Saya')

@section('content')
    @include('profile.partials.card')
@endsection
