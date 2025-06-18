@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    # Congratulations, {{ $data['supplier_name'] }}!

    Your supplier account has been **approved**!

    **Details:**
    - Previous Status: {{ $data['old_status'] }}
    - New Status: {{ $data['new_status'] }}
    - Approval Date: {{ $data['change_date'] }}

    You can now access all supplier features in our platform.

    @component('mail::button', ['url' => url('/supplier/dashboard')])
        Go to Supplier Dashboard
    @endcomponent

    Need help getting started? [Contact our support team](mailto:support@example.com).

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
