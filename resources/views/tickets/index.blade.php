@extends(Auth::User()?->type === 'A'?'layouts.admin':'layouts.main')

@section('header-title', Auth::User()?->type === 'A'?'List of Tickets':'My Tickets')

@section('main')
    <div class="flex justify-center">
        <div class="my-4 p-6 grow bg-white dark:bg-gray-900 overflow-hidden
                    shadow-sm sm:rounded-lg text-gray-900 dark:text-gray-50">
            <x-tickets.filter-card
                :filterAction="route('seats.index')"
                :resetUrl="route('seats.index')"
                class="mb-6"
                />
            <div class="font-base text-sm text-gray-700 dark:text-gray-300">
                @if (Auth::User()?->type === 'A')
                    <x-tickets.table :tickets="$tickets"
                        :showView="true"
                        :showCustomers="true"
                        />
                @else
                    <x-tickets.table :tickets="$tickets"
                        :showView="true"
                        :showStatus="true"
                        />
                @endif
            </div>
            <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
@endsection
