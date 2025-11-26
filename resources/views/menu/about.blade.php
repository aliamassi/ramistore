@extends('layouts.menu')

@section('title', __('About Us') . ' - ' . ($restaurant['business_name']->value ?? 'Restaurant'))

@push('styles')
<style>
    .about-container {
        max-width: 900px;
        margin: 3rem auto;
        padding: 0 1rem;
    }

    .page-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .page-title {
        font-size: 2.5rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .page-subtitle {
        color: #64748B;
        font-size: 1.1rem;
    }

    .about-card {
        background: white;
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
        margin-bottom: 2rem;
    }

    .about-content {
        color: #475569;
        font-size: 1.1rem;
        line-height: 1.8;
        text-align: justify;
    }

    .about-content p {
        margin-bottom: 1.5rem;
    }

    .about-content p:last-child {
        margin-bottom: 0;
    }

    .highlight-box {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
        border-left: 4px solid var(--primary);
        padding: 1.5rem;
        border-radius: 12px;
        margin: 2rem 0;
    }

    [dir="rtl"] .highlight-box {
        border-left: none;
        border-right: 4px solid var(--primary);
    }

    .highlight-box p {
        margin: 0;
        font-style: italic;
        color: #1E293B;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin: 2rem 0;
    }

    .feature-item {
        background: #F8FAFC;
        padding: 1.5rem;
        border-radius: 16px;
        text-align: center;
        transition: all 0.3s;
    }

    .feature-item:hover {
        background: #EEF2FF;
        transform: translateY(-4px);
    }

    .feature-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        margin: 0 auto 1rem;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .feature-title {
        font-weight: 700;
        color: #1E293B;
        margin-bottom: 0.5rem;
    }

    .feature-desc {
        color: #64748B;
        font-size: 0.95rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 12px 24px;
        background: white;
        color: var(--primary);
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
    }

    .back-btn:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
    }

    body.dark-mode .about-card {
        background: #334155;
    }

    body.dark-mode .about-content {
        color: #94A3B8;
    }

    body.dark-mode .highlight-box {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.2), rgba(139, 92, 246, 0.2));
    }

    body.dark-mode .highlight-box p {
        color: #E2E8F0;
    }

    body.dark-mode .feature-item {
        background: #475569;
    }

    body.dark-mode .feature-item:hover {
        background: #64748B;
    }

    body.dark-mode .feature-title {
        color: #F1F5F9;
    }

    body.dark-mode .feature-desc {
        color: #94A3B8;
    }

    body.dark-mode .page-subtitle {
        color: #94A3B8;
    }

    body.dark-mode .back-btn {
        background: #475569;
        color: #38BDF8;
    }

    body.dark-mode .back-btn:hover {
        background: #38BDF8;
        color: #1E293B;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 2rem;
        }

        .about-card {
            padding: 2rem 1.5rem;
        }

        .about-content {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="about-container">
    <div class="page-header">
        <h1 class="page-title">{{ __('About Us') }}</h1>
        <p class="page-subtitle">{{ __('Learn more about our story') }}</p>
    </div>

    <div class="about-card">
        <div class="about-content">
            @if(isset($restaurant['about_us']) && !empty($restaurant['about_us']->value))
                {!! $restaurant['about_us']->value !!}
            @else
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                </p>

                <p>
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>

                <div class="highlight-box">
                    <p>
                        "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo."
                    </p>
                </div>

                <p>
                    Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit.
                </p>

                <p>
                    Sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur.
                </p>
            @endif
        </div>

    </div>

    <div class="text-center">
        <a href="{{ route('menu.index', $name) }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            {{ __('Back to Menu') }}
        </a>
    </div>
</div>
@endsection
