<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Pull Sheet</title>
</head>
<body>
<div>
    <header style="margin-bottom: 1rem; font-weight: bold;">
        @php($fdateTime = date('M d, Y g:i a'))
        Pull Sheet for {{ $fdateTime }}.
    </header>

    <div>
        <style>
            table {
                border-collapse: collapse;
            }

            td, th {
                border: 1px solid gray;
                padding: 0 0.25rem;
            }
        </style>
        <table>
            <thead>
            <tr>
                <th>###</th>
                <th>item</th>
                <th>location</th>
                <th>pulled?</th>
            </tr>
            </thead>
            @forelse($libItems AS $libItem)
                <tr>
                    <td style="text-align: center">{{ $loop->iteration }}</td>
                    <td style="text-align: left">{!! $libItem->longLink()  !!}</td>
                    <th>location</th>
                    <th>
                        <input type="checkbox"/>
                    </th>
                </tr>
            @empty
                <tr>
                    <th>
                        No pull sheet items found.
                    </th>
                </tr>
            @endforelse
        </table>
    </div>
</div>
</body>
</html>
