@foreach($products as $item)
    <div class="col-12 col-md-6 col-lg-4">
        <div class="product-card" onclick="window.location.href='{{ route('menu.show', [$name, $item->id]) }}{{ request()->has('lang') ? '?lang=' . request('lang') : '' }}'">
            <div class="d-flex gap-3">
                <div class="flex-grow-1">
                    <h3 class="fs-6 fw-bold mb-2">{{ $item->name }}</h3>
                    <p class="text-muted small mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ Str::limit($item->description, 60) }}
                    </p>
                    <div class="d-flex align-items-center justify-content-between">
                        <span class="fs-6 fw-bold text-purple">{{ $setting['currency']->value ?? "$" }} {{ number_format($item->price, 2) }}</span>
                        @if($item->customizable)
                            <span class="small text-primary fw-semibold">
                                <i class="fas fa-sliders-h"></i> Custom
                            </span>
                        @endif
                    </div>
                </div>
                <div class="product-img-wrapper flex-shrink-0">
                    @if($item->image)
                        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="product-img">
                    @else
                        <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                            <i class="fas fa-utensils fs-4"></i>
                        </div>
                    @endif
                    <div class="add-btn">
                        <i class="fas fa-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
