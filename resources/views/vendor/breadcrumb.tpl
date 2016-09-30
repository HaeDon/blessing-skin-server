<ol class="breadcrumb">
    <li><i class="fa fa-tags"></i> {{ trans('skinlib.filter.now-showing') }}</li>
    <li>
        @if ($filter == "skin")
            {{ trans('skinlib.filter.skin') }}
            <small>{{ trans('skinlib.filter.any-model') }}</small>
        @elseif ($filter == "steve")
            {{ trans('skinlib.filter.any-model') }}
            <small>({{ trans('skinlib.filter.steve-model') }})</small>
        @elseif ($filter == "alex")
            {{ trans('skinlib.filter.skin') }}
            <small>{{ trans('skinlib.filter.alex-model') }}</small>
        @elseif ($filter == "cape")
            {{ trans('skinlib.filter.cape') }}
        @elseif ($filter == "user")
            {{ trans('skinlib.filter.uploader', ['name' => (new App\Models\User($_GET['uid']))->getNickName()]) }}
        @endif
    </li>
    <li class="active">
        @if ($sort == "time")
            {{ trans('skinlib.sort.newest-uploaded') }}
        @elseif ($sort == "likes")
            {{ trans('skinlib.sort.most-likes') }}
        @endif
    </li>
</ol>
