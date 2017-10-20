<div class="form-group {{$errors->has($passing_variable) ? 'has-error' : null}}">
    <label for="{{$passing_variable}}" class="control-label">{{$passing_description}}<span class="text-danger"> {{$passing_error}}</span>
    </label>
    <div class="clearfix"></div>
    @if($isOperator)
        @if($disabled == null)
            <input name="{{$passing_variable}}" id="fileinput-upload" type="file" class="file" accept=".pdf">
            @if($errors->has($passing_variable))
                <label class="error" style="display: inline-block;">
                    {{$errors->first($passing_variable)}}
                </label>
            @endif
        @else
            @if(isset($cooperation[$passing_variable]))
                <a href="{{url('cooperations/download-document?id=' . $cooperation->id)}}" class="btn btn-theme rounded btn-slideright">Download</a>
            @else
                <a class="btn btn-theme rounded btn-slideright" disabled>Download</a><p class="text-danger"> File belum diupload</p>
            @endif
        @endif
    @else
        <i class="text-danger"> Apabila ingin mendownload file, hubungi Kerjasama Biro Rektor USU </i>
    @endif
</div>