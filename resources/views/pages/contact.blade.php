@extends('layouts._layout')

@section('title', __('Contact Us') . ' - ' . ($restaurant['business_name']->value ?? 'Restaurant'))

@push('styles')
    <style>
        .contact-container {
            max-width: 800px;
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

        .contact-card {
            background: white;
            border-radius: 24px;
            padding: 2.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            margin-bottom: 1.5rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 1.5rem;
            padding: 1.5rem;
            border-radius: 16px;
            background: #F8FAFC;
            margin-bottom: 1rem;
            transition: all 0.3s;
        }

        .contact-item:hover {
            background: #EEF2FF;
            transform: translateX(8px);
        }

        [dir="rtl"] .contact-item:hover {
            transform: translateX(-8px);
        }

        .contact-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }

        .contact-content {
            flex: 1;
        }

        .contact-label {
            font-weight: 600;
            color: #1E293B;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }

        .contact-value {
            color: #64748B;
            font-size: 1rem;
            word-break: break-word;
        }

        .contact-value a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s;
        }

        .contact-value a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
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

        body.dark-mode .contact-card {
            background: #334155;
        }

        body.dark-mode .contact-item {
            background: #475569;
        }

        body.dark-mode .contact-item:hover {
            background: #64748B;
        }

        body.dark-mode .contact-label {
            color: #F1F5F9;
        }

        body.dark-mode .contact-value {
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

            .contact-card {
                padding: 1.5rem;
            }

            .contact-item {
                padding: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="contact-container">
        <div class="page-header">
            <h1 class="page-title">{{ __('Contact Us') }}</h1>
            <p class="page-subtitle">{{ __('Get in touch with us') }}</p>
        </div>

        <div class="contact-card">
            @if(isset($restaurant['phone_number']))
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-phone-alt"></i>
                    </div>
                    <div class="contact-content">
                        <div class="contact-label">{{ __('Phone Number') }}</div>
                        <div class="contact-value">
                            <a href="tel:{{ $restaurant['phone_number']->value }}">
                                {{ $restaurant['phone_number']->value }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($restaurant['contact_email']))
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="contact-content">
                        <div class="contact-label">{{ __('Email Address') }}</div>
                        <div class="contact-value">
                            <a href="mailto:{{ $restaurant['contact_email']->value }}">
                                {{ $restaurant['contact_email']->value }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($restaurant['address']))
                <div class="contact-item">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-content">
                        <div class="contact-label">{{ __('Address') }}</div>
                        <div class="contact-value">
                            {{ $restaurant['address']->value }}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="text-center">
            <a href="{{ route('menu.index', $name) }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                {{ __('Back to Menu') }}
            </a>
        </div>
    </div>
@endsection
