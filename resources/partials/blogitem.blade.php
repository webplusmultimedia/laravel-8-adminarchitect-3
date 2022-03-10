@foreach($blogs as $blog)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="service-block-2">
            <div class="service-block-2-content">
                <div class="blog-image">
                    <img src="{{ url($blog->getFirstMediaUrl('default','medium')) }}" alt="img">
                    <span class="blog-date"><i class="far fa-clock"> </i> &nbsp; {{ $blog->created_at->translatedFormat('d F Y') }}</span>
                </div>

                <div class="block-content">
                    <h4><a href="{{ route('blog.show',$blog->slug) }}">{{ $blog->titre }}</a></h4>
                    <strong>{{ $blog->extrait }}</strong>
                </div>
            </div>
            <div class="block-plus">
                <a href="{{ route('blog.show',$blog->slug) }}" class="service-block-2-btn">En savoir plus <i class="fa fa-arrow-right primary-color"></i></a>
            </div>
        </div>
    </div>
@endforeach
