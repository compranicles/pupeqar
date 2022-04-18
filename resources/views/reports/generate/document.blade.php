<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('View Documents') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <table>
                    @foreach ($reportDocuments as $document)
                    <tr>
                        <td>
                            <a href="{{ route('document.view', $document) }}" target="_blank" class="btn-link">{{ $document }}</a>
                        </td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
    </div>
   
</x-app-layout>