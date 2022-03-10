@extends('layouts.app')
@section('title', e($article->tagTitle))
@section('description', e($article->tagMetaDescription))

@section('content')

    <section class="inner-banner"
             {{--style="background: url({{ url($article->getFirstMediaUrl('default','large'))}}) rgba(12,71,91,0.4) center top; background-size: cover;"--}}
             style="background: #007168;padding-top: 69px">
        <div class="thm-container text-center">
            <h2 style="font-size: 45px">{{ $article->titre }}</h2>

        </div><!-- /.thm-container -->
    </section><!-- /.inner-banner -->

    <section class="blog-page blog-with-sidebar single-blog-post-page sec-pad">
        <div class="thm-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="has-right-sidebar">
                        <div class="blog-post-style-one">
                            @include('partials.media')
                            <div class="single-blog-content">
                                {!! $article->texte !!}
                            </div><!-- /.single-blog-content -->
                        </div><!-- /.blog-post-style-one -->

                    </div><!-- /.has-right-sidebar -->
                </div><!-- /.col-md-9 -->

            </div><!-- /.row -->
        </div><!-- /.thm-container -->
    </section><!-- /.blog-page -->

@stop
