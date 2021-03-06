@extends('layouts.global')

@section('activity','Buka Kotak Suara')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/bukasuara.css') }}">
@endpush

@section('content')
    @if(\App\Pengaturan::isVotingSedangBerlangsung()||\App\Pengaturan::isVotingAkanBerlangsung())

        <div class="alert alert-warning">
            <center>
                <b>Pemira Sedang berlansung</b>
            </center>
        </div>
    @elseif(\App\Pengaturan::isVotingTelahBerlangsung())
        @if(!\App\Pengaturan::checkJikaSemuaPasswordBukaHasilTelahDiisiKetuaKpu())
            <div class="row">
                @foreach($users as $user)
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="header-block">
                                    <h3 class="title">{{ $user->nama }}</h3>
                                </div>
                                <div class="header-block pull-right">
                                    <h3 class="title">{{ strtoupper($user->role) }}</h3>
                                </div>
                            </div>

                            <div class="card-block">
                                @if($user->helper == null || $user->helper == '')
                                    <input name="id" value="{{ $user->id }}" type="hidden" disabled>
                                    <div class="form-group">
                                        <label class="control-label">Password</label>
                                        <input type="password" placeholder="{{$user->name}} Belum Mengeset Password"
                                               name="password" class="form-control" disabled>
                                    </div>
                                    <input type="submit" value="Kirim" class="btn btn-info" disabled>
                                @else
                                    @if($user->helper == 'secret')
                                        <div class="form-group">
                                            <label class="control-label">Password</label>
                                            <div class="alert alert-info">
                                                Password dari {{ strtoupper($user->role) }} telah dikonfirmasi
                                            </div>
                                        </div>
                                    @else
                                        <form action="{{ route('kakpu.simpan') }}" method="post">
                                            {{ csrf_field() }}
                                            <input name="id" value="{{ $user->id }}" type="hidden">
                                            <div class="form-group">
                                                <label class="control-label">Password</label>
                                                <input type="password" name="password" class="form-control">
                                            </div>
                                            <input type="submit" value="Kirim" class="btn btn-info">
                                        </form>
                                    @endif
                                @endif
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="alert alert-info">
                            Data Pemilihan bersifat rahasia
                        </div>
                        <div class="card-block">
                            @if(\App\Pengaturan::isVotingSedangBerlangsung())
                                <div class="alert alert-info">
                                    Pemira Sedang berlansung
                                </div>
                            @elseif(\App\Pengaturan::isVotingTelahBerlangsung())
                                @if(\App\Pengaturan::checkJikaSemuaPasswordBukaHasilTelahDiisiKetuaKpu())
                                    <div class="header-block">
                                        <div class="btn-group">
                                            <a href="{{ route('kakpu.hasil', ['hasil' => 'bem']) }}" target="_blank">
                                                <button class="btn btn-primary">
                                                    BEM
                                                </button>
                                            </a>

                                            <div class="dropdown">
                                                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    DPM
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    @foreach(App\Jurusan::all() as $jurusan)
                                                    <a class="dropdown-item"
                                                       href="{{ route('kakpu.hasil', ['hasil' => 'dpm','jur'=>$jurusan->id]) }}" target="_blank">{{$jurusan->nama}}</a>
                                                    @endforeach
                                                </div>
                                            </div>

                                            <div class="dropdown">
                                                <a class="btn btn-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    HMJ
                                                </a>

                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                    @foreach(App\Jurusan::all() as $jurusan)
                                                    <a class="dropdown-item"
                                                       href="{{ route('kakpu.hasil', ['hasil' => 'hmj','jur'=>$jurusan->id]) }}" target="_blank">{{$jurusan->nama}}</a>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <a href="{{ route('kakpu.mahasiswa') }}" target="_blank">
                                                <button class="btn btn-danger">
                                                    <span class="fa fa-print"></span>
                                                    Print Data Mahasiswa
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{route('kakpu.buka')}}">
                                        <button class="btn btn-primary">Buka Kotak Suara</button>
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

@endsection

@push('js')
    @if(session()->has('message'))
        <script>
            swal({
                icon: "success",
                title: "{{ session()->get('message') }}"
            });

        </script>

    @endif
    @if (session()->has('error'))
        <script>
            swal({
                icon: "error",
                title: "{{ session()->get('error') }}"
            });

        </script>
    @endif
@endpush