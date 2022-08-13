<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">View Documents</h2>
            </div>
        </div>
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