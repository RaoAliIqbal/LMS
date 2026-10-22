@extends('admin.layouts.app')

@section('title')
    Add Student + Course
@endsection
@section('content')
    <div class="content-main" id="content-main">
        <div class="padding">
            <div class="row">
                <div class="col-md-12">
                    @include('layouts.partials.alertBox')
                </div>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <div class="box">
                        <div class="box-header">
                            <h2>Add Student + Grant Course</h2>
                            <p class="text-muted" style="margin: 8px 0 0;">
                                Use when a student paid but does not appear in the database (registration failed).
                            </p>
                        </div>
                        <div class="box-body">
                            @if(!count($lessons))
                                <div class="alert alert-danger">
                                    Course list could not be loaded. Please refresh or call 1-888-900-3382.
                                </div>
                            @else
                            <form method="POST" action="{{ route('admin_store_client') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name *</label>
                                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name *</label>
                                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email *</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password *</label>
                                            <input type="text" name="password" class="form-control" value="{{ old('password', 'Welcome2026!') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Phone *</label>
                                            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>City *</label>
                                            <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Zipcode *</label>
                                            <input type="text" name="zipcode" class="form-control" value="{{ old('zipcode') }}" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>State *</label>
                                            <select name="state" class="form-control" required>
                                                @foreach($states as $state)
                                                    <option value="{{ $state->name }}" {{ old('state') == $state->name ? 'selected' : '' }}>{{ $state->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Clinic Name</label>
                                            <input type="text" name="clinic_name" class="form-control" value="{{ old('clinic_name') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Course to grant *</label>
                                            <select name="lesson_id" class="form-control" required>
                                                <option value="" disabled selected>Select course</option>
                                                @foreach($lessons as $lesson)
                                                    @php $code = $courseCodes[$lesson->id] ?? 'Course'; @endphp
                                                    <option value="{{ $lesson->id }}" {{ old('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                                        {{ $code }} — {{ $lesson->title }} (${{ $lesson->price }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="text-muted">CAT17 = SD Initial ($275) | CAT42 = Oregon Initial ($179)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Price paid</label>
                                            <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price') }}" placeholder="Leave blank = course price">
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Create Student &amp; Add Course</button>
                                <a href="{{ route('admin_client_view') }}" class="btn btn-default">Cancel</a>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
