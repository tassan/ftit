@extends('layouts.app')

@push('head')
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/diagnostico.css') }}">
@endpush

@section('body')
<div class="container">
    <div class="header">
        <a href="{{ route('landing') }}" class="logo">
            <span class="back-arrow">←</span>
            <span class="ft">f(t)</span><span class="it"> it</span>
        </a>
        <h1>Diagnóstico <span class="highlight">Digital</span></h1>
        <p>6 blocos rápidos. Depois, um <strong>parecer personalizado com IA</strong> sobre o seu negócio.</p>
    </div>

    <div class="progress-container">
        <div class="progress-bar">
            <div class="progress-fill" id="progressFill"></div>
        </div>
        <div class="progress-label" id="progressLabel">Etapa 1 de 6</div>
    </div>

    @include('diagnostico._steps')
</div>

<script>
    window.FTIT = {
        whatsapp: '{{ addslashes($whatsapp) }}'
    };
</script>
<script src="{{ asset('assets/js/diagnostico.js') }}"></script>
@endsection

