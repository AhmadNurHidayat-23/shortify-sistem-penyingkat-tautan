@extends('layouts.app')
@section('title', 'Tautan tidak ditemukan')
@section('content')
    <section class="error-page"><span class="error-code">404</span><p class="section-kicker">SEPERTINYA ANDA TERSESAT</p><h1>Tautan tidak ditemukan.</h1><p>Tautan ini mungkin sudah dihapus atau alamatnya kurang tepat.</p><a class="button button-primary" href="{{ route('home') }}">Kembali ke Beranda <span aria-hidden="true">↗</span></a></section>
@endsection
