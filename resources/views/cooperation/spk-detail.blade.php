@php
    if(!isset($mou_coops))
        $mou_coops = new App\Cooperation();
    if(!isset($faculties))
        $faculties = [];
    if(!isset($units))
        $units = [];
    if(!isset($disabled))
        $disabled = null;
    if(isset($prev_coop) && $prev_coop['coop_type'] == "ADDENDUM")
        $coop_id = $prev_coop['cooperation_id'];
    else
        $coop_id = $cooperation['cooperation_id'];
@endphp

<!-- SPK -->
<div id="SPK" style='display: none !important;'>
     {{-- <div class="form-group">
        <label for="name" class="control-label col-xs-12">Jenis SPK</label>
        @if($upd_mode == 'edit')
            <input type="hidden"  name="is_accidental" value="{{$cooperation['is_accidental']}}">
        @endif
        <div class='rdio radio-inline rdio-theme rounded'>
            <input type='radio' class='radio-inline nonaccspk' name="is_accidental" value='0' id='radio-type-rounded7'
               required {{$cooperation['is_accidental'] == '0' ? 'checked' : null}} 
               {{$upd_mode != 'create' ? 'disabled' : null}}>
            <label class='control-label' for='radio-type-rounded7'>Non Accidental</label> 
        </div>
        <div class='rdio radio-inline rdio-theme rounded'>
            <input type='radio' class='radio-inline accspk' name="is_accidental" id='radio-type-rounded8'
                   required value='1' {{$cooperation['is_accidental'] == '1' ? 'checked' : null}}
                    {{$upd_mode != 'create' ? 'disabled' : null}}>
            <label class='control-label' for='radio-type-rounded8'>Accidental</label>
        </div>
    </div>
 --}}
    {{-- @include('cooperation.coop-accidental-spk') --}}

    <!-- SPK -->
    <div class="form-group form-group-divider">
        <div class="form-inner">
            <h4 class="no-margin">Data SPK / Surat Perintah Kerjasama (Mohon Diisi)</h4>
        </div>
    </div>
    <div class="form-group {{$errors->has('subject_of_coop') ? 'has-error' : null}}">
        <label for="subject_of_coop" class="control-label">Subjek Kerjasama SPK</label>
        <textarea name="subject_of_coop" class="form-control"
                  placeholder="Subjek Kerjasama SPK" {{$disabled}}>{{$cooperation['subject_of_coop']}}</textarea>
        @if($errors->has('subject_of_coop'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('subject_of_coop')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('area_of_coop') ? 'has-error' : null}}">
        <label for="area_of_coop" class="control-label">Bidang Kerjasama SPK</label>
        <select class="form-control mb-15 select2" name='area_of_coop' {{$disabled}} data-placeholder="-- Pilih Bidang Kerjasama --" >
            <option value="" disabled selected>-- Pilih Bidang Kerjasama --</option>
            @foreach($areas as $area)
                <option value="{{$area->id}}" {{$cooperation['area_of_coop'] == $area->id ? 'selected' : null}}>{{$area->area_coop}}</option>
            @endforeach
        </select>
        @if($errors->has('area_of_coop'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('area_of_coop')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('implementation') ? 'has-error' : null}}">
        <label for="implementation" class="control-label">Implementasi</label>
        <textarea name="implementation" class="form-control" id="implementasi" placeholder="implementasi"
                 >{{$cooperation['implementation']}}</textarea>
        @if($errors->has('implementation'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('implementation')}}
            </label>
        @endif
    </div>
    @include('layout.input-date', ['passing_variable' => 'sign_date', 'passing_description' => 'Tanggal Tanda Tangan'])
    @include('layout.input-date', ['passing_variable' => 'end_date', 'passing_description' => 'Tanggal Berakhir'])
    
    <div class="form-group {{$errors->has('unit') ? 'has-error' : null}} ">
        <label for="unit" class="control-label">Unit yang melakukan kerjasama</label>
        <div>
            <select name='unit' class="form-control select2 mb-15" >
                <option value="" disabled selected>-- Pilih Unit --</option>
                @foreach($units as $unit)
                   @if(!empty($unit['code']))
                        <option value="{{$unit['code']}}"
                                {{$cooperation['unit'] == $unit['code'] ? 'selected' : null}}>
                            {{$unit['name']}}
                        </option>
                    @endif
                @endforeach
            </select>
            @if($errors->has('unit'))
                <label class="error" style="display: inline-block;">
                    {{$errors->first('unit')}}
                </label>
            @endif
        </div>
    </div>

    <div class="form-group">
        <label for="is_sub_unit">Kerjasama dengan sub unit / program studi </label>
        <div class='ckbox ckbox-theme'>
            <input type='checkbox' id='checkbox-default1'
                   value='1' name="is_sub_unit"
                    {{$cooperation['sub_unit'] != null ? 'checked' : null}}
                    {{$upd_mode != 'create' ? 'disabled' : null}}
            >
            <label class='' for='checkbox-default1'>Ya</label>
        </div>
    </div>

    <div class="form-group">
        <label for="sub_unit" class="control-label">Sub Unit</label>
        <div>
            <select name='sub_unit' class="form-control select2 mb-15">
                @if($upd_mode == 'display')
                    <option value="{{$cooperation['sub_unit']}}" selected>{{$cooperation['sub_unit']}}</option>
                @endif
            </select>
            @if($errors->has('sub_unit'))
                <label class="error" style="display: inline-block;">
                    {{$errors->first('sub_unit')}}
                </label>
            @endif
        </div>
    </div>

    <div class="form-group {{$errors->has('usu_doc_no') ? 'has-error' : null}}">
        <label for="usu_doc_no" class="control-label">Nomor Dokumen USU</label>
        <input name='usu_doc_no' class="form-control" type="text" placeholder="Nomor Dokumen USU"
               value="{{$cooperation['usu_doc_no']}}">
        @if($errors->has('usu_doc_no'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('usu_doc_no')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('partner_doc_no') ? 'has-error' : null}}">
        <label for="partner_doc_no" class="control-label">Nomor Dokumen Instansi Partner</label>
        <input name='partner_doc_no' class="form-control" type="text"
               placeholder="Nomor Dokumen Instansi Partner"
               value="{{$cooperation['partner_doc_no']}}">
        @if($errors->has('partner_doc_no'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('partner_doc_no')}}
            </label>
        @endif
    </div>
    @if($upd_mode != "display")
        <div class="form-group">
            <a href="#" class="btn btn-theme btn-md rounded table-addSPK" title="Tambah"><i class="fa fa-plus"></i></a>
        </div>
    @endif

    <div id="spk-table" class="form-group table-responsive">
        <table class="table">
            <thead>
            <th class="text-center">Nama Pekerjaan / Barang</th>
            <th class="text-center">Jumlah</th>
            <th class="text-center">Satuan</th>
            <th class="text-center">Total Harga</th>
            <th class="text-center">Keterangan</th>
            <th class="text-center">Hapus</th>
            </thead>
            <tbody>
            @foreach($coop_items as $coop_item)
                <tr class="text-center">
                    <td>
                        <input name="item_name[]" type="text" class="form-control" value="{{$coop_item['item_name']}}">
                    </td>
                    <td>
                        <input name="item_quantity[]" type="text" class="form-control"
                               data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'rightAlign': false"
                               value="{{$coop_item['item_quantity']}}">
                    </td>
                    <td>
                        <input name="item_uom[]" type="text" class="form-control"
                               value="{{$coop_item['item_uom']}}">
                    </td>
                    <td>
                        <input name="item_total_amount[]" type="text" class="form-control"
                               data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'rightAlign': false"
                               value="{{$coop_item['item_total_amount']}}">
                    </td>
                    <td>
                        <input name="item_annotation[]" type="text" class="form-control"
                               value="{{$coop_item['item_annotation']}}">
                    </td>
                    <td>
                        @if($upd_mode != "display")
                            <a href="#" class="table-remove btn btn-danger rounded"><i class="fa fa-trash"></i></a>
                        @endif
                    </td>
                </tr>
            @endforeach
            <tr class="hide text-center">
                <td>
                    <input name="item_name[]" type="text" class="form-control" disabled>
                </td>
                <td>
                    <input name="item_quantity[]" type="text" class="form-control"
                           data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'rightAlign': false" disabled>
                </td>
                <td>
                    <input name="item_uom[]" type="text" class="form-control" disabled>
                </td>
                <td>
                    <input name="item_total_amount[]" type="text" class="form-control"
                           data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'rightAlign': false" disabled>
                </td>
                <td>
                    <input name="item_annotation[]" type="text" class="form-control" disabled>
                </td>
                <td>
                    <a href="#" class="table-remove btn btn-danger rounded"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <label for="name" class="control-label">Nilai Kontrak</label>
        <input class="form-control" name='contract_amount' type="text" disabled
               data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true, 'rightAlign': false"
               value="{{$cooperation['contract_amount'].".00"}}">
    </div>
    @if($upd_mode=='create')
        @include('layout.input-upload', ['passing_variable' => 'file_name_ori', 'passing_description' => 'Dokumen', 'passing_error' => ' * File yang diupload harus sudah ditandatangani WR III. Saat addendum, kosongkan apabila file yang digunakan sama dengan SPK'])
    @elseif($upd_mode=='edit' || $upd_mode=='display')
        @include('layout.input-upload', ['passing_variable' => 'file_name_ori', 'passing_description' => 'Dokumen', 'passing_error' => ' * File yang diupload harus sudah ditandatangani WR III. Kosongkan apabila file tidak berubah'])
    @endif
</div>