<div class="relative w-full">

    <table class="px-4 shadow-lg w-full">
        <thead>
        <tr>
            <th class='border border-gray-200 px-1'>
                ###
            </th>
            <th>
                student name
            </th>
            <th>
                school
            </th>
            <th>
                amount
            </th>
            <th>
                transaction id
            </th>
            <th>
                comments
            </th>
            <th>
                date
            </th>
        </tr>
        </thead>
        <tbody>
        @forelse($rows AS $row)
            <tr class=" odd:bg-green-50 ">
                <td class="text-center">
                    {{ $loop->iteration }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->name }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->schoolName }}
                </td>
                <td class="border border-gray-200 px-1">
                    ${{ $row->amount }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->transactionId }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->comments }}
                </td>
                <td class="border border-gray-200 px-1">
                    {{ $row->createdAt }}
                </td>

            </tr>

        @empty
            <td colspan="5" class="border border-gray-200 text-center">
                No participation fee payments found.
            </td>
        @endforelse
        </tbody>
    </table>

</div>
