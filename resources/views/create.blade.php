@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        <form method="POST" enctype='multipart/form-data' action="{{ (int)$spacecraft->id > 0? route('spacecrafts.update', $spacecraft->id): route('spacecrafts.store') }}">
                            @if($errors->any())
                                <div class="text-danger">
                                    {!!  implode('', $errors->all('<div>:message</div>')) !!}
                                </div>
                            @endif
                            {{ csrf_field() }}
                            <div>
                                <div class="form-group">
                                    <label>Image</label>
                                    <div>
                                        @if($spacecraft->image)
                                            <div>
                                                <img class="spacecraft-image" src="{{ \Illuminate\Support\Facades\Storage::url($spacecraft->image) }}">
                                            </div>
                                            <br />
                                        @endif
                                        <input type="file" name="image">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <div>
                                        <input type="text" name="name" value="{{ old('name')? old('name'): $spacecraft->name }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Class</label>
                                    <div>
                                        <select name="class">
                                            <option value="">None</option>
                                            <?php $selectedClass = old('class')? old('class'): $spacecraft->class; ?>
                                            @foreach (\App\Models\Spacecraft::CLASSES as $class)
                                                <option value="{{ $class }}" {{ $selectedClass == $class? ' selected="selected"': '' }}>{{ $class }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Crew</label>
                                    <div>
                                        <input type="text" name="crew" value="{{ old('crew')? old('crew'): $spacecraft->crew }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Value</label>
                                    <div>
                                        <input type="text" name="value" value="{{ old('value')? old('value'): $spacecraft->value }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <div>
                                        <select name="status">
                                            <?php
                                            $selectedStatus = old('status')? old('status'): $spacecraft->status;
                                            ?>
                                            <option value="">None</option>
                                            <option value="{{ \App\Models\Spacecraft::STATUS_OPERATIONAL }}"{{ $selectedStatus == \App\Models\Spacecraft::STATUS_OPERATIONAL? ' selected="selected"': '' }}>Operational</option>
                                            <option value="{{ \App\Models\Spacecraft::STATUS_DAMAGED }}"{{ $selectedStatus == \App\Models\Spacecraft::STATUS_DAMAGED? ' selected="selected"': '' }}>Damaged</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="button" class="btn-add-armament">Add armament</button>
                                    <div id="armaments">
                                        @if (old('armaments'))
                                            @foreach (old('armaments') as $key => $armament)
                                                <div class='armament-row'><br /><div><input placeholder='title' name='armaments[{{ $key }}][title]' value="{{ old('armaments')[$key]['title'] }}"> <input placeholder='qty' type='number' name='armaments[{{ $key }}][qty]' value="{{ old('armaments')[$key]['qty'] }}"> <button class='btn-remove-armament' type='button'>remove</button></div></div>
                                            @endforeach
                                        @elseif ((int)$spacecraft->id > 0 && $spacecraft->armaments()->count()> 0)
                                             <?php $indexOffset = 0; ?>
                                             <?php $armaments = $spacecraft->armaments()->get() ?>
                                             @foreach ($armaments as $key => $armament)
                                                 <div class='armament-row'><br /><div><input placeholder='title' name='armaments[{{ $key }}][title]' value="{{ $armament->title }}"> <input placeholder='qty' type='number' name='armaments[{{ $key }}][qty]' value="{{ $armament->qty }}"> <button class='btn-remove-armament' type='button'>remove</button></div></div>
                                                 <?php $indexOffset = $key; ?>
                                             @endforeach
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="{{ route('home') }}">Back to home</a>
                                    <button type="submit">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
