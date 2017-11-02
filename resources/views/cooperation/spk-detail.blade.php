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
    <div class="form-group {{$errors->has('cooperation_id') ? 'has-error' : null}}">
        <label for="cooperation_id" class="control-label">Pilih MOU / Nota Kesepahaman</label>
        <select name="cooperation_id" class="form-control select2" style="width: 100%;" required>
            <option value="" disabled selected>-- Pilih MOU berdasarkan Bidang Kerjasama --</option>
            @foreach($mou_coops as $mou_coop)
                <option value="{{$mou_coop['id']}}"
                        {{$mou_coop['id'] == $coop_id ? "selected" : null}}>
                    {{$mou_coop['partner']['name']}} - {{$mou_coop['area_of_coop']}}
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
        <label for="mou_detail_area_of_coop" class="control-label"> Bidang Kerjasama</label>
        <input name="mou_detail_area_of_coop" class="form-control mb-15" type="text" id='bid_kerma' disabled>
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

    <!-- SPK -->
    <div class="form-group form-group-divider">
        <div class="form-inner">
            <h4 class="no-margin">Data SPK / Surat Perintah Kerjasama (Mohon Diisi)</h4>
        </div>
    </div>
    <div class="form-group {{$errors->has('area_of_coop') ? 'has-error' : null}}">
        <label for="area_of_coop" class="control-label">Bidang Kerjasama SPK / Surat Perintah Kerjasama</label>
        <textarea name="area_of_coop" class="form-control" id="bid_kerma_spk"
                  placeholder="Bidang Kerjasama SPK / Surat Perintah Kerjasama" required>{{$cooperation['area_of_coop']}}</textarea>
        @if($errors->has('area_of_coop'))
            <label class="error" style="display: inline-block;">
                {{$errors->first('area_of_coop')}}
            </label>
        @endif
    </div>
    <div class="form-group {{$errors->has('implementation') ? 'has-error' : null}}">
        <label for="implementation" class="control-label">Implementasi</label>
        <textarea name="implementation" class="form-control" id="implementasi" placeholder="implementasi"
                  required>{{$cooperation['implementation']}}</textarea>
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
            <select name='unit' class="form-control select2 mb-15">
                <option value="" disabled selected>-- Pilih Unit --</option>
                @foreach($faculties as $faculty)
                    <option value="{{$faculty['code']}}"
                            {{$cooperation['unit'] == $faculty['code'] ? 'selected' : null}}>
                        {{$faculty['name']}}
                    </option>
                @endforeach
                @foreach($units as $unit)
                    <option value="{{$unit['code']}}"
                            {{$cooperation['unit'] == $unit['code'] ? 'selected' : null}}>
                        {{$unit['name']}}
                    </option>
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
               value="{{$cooperation['usu_doc_no']}}" required>
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
               value="{{$cooperation['partner_doc_no']}}" required>
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
        @include('layout.input-upload', ['passing_variable' => 'file_name_ori', 'passing_description' => 'Dokumen', 'passing_error' => ' * File yang diupload harus sudah ditandatangani WR III. Saat addendum, kosongkan apabila file yang digunakan sama dengan MOA'])
    @elseif($upd_mode=='edit' || $upd_mode=='display')
        @include('layout.input-upload', ['passing_variable' => 'file_name_ori', 'passing_description' => 'Dokumen', 'passing_error' => ' * File yang diupload harus sudah ditandatangani WR III. Kosongkan apabila file tidak berubah'])
    @endif
</div>