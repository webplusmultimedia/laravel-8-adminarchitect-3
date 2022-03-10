<div class="col-md-4 col-sm-6 col-xs-12" style="margin-top: 20px">
    <div class="single-service">
        <div style="background-color: white;height: 100%">
            @if($service->hasMedia())
                <div class="img-box">
                    <img src="{{ Croppa::url(url($service->getFirstMediaUrl()),400,235) }}" alt="{{ $service->getFirstMedia()->getCustomProperty('titre') }}"/>
                </div><!-- /.icon-box -->
            @endif
            <div class="single-service-style-one">
                <div class="text-box">
                    <h3>{{ $service->titre }}</h3>
                    <p>{!! $service->extrait !!}</p>
                    <a href="{{ route('services.show',[$service->slug]) }}" class="read-more">En savoir plus</a>
                </div><!-- /.text-box -->
            </div><!-- /.single-service-style-one -->
        </div>

    </div>
</div><!-- /.col-md-4 -->
