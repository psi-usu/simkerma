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
        <select class="form-control mb-15 select2" name='partner_id' {{$disabled}} data-placeholder="-- Pilih Instansi Partner --" >
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
                  placeholder="Bidang Kerjasama" {{$disabled}}>{{$cooperation['area_of_coop']}}</textarea>
        @if($errors->has('area_of_coop'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('area_of_coop')}}
            </label>
        @endif
    </div>
    @include('layout.input-date', ['passing_variable' => 'sign_date', 'passing_description' => 'Tanggal Tanda Tangan'])
    @include('layout.input-date', ['passing_variable' => 'end_date', 'passing_description' => 'Tanggal Berakhir'])
    <div class="form-group {{$errors->has('form_of_coop') ? 'has-error' : null}}">
        <label for="form_of_coop" class="control-label">Bentuk Kerjasama</label>
        <select name='form_of_coop' class="form-control mb-15 select2" {{$disabled}}>
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
               value="{{$cooperation['usu_doc_no']}}" {{$disabled}}>
        @if($errors->has('usu_doc_no'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('usu_doc_no')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('partner_doc_no') ? 'has-error' : null}}">
        <label for="partner_doc_no" class="control-label">Nomor Dokumen Instansi Partner</label>
        <input name='partner_doc_no' class="form-control" type="text"
               placeholder="Nomor Dokumen Instansi Partner" value="{{$cooperation['partner_doc_no']}}" {{$disabled}}>
        @if($errors->has('partner_doc_no'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('partner_doc_no')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('benefit') ? 'has-error' : null}}">
        <label for="benefit" class="control-label">Manfaat Kerjasama</label>
        <textarea name="benefit" class="form-control" id="bid_kerma_moa"
                  placeholder="Manfaat Kerjasama">{{$cooperation['benefit']}}</textarea>
        @if($errors->has('benefit'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('benefit')}}
            </label>
        @endif
    </div>
    @if($upd_mode=='create')
        @include('layout.input-upload', ['passing_variable' => 'file_name_ori', 'passing_description' => 'Dokumen', 'passing_error' => ' * File yang diupload harus sudah ditandatangani WR III. Saat addendum, kosongkan apabila file yang digunakan sama dengan MOA'])
    @elseif($upd_mode=='edit' || $upd_mode=='display')
        @include('layout.input-upload', ['passing_variable' => 'file_name_ori', 'passing_description' => 'Dokumen', 'passing_error' => ' * File yang diupload harus sudah ditandatangani WR III. Kosongkan apabila file tidak berubah'])
    @endif
</div>
<!-- End MOU -->