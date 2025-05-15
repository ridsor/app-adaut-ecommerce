@extends('layouts.app')

@section('content')
    <div class="container py-5 my-5">
        <p>Mohon verifikasi email kamu melalu email yang kami kirim padamu.</p>
        <p>Tidak dapat email?</p>
        <form method="post" action="{{ route('verification.send') }}">
            @csrf
            <button class="btn btn-dark">
                <span class="text-light">Kirim Lagi</span>
            </button>
        </form>
    </div>
@endsection
