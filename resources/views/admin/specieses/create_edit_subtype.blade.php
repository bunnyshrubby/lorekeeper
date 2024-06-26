@extends('admin.layout')

@section('admin-title') {{ ucfirst(__('lorekeeper.subtype')) }} @endsection

@section('admin-content')
{!! breadcrumbs(['Admin Panel' => 'admin', ucfirst(__('lorekeeper.subtypes')) => 'admin/data/subtypes', ($subtype->id ? 'Edit' : 'Create').' '.ucfirst(__('lorekeeper.subtype')) => $subtype->id ? 'admin/data/subtypes/edit/'.$subtype->id : 'admin/data/subtypes/create']) !!}

<h1>{{ $subtype->id ? 'Edit' : 'Create' }} {{ ucfirst(__('lorekeeper.subtype')) }}
    @if($subtype->id)
        <a href="#" class="btn btn-danger float-right delete-subtype-button">Delete {{ ucfirst(__('lorekeeper.subtype')) }}</a>
    @endif
</h1>

{!! Form::open(['url' => $subtype->id ? 'admin/data/subtypes/edit/'.$subtype->id : 'admin/data/subtypes/create', 'files' => true]) !!}

<h3>Basic Information</h3>

<div class="form-group">
    {!! Form::label('Name') !!}
    {!! Form::text('name', $subtype->name, ['class' => 'form-control']) !!}
</div>

<div class="form-group">
    {!! Form::label(ucfirst(__('lorekeeper.species'))) !!}
    {!! Form::select('species_id', $specieses, $subtype->species_id, ['class' => 'form-control']) !!}
</div>
<?/*
<div class="form-group">
    {!! Form::label('Traits') !!}
    {!! Form::select('feature_id[]', $features, null, ['class' => 'form-control mr-2 feature-select', 'placeholder' => 'Select Trait']) !!}
    {!! Form::text('feature_data[]', null, ['class' => 'form-control mr-2', 'placeholder' => 'Extra Info (Optional)']) !!}
    <a href="#" class="remove-feature btn btn-danger mb-2">×</a>
</div>

<div class="form-group">
    {!! Form::label('Items') !!}
    {!! Form::select('item_ids[]', $items, null, ['class' => 'form-control mr-2 item-select', 'placeholder' => 'Select Item']) !!}
    {!! Form::text('quantities[]', 1, ['class' => 'form-control mr-2', 'placeholder' => 'Quantity']) !!}
    <a href="#" class="remove-item btn btn-danger mb-2">×</a>
</div>
*/?>

<div class="form-group">
    {!! Form::label('World Page Image (Optional)') !!} {!! add_help('This image is used only on the world information pages.') !!}
    <div>{!! Form::file('image') !!}</div>
    <div class="text-muted">Recommended size: 200px x 200px</div>
    @if($subtype->has_image)
        <div class="form-check">
            {!! Form::checkbox('remove_image', 1, false, ['class' => 'form-check-input']) !!}
            {!! Form::label('remove_image', 'Remove current image', ['class' => 'form-check-label']) !!}
        </div>
    @endif
</div>

<div class="form-group">
    {!! Form::label('Description (Optional)') !!}
    {!! Form::textarea('description', $subtype->description, ['class' => 'form-control wysiwyg']) !!}
</div>

<div class="text-right">
    {!! Form::submit($subtype->id ? 'Edit' : 'Create', ['class' => 'btn btn-primary']) !!}
</div>

{!! Form::close() !!}

@if($subtype->id)
    <h3>Preview</h3>
    <div class="card mb-3">
        <div class="card-body">
            @include('world._subtype_entry', ['subtype' => $subtype])
        </div>
    </div>
@endif

@endsection

@section('scripts')
@parent
<script>
$( document ).ready(function() {
    $('.delete-subtype-button').on('click', function(e) {
        e.preventDefault();
        loadModal("{{ url('admin/data/subtypes/delete') }}/{{ $subtype->id }}", 'Delete {{ucfirst(__('lorekeeper.subtype'))}}');
    });
});

</script>
@endsection
