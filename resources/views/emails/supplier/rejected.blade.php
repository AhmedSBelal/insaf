@component('mail::layout')
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    # Application Status Update

    Dear {{ $data['supplier_name'] }},

    We regret to inform you that your supplier application has been **rejected**.

    **Application Details:**
    - Previous Status: {{ $data['old_status'] }}
    - Current Status: {{ $data['new_status'] }}
    - Decision Date: {{ $data['change_date'] }}

    @isset($data['reason'])
        **Reason for Rejection:**
        {{ $data['reason'] }}
    @endisset

    You may:
    - Review our [supplier requirements]({{ url('/supplier/requirements') }})
    - Correct the issues and reapply
    - [Contact us](mailto:support@example.com) for clarification

    @slot('footer')
        @component('mail::footer')
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
