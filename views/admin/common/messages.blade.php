@if(count($messages) > 0)
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible" role="alert" style="margin-bottom: 15px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <p><strong>提示：</strong></p>
            @foreach ($messages->all() as $message)
                <p><strong>{{ $message }}</strong></p>
            @endforeach
        </div>
    </div>
@endif
@if(count($errors) > 0)
    <div class="col-md-12">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            @foreach ($errors->all() as $error)
                <p><strong>{{ $error }}</strong></p>
            @endforeach
        </div>
    </div>
@endif