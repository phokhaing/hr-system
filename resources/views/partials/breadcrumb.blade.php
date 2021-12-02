<ul class="breadcrumb">
    <li>
        <a href="{{ url('/') }}"> Dashboard</a>
    </li>
    <?php $link = url('/'); ?>
    @for($i = 1; $i <= count(Request::segments()); $i++)
        <li>
            @if($i < count(Request::segments()) & $i > 0)
                @if(strlen(Request::segment($i)) > 100)
                    @continue
                @endif
                <?php $link .= "/" . Request::segment($i); ?>
                <a class="text-capitalize" href="<?= $link ?>">{{Request::segment($i)}}</a>
            @else
                <a href="javascript:void(0)" class="text-capitalize text-black">{{Request::segment($i)}}</a>
            @endif
        </li>
    @endfor
</ul>

@section('css')
    <style>
        .breadcrumb {
            background-color: #ffffff;
        }
    </style>
@endsection