@extends('layouts.app')

@push('head')
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@endpush

@section('body')
<main style="min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:2rem;">
    <p style="font-family:var(--font-mono);color:var(--accent-purple);font-size:0.85rem;letter-spacing:1px;margin-bottom:1rem;">✓ ENVIADO</p>
    <h1 style="font-family:var(--font-mono);font-size:2rem;margin-bottom:1rem;">Diagnóstico enviado!</h1>
    <p style="color:var(--text-secondary);max-width:480px;line-height:1.6;margin-bottom:2rem;">Entraremos em contato pelo WhatsApp em até 24h para confirmar o melhor horário para a conversa.</p>
    <a href="{{ route('landing') }}" style="font-family:var(--font-mono);color:var(--accent-purple);text-decoration:none;font-size:0.9rem;">← Voltar para o início</a>
</main>
@endsection

