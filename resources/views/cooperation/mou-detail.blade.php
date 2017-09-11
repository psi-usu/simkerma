@php
    if(isset($coop_relations))
    {
        if(!$coop_relations->isEmpty())
        {
            $disabled = 'disabled';
        }
    }

@endphp

<!-- MOU -->
<div id="MoU" style='display: none !important;'>
    <div class="form-group {{$errors->has('partner_id') ? 'has-error' : null}}">
        <label for="name" class="control-label">Instansi Partner</label>
        <select class="form-control mb-15 select2" name='partner_id' {{$disabled}} data-placeholder="-- Pilih Instansi Partner --"  required>
            <option value="" disabled selected>-- Pilih Instansi Partner --</option>
            @foreach($partners as $partner)
                <option value="{{$partner->id}}" {{$cooperation['partner_id'] == $partner->id ? 'selected' : null}}>{{$partner->name}}</option>
            @endforeach
        </select>
        @if($errors->has('partner_id'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('partner_id')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('area_of_coop') ? 'has-error' : null}}">
        <label for="area_of_coop" class="control-label">Bidang Kerjasama</label>
        <textarea name="area_of_coop" class="form-control"
                  placeholder="Bidang Kerjasama" {{$disabled}} required>{{$cooperation['area_of_coop']}}</textarea>
        @if($errors->has('area_of_coop'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('area_of_coop')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('sign_date') ? 'has-error' : null}}">
        <label for="sign_date" class="control-label">Tanggal Tanda Tangan</label>
        <input name="sign_date" class="form-control" id="datepicker" type="text" placeholder="Tanggal Tanda Tangan"
               value="{{$cooperation['sign_date']}}" {{$disabled}} required>
        @if($errors->has('sign_date'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('sign_date')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('end_date') ? 'has-error' : null}}">
        <label for="end_date" class="control-label">Tanggal Berakhir</label>
        <input name="end_date" class="form-control" id="datepicker2" type="text" placeholder="Tanggal Berakhir"
               value="{{$cooperation['end_date']}}" {{$disabled}} required>
        @if($errors->has('end_date'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('end_date')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('form_of_coop') ? 'has-error' : null}}">
        <label for="form_of_coop" class="control-label">Bentuk Kerjasama</label>
        <select name='form_of_coop' class="form-control mb-15 select2" {{$disabled}} required>
            <option value="" disabled selected>-- Pilih Bentuk Kerjasama --</option>
            <option value="Dalam Negeri" {{$cooperation['form_of_coop'] == 'Dalam Negeri' ? 'selected' : null}}>Dalam
                Negeri
            </option>
            <option value="Luar Negeri" {{$cooperation['form_of_coop'] == 'Luar Negeri' ? 'selected' : null}}>Luar
                Negeri
            </option>
        </select>
        @if($errors->has('form_of_coop'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('form_of_coop')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('usu_doc_no') ? 'has-error' : null}}">
        <label for="usu_doc_no" class="control-label">Nomor Dokumen USU</label>
        <input name='usu_doc_no' class="form-control" type="text" placeholder="Nomor Dokumen USU"
               value="{{$cooperation['usu_doc_no']}}" {{$disabled}} required>
        @if($errors->has('usu_doc_no'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('usu_doc_no')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('partner_doc_no') ? 'has-error' : null}}">
        <label for="partner_doc_no" class="control-label">Nomor Dokumen Instansi Partner</label>
        <input name='partner_doc_no' class="form-control" type="text"
               placeholder="Nomor Dokumen Instansi Partner" value="{{$cooperation['partner_doc_no']}}" {{$disabled}} required>
        @if($errors->has('partner_doc_no'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('partner_doc_no')}}
            </label>
        @endif
    </div>
    <div class="form-group">
        <label for="file_name_ori" class="control-label col-md-12">Dokumen</label>
        @if($disabled == null)
            <input name="file_name_ori" id="fileinput-mou-doc" type="file" class="file" accept=".pdf">
            @if($errors->has('file_name_ori'))
                <label class="error" style="display: inline-block;">
                    {{$errors->first('file_name_ori')}}
                </label>
            @endif
        @else
            <a href="{{url('cooperations/download-document?id=' . $cooperation->id)}}" class="btn btn-theme rounded">download</a>
        @endif
    </div>
</div>
<!-- End MOU -->