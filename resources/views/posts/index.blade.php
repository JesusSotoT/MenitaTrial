@extends('layouts.app')

@section('content')
<section class="pt-5 pb-5">
  <div class="container-fluid">
    <div class="row d-flex">
        <div class="col-12">
            <h3 class="mb-2 text-center">RECENT POSTS</h3>
        </div>
        @foreach ($posts as $post)
        <div class="col-sm-6 col-md-4 col-lg-3 pb-4 d-flex ">
            <div class="card  card-body border-light  justify-content-between bg-primary text-white shadow">
                <blockquote class="blockquote mb-4 pb-2">
                    <p class="card-text mb-5 mb-0 font-weight-bold text-white ">
                        <div>
                            <h1>{{ $post->title }}</h1>
                        </div>
                    </p>
                </blockquote>
                <div class="d-flex align-items-center">
                    <div class="meta-author">
                        <img class="d-block img-fluid rounded-circle shadow" src="https://via.placeholder.com/40x40/ccc/ffffff " alt="author avatar">
                    </div>
                    <div class="m-2">
                        <a class="text-white" href="#">{{ $post->name }}</a>
                    </div>
                    <div class="meta-item ml-auto">
                        <a class="text-white" href="{{ route('post-detail', ['id' => $post->id]) }}">
                            <i class="fas fa-link m-1"></i>Read more</a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
</section>
@endsection



