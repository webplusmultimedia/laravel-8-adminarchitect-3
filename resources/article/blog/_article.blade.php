<article class="blog-item-cta">
    <a href="{{ route('blog.show', $article->slug) }}" class="link">
        <h3 class="title">{{ $article->titre }}</h3>
        <div class="btn-wp">
            <div class="btn-a white">En savoir +</div>
        </div>
        <div class="date-wp">@date($article->date ? $article->date : $article->created_at)</div><!-- /.date-wp -->
    </a>
    @if ($article->illustration)
        <img src="{{ Croppa::url($article->illustration->cropPath, 288, 312) }}" class="img-bg" alt="{{ $article->illustration->tagAlt }}" />
    @else
        <img src="{{ asset('theme/images/illustrations/default.jpg') }}" class="img-bg" alt="" />
    @endif
</article>