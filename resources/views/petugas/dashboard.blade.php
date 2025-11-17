@extends('petugas.layouts.app')

@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard')

@section('content')
<div class="card">
    <div class="card-body">
        Selamat datang, {{ session('name') }}! Ini dashboard petugas.
    </div>
</div>
@endsection
