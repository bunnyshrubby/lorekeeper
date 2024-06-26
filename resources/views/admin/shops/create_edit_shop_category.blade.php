@extends('admin.layout')

@section('admin-title') Shop Categories @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', 'Shop Categories' => 'admin/data/shops/shop-categories', ($category->id ? 'Edit' : 'Create').' Category' => $category->id ? 'admin/data/shops/shop-categories/edit/'.$category->id : 'admin/data/shops/shop-categories/create']) !!}

<h1>{{ $category->id ? 'Edit' : 'Create' }} Category
    @if($category->id)
        <a href="#" class="btn btn-danger float-right delete-category-button">Delete Category</a>
    @endif
</h1>

{!! Form::open(['url' => $category->id ? 'admin/data/shops/shop-categories/edit/'.$category->id : 'admin/data/shops/shop-categories/create', 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="form-group">
    {!! Form::label('Name') !!}
    {!! Form::text('name', $category->name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label('World Page Image (Optional)') !!} {!! add_help('This image is used only on the world information pages.') !!}
    <div>{!! Form::file('image') !!}</div>
    <div class="text-muted">Recommended size: 100px x 100px</div>
    @if($category->has_image)
        <div class="form-check">
            {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
            {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $category->description, ['class' => 'form-control wysiwyg']) !!}
</div>


<div class="text-right">
    {!! Form::submit($category->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

@if($category->id)
    <h3>Preview</h3>
<div class="col-md-12">
    <div class="card mb-2 text-center">
        <div class="card-header d-flex flex-wrap no-gutters">
            <h1 class="col-12">
                <img src="{{ $category->categoryImageUrl }}" style="margin-right: 10px">{!! $category->name !!} <img src="{{ $category->categoryImageUrl }}" style="margin-left: 10px; ">
            </h1>
            <div class="col-12 text-center">
            @if($category->description) {!! $category->description !!}@endif

            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {    
    $('.delete-category-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/data/shops/shop-categories/delete') }}/{{ $category->id }}", 'Delete Category');
    });
});
    
</script>
@endsection