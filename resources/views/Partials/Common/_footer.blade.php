<div class="footer">
    <div class="row">
        @php
            $build = file_get_contents(base_path('build.txt'));
            $build = explode('##', $build);
        @endphp

        <div class="col-md-12 text-center">
            <p class="small">{{ $build[0] }}&#8226{{ $build[1] }}</p>
        </div>
    </div>
</div>