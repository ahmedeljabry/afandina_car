@extends('layouts.website')
@section('title', $homeTranslation?->contact_us_title ?? __('website.contact.page_title'))

@section('content')
    @php
        $assetUrl = static fn (string $path): string => asset('website/assets/' . ltrim($path, '/'));

        $pageTitle = $homeTranslation?->contact_us_title ?: __('website.contact.page_title');
        $detailsParagraph = $homeTranslation?->contact_us_detail_paragraph
            ?: $contact?->additional_info
            ?: __('website.contact.fallback.opening_hours');

        $phoneNumber = $contact?->phone ?: $contact?->alternative_phone;
        $phoneHref = filled($phoneNumber)
            ? 'tel:' . preg_replace('/[^\d+]/', '', $phoneNumber)
            : 'javascript:void(0);';
        $hasPhoneHref = $phoneHref !== 'javascript:void(0);';

        $email = $contact?->email;
        $emailHref = filled($email) ? 'mailto:' . $email : 'javascript:void(0);';
        $hasEmailHref = $emailHref !== 'javascript:void(0);';
        $locationHref = filled($contact?->google_map_url) ? $contact->google_map_url : 'javascript:void(0);';
        $hasLocationHref = $locationHref !== 'javascript:void(0);';

        $fullAddress = collect([
            $contact?->address_line1,
            $contact?->address_line2,
            $contact?->city,
            $contact?->state,
            $contact?->postal_code,
            $contact?->country,
        ])->filter()->implode(', ');
    @endphp

    <style>
        .contact-section .contact-info-area .single-contact-info .contact-info-text {
            display: inline-block;
            font-size: 16px;
            color: #737373;
            transition: all 0.5s;
        }

        .contact-section .contact-info-area .single-contact-info:hover .contact-info-text {
            color: #ffffff;
        }

        @media (max-width: 991.98px) {
            .contact-section .contact-info-area .single-contact-info .contact-info-text {
                font-size: 13px;
            }
        }
    </style>

    <!-- Breadscrumb Section -->
    <div class="breadcrumb-bar">
        <div class="container">
            <div class="row align-items-center text-center">
                <div class="col-md-12 col-12">
                    <h2 class="breadcrumb-title">{{ $pageTitle }}</h2>
                    <nav aria-label="breadcrumb" class="page-breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('website.nav.home') }}</a></li>
                            <li class="breadcrumb-item"><span>{{ __('website.contact.breadcrumb.pages') }}</span></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $pageTitle }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- /Breadscrumb Section -->

    <!-- Contact us -->
    <section class="contact-section">
        <div class="container">
            <div class="contact-info-area">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down" data-aos-duration="1200" data-aos-delay="0.1">
                        <div class="single-contact-info flex-fill">
                            <span><i class="feather-phone-call"></i></span>
                            <h3>{{ __('website.contact.cards.phone_number') }}</h3>
                            @if ($hasPhoneHref)
                                <a href="{{ $phoneHref }}" class="contact-info-text">{{ $phoneNumber }}</a>
                            @else
                                <span class="contact-info-text">{{ __('website.contact.fallback.not_available') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down" data-aos-duration="1200" data-aos-delay="0.2">
                        <div class="single-contact-info flex-fill">
                            <span><i class="feather-mail"></i></span>
                            <h3>{{ __('website.contact.cards.email_address') }}</h3>
                            @if ($hasEmailHref)
                                <a href="{{ $emailHref }}" class="contact-info-text">{{ $email }}</a>
                            @else
                                <span class="contact-info-text">{{ __('website.contact.fallback.not_available') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down" data-aos-duration="1200" data-aos-delay="0.3">
                        <div class="single-contact-info flex-fill">
                            <span><i class="feather-map-pin"></i></span>
                            <h3>{{ __('website.contact.cards.location') }}</h3>
                            @if ($hasLocationHref)
                                <a href="{{ $locationHref }}" class="contact-info-text" target="_blank" rel="noopener noreferrer">{{ $fullAddress ?: __('website.contact.fallback.not_available') }}</a>
                            @else
                                <span class="contact-info-text">{{ $fullAddress ?: __('website.contact.fallback.not_available') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-12 d-flex" data-aos="fade-down" data-aos-duration="1200" data-aos-delay="0.4">
                        <div class="single-contact-info flex-fill">
                            <span><i class="feather-clock"></i></span>
                            <h3>{{ __('website.contact.cards.opening_hours') }}</h3>
                            <span class="contact-info-text">{{ $detailsParagraph }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-info-area" data-aos="fade-down" data-aos-duration="1200" data-aos-delay="0.5">
                <div class="row">
                    <div class="col-lg-6 d-flex">
                        <img src="{{ $assetUrl('img/contact-info.jpg') }}" class="img-fluid" alt="Contact">
                    </div>
                    <div class="col-lg-6">
                        <form action="{{ route('website.contact.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <h1>{{ $pageTitle }}</h1>

                                @if (session('success'))
                                    <div class="col-md-12">
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    </div>
                                @endif

                                <div class="col-md-12">
                                    <div class="input-block">
                                        <label>{{ __('website.contact.form.name') }} <span class="text-danger">*</span></label>
                                        <input type="text"
                                            name="full_name"
                                            value="{{ old('full_name') }}"
                                            class="form-control @error('full_name') is-invalid @enderror"
                                            placeholder="">
                                        @error('full_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-block">
                                        <label>{{ __('website.contact.form.email_address') }} <span class="text-danger">*</span></label>
                                        <input type="text"
                                            name="email"
                                            value="{{ old('email') }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-block">
                                        <label>{{ __('website.contact.form.phone_number') }} <span class="text-danger">*</span></label>
                                        <input type="text"
                                            name="phone"
                                            value="{{ old('phone') }}"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-block">
                                        <label>{{ __('website.contact.form.comments') }} <span class="text-danger">*</span></label>
                                        <textarea name="message"
                                            class="form-control @error('message') is-invalid @enderror"
                                            rows="4"
                                            cols="50"
                                            placeholder="">{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button class="btn contact-btn" type="submit">{{ __('website.contact.form.send_enquiry') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /Contact us -->
@endsection
