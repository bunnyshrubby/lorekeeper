@extends('galleries.layout')

@section('gallery-title') {{ $submission->title }} Log @endsection

@section('gallery-content')
{!! breadcrumbs(['gallery' => 'gallery', $submission->gallery->displayName => 'gallery/'.$submission->gallery->id, $submission->title => 'gallery/view/'.$submission->id, 'Log Details' => 'gallery/queue/'.$submission->id ]) !!}

<h1>Log Details
    <span class="float-right badge badge-{{ $submission->status == 'Pending' ? 'secondary' : ($submission->status == 'Accepted' ? 'success' : 'danger') }}">{{ $submission->collaboratorApproved ? $submission->status : 'Pending Collaborator Approval' }}</span>
</h1>

@include('galleries._queue_submission', ['queue' => false, 'key' => 0])

<div class="row">
    <div class="col-md">
        <div class="card">
            <div class="card-header">
                <h4>Staff Comments{!! isset($submission->staff_id) ? ' - '.$submission->staff->displayName : '' !!}</h4>
                {!! Auth::user()->hasPower('staff_comments') ? '(Visible to '.$submission->credits.')' : '' !!}
            </div>
            <div class="card-body">
                @if(Auth::user()->hasPower('staff_comments'))
                    {!! Form::open(['url' => 'admin/gallery/edit/'.$submission->id.'/comment']) !!}
                        <div class="form-group">
                            {!! Form::label('staff_comments', 'Staff Comments') !!}
                            {!! Form::textarea('staff_comments', $submission->staff_comments, ['class' => 'form-control wysiwyg']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::checkbox('alert_user', 1, true, ['class' => 'form-check-input', 'data-toggle' => 'toggle', 'data-onstyle' => 'danger']) !!}
                            {!! Form::label('alert_user', 'Notify User', ['class' => 'form-check-label ml-3']) !!} {!! add_help('This will send a notification to the user that the staff comments on their submission have been edited.') !!}
                        </div>
                        <div class="text-right">
                            {!! Form::submit('Edit Comments', ['class' => 'btn btn-primary']) !!}
                        </div>
                    {!! Form::close() !!}
                @else
                    {!! isset($submission->parsed_staff_comments) ? $submission->parsed_staff_comments : '<i>No comments provided.</i>' !!}
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @if(Auth::user()->hasPower('manage_submissions') && $submission->collaboratorApproved)
            <div class="card mb-4">
                <div class="card-header">
                    <h5>[Admin] Vote Info</h5>
                </div>
                <div class="card-body">
                    @if(isset($submission->vote_data) && $submission->voteData->count())
                        @foreach($submission->voteData as $voter=>$vote)
                            <li>
                                {!! App\Models\User\User::find($voter)->displayName !!} {{ $voter == Auth::user()->id ? '(you)' : '' }}: <span {!! $vote == 2 ? 'class="text-success">Accept' : 'class="text-danger">Reject' !!}</span>
                            </li>
                        @endforeach
                    @else
                        <p>No votes have been cast yet!</p>
                    @endif
                </div>
            </div>
        @endif
        @if(Settings::get('gallery_submissions_reward_currency') && $submission->gallery->currency_enabled)
            <div class="card">
                <div class="card-header">
                    <h5>{!! $currency->displayName !!} Award Info</h5>
                </div>
                <div class="card-body">
                    @if($submission->status == 'Accepted')
                        @if(Auth::user()->hasPower('manage_submissions'))
                            power
                        @else
                            view
                        @endif
                    @else
                        <p>This submission is not eligible for currency awards{{ $submission->status == 'Pending' ? ' yet-- it must be accepted first' : '' }}.</p>
                    @endif
                    <hr/>
                    <h6>Form Responses:</h6>
                    @foreach($submission->data['currencyData'] as $key=>$data)
                        <p>
                            @if(isset($data))
                                <strong>{{ Config::get('lorekeeper.group_currency_form')[$key]['name'] }}:</strong><br/>
                                @if(Config::get('lorekeeper.group_currency_form')[$key]['type'] == 'choice')
                                    @if(isset(Config::get('lorekeeper.group_currency_form')[$key]['multiple']) && Config::get('lorekeeper.group_currency_form')[$key]['multiple'] == 'true')
                                        @foreach($data as $answer)
                                            {{ Config::get('lorekeeper.group_currency_form')[$key]['choices'][$answer] }}<br/>
                                        @endforeach
                                    @else
                                        {{ Config::get('lorekeeper.group_currency_form')[$key]['choices'][$data] }}
                                    @endif
                                @else
                                    {{ Config::get('lorekeeper.group_currency_form')[$key]['type'] == 'checkbox' ? (Config::get('lorekeeper.group_currency_form')[$key]['value'] == $data ? 'True' : 'False') : $data }}
                                @endif
                            @endif
                        </p>
                    @endforeach
                    @if(Auth::user()->hasPower('manage_submissions'))
                    <h6>[Admin]</h6>
                        <p class="text-center">
                            <strong>Calculated Total:</strong> {{ $submission->data['total'] }}
                            @if($submission->characters->count())
                                 ・ <strong> Times {{ $submission->characters->count() }} Characters:</strong> {{ round($submission->data['total'] * $submission->characters->count()) }}
                            @endif
                            @if($submission->collaborators->count())
                                <br/><strong>Divided by {{ $submission->collaborators->count() }} Collaborators:</strong> {{ round($submission->data['total'] / $submission->collaborators->count()) }}
                                @if($submission->characters->count())
                                    ・ <strong> Times {{ $submission->characters->count() }} Characters:</strong> {{ round(round($submission->data['total'] * $submission->characters->count()) / $submission->collaborators->count()) }}
                                @endif
                            @endif
                            <br/>For a suggested {!! $currency->display(
                                round(
                                    ($submission->characters->count() ? round($submission->data['total'] * $submission->characters->count()) : $submission->data['total']) / ($submission->collaborators->count() ? $submission->collaborators->count() : '1')
                                )
                            ) !!}{{ $submission->collaborators->count() ? ' per collaborator' : ''}}
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<?php $galleryPage = true; 
$sideGallery = $submission->gallery ?>

@endsection