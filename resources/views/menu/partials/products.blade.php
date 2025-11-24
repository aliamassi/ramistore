@foreach($products as $item)
    <div class="product-card" onclick="window.location.href='{{ route('menu.show', [$name, $item->id]) }}'">
        <div class="product-info">
            <h3 class="product-name">{{ $item->name }}</h3>
            <p class="product-desc">{{ Str::limit($item->description, 60) }}</p>
            <div class="product-meta">
                <span class="price">{{ $setting['currency']->value ?? "$" }} {{ number_format($item->price, 2) }}</span>
                @if($item->customizable)
                    <span style="font-size: 12px; color: var(--primary); font-weight: 600; margin-left: 8px;">
                        <i class="fas fa-sliders-h"></i> Custom
                    </span>
                @endif
            </div>
        </div>
        <div class="product-img-wrapper">
            @if($item->image)
                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="product-img">
            @else
                <div style="width:100%; height:100%; background:#f0f0f0; display:flex; align-items:center; justify-content:center; color:#ccc;">
                    <i class="fas fa-utensils" style="font-size: 24px;"></i>
                </div>
            @endif
            <div class="add-btn">
                <i class="fas fa-plus"></i>
            </div>
        </div>
    </div>
@endforeach
