@php($frameId = Illuminate\Support\Str::random(16))

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div class="border rounded">
        <iframe
                srcdoc="{{ $getState() }}"
                src="{{ $frameId }}"
                id="{{ $frameId }}"
                class="w-full rounded h-[500px]"
        ></iframe>
    </div>
</x-dynamic-component>

@push('scripts')
    <script>
        console.log('test')
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const iframe = document.getElementById('{{ $frameId }}');
            iframe.style.height = `${iframe.contentWindow.document.body.offsetHeight}px`;
        });
    </script>
@endpush
