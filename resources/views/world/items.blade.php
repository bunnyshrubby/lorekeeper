@extends('world.layout')

@section('title')
    Items
@endsection

@section('content')
    {!! breadcrumbs(['World' => 'world', 'Items' => 'world/items']) !!}
    <h1>Items</h1>

    <div>
        {!! Form::open(['method' => 'GET', 'class' => '']) !!}
        <div class="form-inline justify-content-end">
            <div class="form-group ml-3 mb-3">
                {!! Form::text('name', Request::get('name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
            </div>
            <div class="form-group ml-3 mb-3">
                {!! Form::text('description', Request::get('description'), ['class' => 'form-control', 'placeholder' => 'Description Keyword']) !!}
            </div>
            <div class="form-group ml-3 mb-3">
                {!! Form::select('item_category_id', $categories, Request::get('item_category_id'), ['class' => 'form-control']) !!}
            </div>
            @if (Config::get('lorekeeper.extensions.item_entry_expansion.extra_fields'))
                <div class="form-group ml-3 mb-3">
                    {!! Form::select('artist', $artists, Request::get('artist'), ['class' => 'form-control']) !!}
                </div>
            @endif
        </div>
        <div class="form-inline justify-content-end">
            <div class="form-group ml-3 mb-3">
                {!! Form::select(
                    'sort',
                    [
                        'alpha' => 'Sort Alphabetically (A-Z)',
                        'alpha-reverse' => 'Sort Alphabetically (Z-A)',
                        'category' => 'Sort by Category',
                        'newest' => 'Newest First',
                        'oldest' => 'Oldest First',
                    ],
                    Request::get('sort') ?: 'category',
                    ['class' => 'form-control'],
                ) !!}
            </div>
            <div class="form-group ml-3 mb-3">
                {!! Form::submit('Search', ['class' => 'btn btn-primary']) !!}
            </div>
        </div>
    {!! Form::close() !!}
</div>

{!! $items->render() !!}
<div class="container-flex">
    <div class="row">
        @foreach($items as $item)
            <div class="col-xs-12 col-s-12 col-md-4 col-lg-4 col-xl-4 mb-3">
                <div class="card" style="height: 100%;">
        <div class="card-body">
        <?php
        $shops = App\Models\Shop\Shop::where(function($shops) {
            if(Auth::check() && Auth::user()->isStaff) return $shops;
            return $shops->where('is_staff', 0);
        })->whereIn('id', App\Models\Shop\ShopStock::where('item_id', $item->id)->pluck('shop_id')->toArray())->orderBy('sort', 'DESC')->get();
        ?>
        @include('world._item_entry', ['imageUrl' => $item->imageUrl, 'name' => $item->displayName, 'description' => $item->parsed_description, 'idUrl' => $item->idUrl, 'shops' => $shops])
        </div>
    </div>
@endforeach
{!! $items->render() !!}

<div class="text-center mt-4 small text-muted">{{ $items->total() }} result{{ $items->total() == 1 ? '' : 's' }} found.</div>

@endsection
