<div id="nonAccidentalSPK" style='display: none;'>
    <div class="form-group">
        <label for="cooperation_id" class="control-label">Pilih MOU / Nota Kesepahaman</label>
        <select name="cooperation_id" class="form-control select2" style="width: 100%;" required>
            <option value="" disabled selected>-- Pilih MOU berdasarkan Bidang Kerjasama --</option>
            @foreach($mou_coops as $mou_coop)
                <option value="{{$mou_coop['id']}}"
                        {{$mou_coop['id'] == $coop_id ? "selected" : null}}>
                    {{$mou_coop['partner']['name']}} - {{$mou_coop['partner_doc_no']}}
                </option>
            @endforeach
        </select>
        @if($errors->has('cooperation_id'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('cooperation_id')}}
            </label>
        @endif
    </div>

    <!-- MOU INFORMATION -->
    <div class="form-group form-group-divider">
        <div class="form-inner">
            <h4 class="no-margin">Informasi MOU / Nota Kesepahaman (Display Only)</h4>
        </div>
    </div>
    <div class="form-group">
        <label for="mou_detail_partner_id" class="control-label">Instansi Kerjasama</label>
        <input name="mou_detail_partner_id" class="form-control mb-15" type="text" id='instansi_kerma' disabled>
    </div>
    <div class="form-group">
        <label for="mou_detail_subject_of_coop" class="control-label"> Subjek Kerjasama MOU</label>
        <input name="mou_detail_subject_of_coop" class="form-control mb-15" type="text" id='bid_kerma' disabled>
    </div>
    <div class="form-group">
        <label for="mou_detail_sign_date" class="control-label">Tanggal Tanda Tangan</label>
        <input name="mou_detail_sign_date" class="form-control" id="tgl_tanda_tangan" type="text" disabled>
    </div>
    <div class="form-group">
        <label for="mou_detail_end_date" class="control-label">Tanggal Berakhir</label>
        <input name="mou_detail_end_date" class="form-control" id="tgl_berakhir" type="text" disabled>
    </div>
    <div class="form-group">
        <label for="mou_detail_usu_doc_no" class="control-label">Nomor Dokumen USU</label>
        <input name='mou_detail_usu_doc_no' class="form-control" id="nomor_dokumen_usu" type="text" disabled>
    </div>
    <div class="form-group">
        <label for="mou_detail_partner_doc_no" class="control-label">Nomor Dokumen Instansi Partner</label>
        <input name='mou_detail_partner_doc_no' class="form-control" id="nomor_dokumen_usu" type="text" disabled>
    </div>
    <!-- END MOU INFORMATION -->
</div>

<div id="AccidentalSPK" style='display: none;'>
    <div class="form-group {{$errors->has('partner_id') ? 'has-error' : null}}">
        <label for="name" class="control-label">Instansi Partner</label>
        <select class="form-control mb-15 select2" name='partner_id' {{$disabled}} data-placeholder="-- Pilih Instansi Partner --" required>
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
    <div class="form-group {{$errors->has('reason') ? 'has-error' : null}}">
        <label for="reason" class="control-label">Alasan dibuat Accidental</label>
        <textarea name="reason" class="form-control"
                  placeholder="Alasan dibuat Accidental" {{$disabled}} required>{{$cooperation['reason']}}</textarea>
        @if($errors->has('reason'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('reason')}}
            </label>
        @endif
    </div>
</div>
