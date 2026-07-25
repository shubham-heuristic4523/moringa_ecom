{{--
 | Layout: layouts/customer.blade.php
 | Purpose: Customer dashboard shell — extends app.blade.php with sidebar for account pages.
 | Usage  : @extends('layouts.customer')
--}}
@extends('layouts.app')

@section('content')
<div class="max-w-container-max mx-auto px-gutter py-4xl">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4xl">

        {{-- Account Sidebar --}}
        <aside class="lg:col-span-1 hidden lg:block">
            @include('customer.partials.account-sidebar')
        </aside>

        {{-- Page Content --}}
        <div class="lg:col-span-3">
            @yield('customer-content')
        </div>

    </div>
</div>
@endsection
