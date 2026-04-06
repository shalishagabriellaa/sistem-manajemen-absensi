@extends('templates.app')
@section('container')
    <div id="app-wrap" class="style1">
        <div class="tf-container">
            <div class="tf-tab">
                <div class="content-tab pt-tab-space mb-5">
                    <div class="tab-gift-item">
                        @foreach ($informasi as $inf)
                            <a href="{{ url('/informasi-user/show/'.$inf->id) }}" style="text-decoration: none; color: inherit; display: block;">
                                <div class="food-box">
                                    <div class="img-box">
                                        <img src="{{ url('/storage/'.$inf->berita_file_path) }}" class="img-fluid w-100" style="height: 200px; object-fit: cover;">
                                    </div>
                                    <div class="content">
                                        <h4>{{ $inf->judul }}</h4>
                                        <div class="rating mt-2">
                                            {{ Str::limit(strip_tags($inf->isi), 80, '...') }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br><br><br>
@endsection
