<div class="row">
    <div class="col-md-12">
        <div class="form-group">
    <label for="type">Type <small class='validation-hints'>*</small></label>
    {!! Form::select("type", [], null, ["class"=>"select2 form-control", "placeholder"=>"Type"]) !!}
    <span class="validation-error">{{ $errors->first("type") }}</span>
</div>
<div class="form-group">
    <label for="name">Name <small class='validation-hints'>*</small></label>
    {!! Form::text("name", null, ["class"=>"form-control", "placeholder"=>"Name"]) !!}
    <span class="validation-error">{{ $errors->first("name") }}</span>
</div>
<div class="form-group">
    <label for="email">Email <small class='validation-hints'>*</small></label>
    {!! Form::email("email", null, ["class"=>"form-control", "placeholder"=>"Email"]) !!}
    <span class="validation-error">{{ $errors->first("email") }}</span>
</div>
<div class="form-group">
    <label for="password">Password <small class='validation-hints'>*</small></label>
    {!! Form::password("password", ["class"=>"form-control", "placeholder"=>"Password"]) !!}
    <span class="validation-error">{{ $errors->first("password") }}</span>
</div>
    </div>
</div>