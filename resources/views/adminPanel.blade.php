@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header card-header-icon" data-background-color="blue">
            <i class="material-icons">message</i>
        </div>
        <div class="card-content">
            <h4 class="card-title">Add new notification message for all users</h4>
            <form action="/admin/newMessage" method="POST">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <input id="message-title" type="text" name="title" placeholder="Title" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <select id="message-type" class="selectpicker sp-added-height" name="type" data-style="btn btn-primary" title="Type">
                            <option value="warning">Warning</option>
                            <option value="error">Error</option>
                            <option value="success">Success</option>
                            <option value="info">Info</option>
                            <option value="question">Question</option>
                        </select>
                    </div>
                </div>
                <input id="message-button" type="text" name="confirmButtonText" placeholder="Confirm buttontext" class="form-control" />
                <textarea id="message-body" type="text" name="html" placeholder="Content" class="form-control" /></textarea>

                <button type="button" id="preview-message" class="btn">Preview</button>
                <input type="submit" class="btn btn-success" value="Send notification message" />
            </form>
        </div>

    </div>
@endsection

@section('script')
    <script src="{{ mix('/js/admin.min.js') }}"></script>
@endsection