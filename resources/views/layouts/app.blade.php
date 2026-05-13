@php
    $currentUser = auth()->user();
    $useKemaLayout = $currentUser?->isAdmin() || $currentUser?->isSuperAdmin();
@endphp

@if($useKemaLayout)
    @include('partials.kema_layout')
@else
    @include('partials.ormawa_layout')
@endif
