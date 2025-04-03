@props([
    'columnHeaders',
    'header',
    'payments',
    'paymentsDue',
    'paymentsStatus',
    'recordsPerPage',
    'rows',
    'showPaymentForm',
    'sortAsc',
    'sortColLabel',
])
<div class="relative">

    <table class="px-4 shadow-lg w-full">
        <thead>
        <tr>
            @foreach($columnHeaders AS $columnHeader)
                <th class='border border-gray-200 px-1'>
                    <button
                        @if($columnHeader['sortBy']) wire:click="sortBy('{{ $columnHeader['sortBy'] }}')" @endif
                        @class([
                        'flex items-center justify-center w-full gap-2 ',
                        'text-blue-500' => ($columnHeader['sortBy'])
                        ])
                    >
                        <div>{{ $columnHeader['label'] }}</div>
                        @if($sortColLabel === $columnHeader['sortBy'])
                            @if($sortAsc)
                                <x-heroicons.arrowLongUp/>
                            @else
                                <x-heroicons.arrowLongDown/>
                            @endif
                        @endif
                    </button>
                </th>
            @endforeach

        </tr>
        </thead>
        <tbody>
        @forelse($rows AS $row)
            <tr class=" odd:bg-green-50 ">

                {{-- COUNTER --}}
                <td class="text-center">
                    {{ $loop->iteration + (($rows->currentPage() - 1) * $recordsPerPage) }}
                </td>

                {{-- SCHOOL --}}
                <td class="border border-gray-200 px-1">
                    {{ $row->schoolName }} ({{ $row->schoolId }})
                </td>

                {{-- TEACHER --}}
                <td class="border border-gray-200 px-1">
                    <div>
                        {{ $row->last_name . ($row->suffix_name ? ' ' . $row->suffix_name : '') . ', ' . $row->first_name . ' ' . $row->middle_name . ($row->prefix_name ? ' (' . $row->prefix_name . ')' : '') }}
                    </div>
                    <div class="ml-2 text-sm text-blue-500 hover:underline ">
                        <a href="mailto:{{$row->email}}?subject=2025 NJ All-State Chorus">
                            {{ $row->email }}
                        </a>
                    </div>
                    <div class="ml-2 text-sm">
                        {{ $row->phoneMobile }} (c)
                    </div>
                    <div class="ml-2 text-sm">
                        {{--                        {{ $row->phoneWork }} (w)--}}
                    </div>
                </td>

                {{-- PACKAGE RECEIVED --}}
                <td class="border border-gray-200 px-1 text-center">
                    <input type="checkbox"
                           wire:click="packageReceived({{ $row->schoolId }})"
                        @checked($row->received === 1)
                    />
                </td>

                {{-- REGISTRANT COUNT --}}
                <td class="border border-gray-200 px-1 text-center">
                    {{ number_format($row->candidateCount,0) }}
                </td>

                {{-- PAYMENT DUE --}}
                <td class="border border-gray-200 px-1 text-center">
                    @php($paymentDue = (float)$paymentsDue[$row->schoolId])
                    {{ number_format($paymentDue, 2) }}
                </td>

                {{-- PAYMENTS MADE --}}
                <td
                    @class([
        "border border-gray-200 px-1 text-center",
                'bg-red-100' => $paymentsStatus[$row->schoolId] === 'due',
                'bg-blue-100' => $paymentsStatus[$row->schoolId] === 'refund',
                'bg-green-200' => $paymentsStatus[$row->schoolId] === 'paid',
                'bg-red-600' => $paymentsStatus[$row->schoolId] === 'error',
                ])
                    title="payment {{ $paymentsStatus[$row->schoolId] }}"
                >
                    @php($payment = (float)$payments[$row->schoolId])
                    {{ number_format($payment, 2) }}
                </td>

                {{-- PAYMENT FORM BUTTON --}}
                <td class="border border-gray-200 px-1 text-center">
                    <button
                        type="button"
                        wire:click="createPayment({{ $row->schoolId }})"
                        class="bg-indigo-800 text-sm text-white px-2 rounded-lg shadow-lg"
                    >
                        Payment
                    </button>

                </td>

            </tr>

        @empty
            <td colspan="{{ count($columnHeaders) }}" class="border border-gray-200 text-center">
                No {{ $header }} found.
            </td>
        @endforelse
        </tbody>
    </table>

    {{-- LOADING COMPONENT AND SPINNER --}}
    <x-tables.loadingComponentAndSpinner/>
</div>
