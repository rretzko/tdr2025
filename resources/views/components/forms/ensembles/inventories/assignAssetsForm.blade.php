<div class="p-2 ">

    {{-- STUDENT NAME --}}
    <div class="flex flex-row space-x-2">
        <label>
            Student Name:
        </label>
        <div class="font-bold">
            {{ $assetForm->nameAlpha }}
        </div>
    </div>

    {{-- STATUS --}}
    <div class="flex flex-row space-x-2">
        <label>
            Ensemble Status:
        </label>
        <div class="font-bold">
            {{ $assetForm->ensembleStatus }}
        </div>
    </div>

    {{-- GRADE/CLASS_OF --}}
    <div class="flex flex-row space-x-2">
        <label>
            Grade:
        </label>
        <div class="font-bold">
            {{ $assetForm->gradeClassOf }}
        </div>
    </div>

    {{-- ASSETS TABLE --}}
    <table>
        <thead>
        <tr>
            <th class="px-2 border border-gray-400">Asset</th>
            <th class="px-2 border border-gray-400">Assigned</th>
            <th class="px-2 border border-gray-400">Available</th>
        </tr>
        </thead>
        <tbody>
        @forelse($assetForm->assets AS $id => $name)
            <tr>
                <td class="px-2 border border-gray-400">
                    {{ $name }}
                </td>
                <td class="px-2 border border-gray-400">
                    <x-forms.elements.livewire.selectWide
                        label="label"
                        name="form.assetIds"
                        :options="$availables[$id]"
                    />
                </td>
                <td class="px-2 border border-gray-400">
                    available
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">
                    No assets found.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

</div>
