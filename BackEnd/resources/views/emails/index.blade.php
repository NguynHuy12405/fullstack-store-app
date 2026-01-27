@extends('user.layouts.app')
@section('title','Trang help')
@section('content')
<section class="flex justify-center bg-gray-100 py-20">
    <div class="w-full max-w-md bg-white p-10">

        {{-- Thông báo thành công --}}
        @if(session('success'))
            <div class="mb-6 rounded-md bg-black px-4 py-3 text-center text-sm text-white animate-fade">
                {{ session('success') }}
            </div>
        @endif

        <h2 class="mb-2 text-center text-lg font-medium uppercase tracking-[0.3em]">
            Support
        </h2>

        <p class="mb-8 text-center text-sm text-gray-500">
            Please leave your information, we will assist you shortly.
        </p>

        <form action="{{ route('help.store') }}" method="POST" class="space-y-6">
            @csrf

            <input
                type="text"
                name="name"
                placeholder="Full name"
                required
                class="w-full border-b border-gray-300 bg-transparent py-2 text-sm focus:border-black focus:outline-none"
            >

            <input
                type="email"
                name="email"
                placeholder="Email address"
                required
                class="w-full border-b border-gray-300 bg-transparent py-2 text-sm focus:border-black focus:outline-none"
            >

            <input
                type="text"
                name="phone"
                placeholder="Phone number"
                class="w-full border-b border-gray-300 bg-transparent py-2 text-sm focus:border-black focus:outline-none"
            >

            <textarea
                name="message"
                rows="4"
                placeholder="How can we help you?"
                required
                class="w-full resize-none border-b border-gray-300 bg-transparent py-2 text-sm focus:border-black focus:outline-none"
            ></textarea>

            <button
                type="submit"
                class="w-full border border-black py-3 text-xs uppercase tracking-[0.3em] transition hover:bg-black hover:text-white"
            >
                Send Request
            </button>
        </form>
    </div>
</section>
@endsection