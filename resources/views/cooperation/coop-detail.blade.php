@extends('main_layout')

@php
    if(!isset($cooperation))
        $cooperation = new \App\Cooperation();
    if($upd_mode == 'create'){
        if($isSuper){
            $cooperation['coop_type'] = 'MOU';
        }else{
            $cooperation['coop_type'] = 'MOA';
        }
    }

    $olds = session()->getOldInput();

    $contract_amount = 0;
    $ctr = 0;
    if(old('item_name.0'))
        $coop_items = new \Illuminate\Database\Eloquent\Collection();
    while(old('item_name.' . $ctr))
    {
        $coop_item['item_name'] = old('item_name.' . $ctr);
        $coop_item['item_quantity'] = old('item_quantity.' . $ctr);
        $coop_item['item_uom'] = old('item_uom.' . $ctr);
        $coop_item['item_total_amount'] = old('item_total_amount.' . $ctr);
        $contract_amount+=str_replace(',', '', old('item_total_amount.' . $ctr));
        $coop_item['item_annotation'] = old('item_annotation.' . $ctr);
        $coop_items->add($coop_item);
        $ctr++;
    }

    foreach ($olds as $key => $old)
    {
        if($key !== '_token')
        {
            $cooperation[$key] = old($key);
            $cooperation['contract_amount'] = $contract_amount;
        }
    }

    if(!isset($coop_items))
        $coop_items = new \Illuminate\Database\Eloquent\Collection();

    if(!isset($disabled))
        $disabled = "";
    if(!isset($is_relation))
        $is_relation = "";
    if(!isset($mou_coops))
        $mou_coops = new \App\Cooperation();
    if(!isset($approve))
        $approve = "";
    if(!isset($edit))
        $edit = "";
    if(!isset($rj_note))
        $rj_note = "";
    if(!isset($moa_coops))
        $moa_coops = new \App\Cooperation();
    if(!isset($prev_coop))
        $prev_coop = new \App\Cooperation();
@endphp

@section('content')
    <!-- START @PAGE CONTENT -->
    <section id="page-content">
        <!-- Start page header -->
        <div id="tour-11" class="header-content">
            <h2><i class="fa fa-handshake-o"></i>{{$page_title}}</h2>
            <div class="breadcrumb-wrapper hidden-xs">
                <span class="label">Direktori Anda:</span>
                <ol class="breadcrumb">
                    <li class="active">Kerjasama > Tambah</li>
                </ol>
            </div>
        </div><!-- /.header-content -->
        <!--/ End page header -->

        <!-- Start body content -->
        <div class="body-content animated fadeIn">

            @if($upd_mode != "create")
                @include("cooperation.coop-relation-panel")
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="panel rounded shadow">
                        <div class="panel-heading">
                            <div class="pull-left">
                                <h3 class="panel-title">{{$page_title}}</h3>
                            </div>
                            <div class="pull-right">
                                <button class="btn btn-sm" data-action="collapse" data-container="body"
                                        data-toggle="tooltip"
                                        data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i>
                                </button>
                            </div>
                            <div class="clearfix"></div>
                        </div><!-- /.panel-heading -->
                        <div class="panel-body">
                            @if($is_relation != null)
                                @include("cooperation.coop-relation-danger",['danger' => 'Kerjasama tidak dapat diubah, karena telah terdapat relasi pada kerjasama ini'])
                            @endif
                            @if(!empty($rj_note))
                                @include("cooperation.coop-relation-danger",['danger' => $rj_note->note])
                            @endif
                            @if($upd_mode == 'display' && $is_relation==null)
                                @if($edit)
                                    <div class="form-group">
                                        <a href="{{url('cooperations/edit?id=' . $cooperation->id)}}"
                                           class="btn btn-success rounded">Ubah</a>
                                        <a href="{{url('/')}}" class="btn btn-danger rounded">Batal</a>
                                    </div>
                                @endif
                            @endif
                            <form id='tambah_kerma'  method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                @if($upd_mode != 'create')
                                    <input name="id" type="hidden" value="{{$cooperation['id']}}">
                                @endif
                                <input name="upd_mode" type="hidden" value="{{$upd_mode}}" disabled>
                                <input name="approve" type="hidden" value="{{$approve}}">
                                <div class="form-group">
                                    <label for="name" class="control-label col-xs-12">Jenis Kerjasama</label>
                                    @if($upd_mode == 'edit')
                                        <input type="hidden"  name="coop_type" value="{{$cooperation['coop_type']}}">
                                    @endif
                                    <div class='rdio radio-inline rdio-theme rounded'>
                                        <input type='radio' class='radio-inline' id='radio-type-rounded1'
                                               required value='MOU' name="coop_type"
                                                {{!$isSuper ? 'disabled' : null}}
                                                {{$cooperation['coop_type'] == 'MOU' ? 'checked' : null}}
                                                {{$upd_mode != 'create' ? 'disabled' : null}}
                                        >
                                        <label class='' for='radio-type-rounded1'>MoU / Nota Kesepahaman</label>
                                    </div>
                                    <div class='rdio radio-inline rdio-theme rounded'>
                                        <input type='radio' class='radio-inline' id='radio-type-rounded2'
                                               required value='MOA' name="coop_type"
                                                {{$cooperation['coop_type'] == 'MOA' ? 'checked' : null}}
                                                {{$upd_mode != 'create' ? 'disabled' : null}}
                                        >
                                        <label class='' for='radio-type-rounded2'>MoA / Perjanjian Kerjasama</label>
                                    </div>
                                    <div class='rdio radio-inline rdio-theme rounded'>
                                        <input type='radio' class='radio-inline' id='radio-type-rounded3'
                                               required value='SPK' name="coop_type"
                                                {{$cooperation['coop_type'] == 'SPK' ? 'checked' : null}}
                                                {{$upd_mode != 'create' ? 'disabled' : null}}
                                        >
                                        <label class='' for='radio-type-rounded3'>SPK / Surat Perintah Kerjasama</label>
                                    </div>
                                    <div class='rdio radio-inline rdio-theme rounded'>
                                        <input type='radio' class='radio-inline' id='radio-type-rounded4'
                                               required value='ADDENDUM' name="coop_type"
                                                {{$cooperation['coop_type'] == 'ADDENDUM' ? 'checked' : null}}
                                                {{$upd_mode != 'create' ? 'disabled' : null}}
                                        >
                                        <label class='' for='radio-type-rounded4'>Addendum</label>
                                    </div>
                                </div>

                                <div id="choose-addendum-type" class="form-group" style="display: none;">
                                    <label for="name" class="control-label">Pilih Jenis Addendum</label>
                                    <select class="form-control mb-15" name='addendum_type' id="pilihan" required>
                                        <option value="" disabled selected>-- Pilih --</option>
                                        {{--@if($isSuper)--}}
                                            {{--<option value="MOU" {{$prev_coop['coop_type'] == "MOU" ? "selected" : null}}>MoU / Nota Kesepahaman</option>--}}
                                        {{--@endif--}}
                                        <option value="MOA" {{$prev_coop['coop_type'] == "MOA" ? "selected" : null}}>MoA / Perjanjian Kerja Sama</option>
                                    </select>
                                </div>

                                <div id="choose-mou" class="form-group" style="display: none;">
                                    <label for="name" class="control-label">Pilih MoU / Nota Kesepahaman</label>
                                    <select class="form-control mb-15 select2" name='cooperation_id' id="pilihan" required>
                                        <option value="" disabled selected>-- Pilih --</option>
                                        @foreach($mou_coops as $mou_coop)
                                            <option value="{{$mou_coop['id']}}" {{$cooperation['cooperation_id'] == $mou_coop['id'] ? "selected" : null}}>{{$mou_coop['area_of_coop']}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="choose-moa" class="form-group" style="display: none;">
                                    <label for="name" class="control-label">Pilih MoA / Perjanjian Kerja Sama</label>
                                    <select class="form-control mb-15 select2" name='cooperation_id' id="pilihan" {{!empty($moa_coops) ? "disabled" : null}} required>
                                        <option value="" disabled selected>-- Pilih --</option>
                                        @foreach($moa_coops as $moa_coop)
                                            <option value="{{$moa_coop['id']}}" {{$cooperation['cooperation_id'] == $moa_coop['id'] ? "selected" : null}}>{{$moa_coop['area_of_coop']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                @include('cooperation.mou-detail')

                                @include('cooperation.moa-detail')

                                @include('cooperation.spk-detail')

                                {{--@include('cooperation.addendum-detail')--}}

                                @if($upd_mode == 'edit')
                                    <input type="hidden" name="_method" value="PUT">
                                @endif

                                @if(empty($approve))
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    @if($disabled == null)
                                                        @if($upd_mode == 'create')
                                                            <button id="coop-temp" class="btn btn-theme rounded btn-slideright"
                                                                    type="button">Simpan Sementara
                                                            </button>
                                                            <button id="coop-submit" class="btn btn-success btn-slideright rounded"
                                                                    type="button">Submit
                                                            </button>
                                                        @endif
                                                        @if($upd_mode == 'edit')
                                                            <button id="coop-Utemp" class="btn btn-theme rounded btn-slideright"
                                                                    type="button">Simpan Sementara
                                                            </button>
                                                            <button id="coop-update" class="btn btn-success btn-slideright rounded"
                                                                    type="button">Update
                                                            </button>
                                                        @endif
                                                    @endif
                                                    <a href="{{url('/')}}" class="btn btn-danger rounded btn-slideright">Batal</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div><!-- /.panel -->
                    </div><!-- /.body-content -->

                    @if(!empty($approve))
                        @include('cooperation.coop-approve-detail')
                    @endif
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div>
        <!--/ End body content -->

        <!-- Start footer content -->
        @include('layout.footer')
        <!--/ End footer content -->

    </section><!-- /#page-content -->
    <!--/ END PAGE CONTENT -->
@endsection