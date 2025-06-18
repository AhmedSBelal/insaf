@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    # Application Under Review

    Hello {{ $data['supplier_name'] }},

    Your supplier application is currently **under review**.

    **Status Change:**
    - From: {{ $data['old_status'] }}
    - To: {{ $data['new_status'] }}
    - Update Date: {{ $data['change_date'] }}

    **What to expect next:**
    - Our team will review your application
    - Processing time: 3-5 business days
    - You'll receive another notification when complete

    @component('mail::button', ['url' => url('/supplier/status')])
        Check Application Status
    @endcomponent

    Thank you for your patience.

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
