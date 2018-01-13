<li @if (Route::currentRouteName() == 'panitia.dashboard') class="active" @endif>
    <a href="{{route('panitia.dashboard')}}">
        <i class="fa fa-home"></i> Dashboard
    </a>
</li>
<li @if (Route::currentRouteName() == 'panitia.paslon') class="active" @endif>
    <a href="{{route('panitia.paslon')}}">
        <i class="fa fa-th-list"></i> Data Paslon
    </a>
</li>
@if (\Illuminate\Support\Facades\Auth::user()->helper=='kps')
    <li @if (Route::currentRouteName() == 'panitia.resepsionis') class="active" @endif>
        <a href="{{route('panitia.resepsionis')}}">
            <i class="fa fa-th-list"></i> Resepsionis
        </a>
    </li>
@endif