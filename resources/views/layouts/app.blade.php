@php($layoutRole = session('dummy_user.role', 'ormawa'))

@if($layoutRole === 'kemahasiswaan')
    @include('partials.kema_layout')
@else
    @include('partials.ormawa_layout')
@endif
