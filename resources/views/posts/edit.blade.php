@extends('layouts.app')

@section('content')
    <style>
        hr {
            border-top: 1px solid #000 !important;
        }

        .row {
            margin-right: 10%;
            margin-left: 10%;
        }

        .comment {
            margin-right: 20px;
            margin-left: 20px;
            margin-bottom: 30px;
        }

        .head {
            margin-bottom: 10px;
        }

        .user {
            margin-right: 10px;
        }

    </style>
    <section class="pt-5 pb-5">
        <div class="container">
            @foreach ($article as $data)

            @endforeach
            <article class="row card  border-0 flex-md-row justify-content-between align-items-center card-top">
                <a class="col-md-5  order-md-2 order-1 w-md-25" href="#">
                    <img class="img-fluid"
                        src="https://image.freepik.com/vector-gratis/papel-pintado-degradado-formas-geometricas_23-2148795930.jpg"
                        srcset="https://image.freepik.com/vector-gratis/papel-pintado-degradado-formas-geometricas_23-2148795930.jpg"
                        alt="Pic 8">
                </a>
                <div class="card-body order-2 order-md-1 col-md-7">
                    <div class=" text-uppercase font-weight-bold mb-4 text-warning">Article {{ $data->id }}</div>
                    <h2 class="card-title display-4 font-weight-bold">
                        <a href="#" class="text-dark" title="Blog title">{{ $data->title }}</a>
                    </h2>
                    <div class="card-text mb-4">
                        <p class="lead">{{ $data->body }}</p>
                    </div>
                    <div class="mt-auto d-flex align-items-center pt-2">
                        <div class="mr-3">
                            <img class="d-block img-fluid"
                                src="https://image.freepik.com/foto-gratis/empresaria-confiada-sonriente-que-presenta-brazos-cruzados_1262-20950.jpg"
                                width="50" alt="user">
                        </div>
                        <div class="d-block">
                            <div class="font-weight-bold">{{ $data->name }}</div>
                            <div class="text-grey">{{ date('d-m-Y', strtotime($data->created_at)) }}</div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </section>
    <section>
        <div class="container">
            <div class="row">
                <h2>Comments</h2>
            </div>
            <hr>
            @foreach ($commentsExternal as $comment)
                <div class=" comment">
                    <div class="head">
                        <h5>{{ $comment->email }} - <strong class='user'> {{ $comment->name }}</strong>
                            <small>01/03/2021 12:13</small></h5>
                    </div>
                    <p>{{ $comment->body }}</p>
                </div>
            @endforeach
            @foreach ($commentsInternal as $comment)
            @if ($comment->comment_body  ==  $commentString)
            <div class="comment" hidden>
            </div>
            @else
            <div class="comment">
                <div class="head">
                    <h5>{{ $comment->email }} - <strong class='user'> {{ $comment->name }}</strong>
                        <small>{{ date('d-m-Y', strtotime($data->created_at)) }}</small>

                    </h5>
                </div><br>
                <div class="comment">
                    <p>{{ $comment->comment_body }}</p>
                </div>
            </div>
            @endif
            @endforeach
            <hr>
            <form method="POST" action="{{ route('store-edit-comment') }}">
                @csrf
                <input type="text" name="idcomment" value="{{ $commentID }}" hidden>
                <input type="text" name="idArticle" value="{{ $idArticle }}" hidden>
                <input type="text" name="userId" value="{{ auth()->user()->id }}" hidden>
                <textarea class="form-control" name="comment" placeholder="Comment....">
                    {{
                        $commentString
                    }}</textarea><br />
                <button class="btn btn-primary">Send</button>
            </form>
        </div>
    </section>
@endsection
