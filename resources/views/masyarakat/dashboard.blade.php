@extends('layouts.app')

@section('title', 'Dashboard Masyarakat')
@section('page-title', 'Dashboard')

@section('content')
<div class="card">
    <div class="card-body">
        Selamat datang, {{ session('name') }}! Ini dashboard masyarakat.
    </div>
</div>
@endsection
