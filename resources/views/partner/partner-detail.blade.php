@extends('main_layout')

@php
    if(!isset($partner))
    {
        $partner = new \App\Partner();
    }
    $olds = session()->getOldInput();
    foreach ($olds as $key => $old)
    {
        if($key !== '_token')
        {
            $partner[$key] = old($key);
        }
    }
@endphp

@section('content')
    <!-- START @PAGE CONTENT -->
    <section id="page-content">

        <!-- Start page header -->
        <div id="tour-11" class="header-content">
            <h2><i class="fa fa-building"></i>Instansi Partner</h2>
            <div class="breadcrumb-wrapper hidden-xs">
                <span class="label">Direktori Anda:</span>
                <ol class="breadcrumb">
                    <li class="active">Data Referensi > Instansi Partner > {{$upd_mode == 'create' ? 'Tambah' : 'Ubah'}}</li>
                </ol>
            </div>
        </div><!-- /.header-content -->
        <!--/ End page header -->

        <!-- Start body content -->
        <div class="body-content animated fadeIn">
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">{{$upd_mode == 'create' ? 'Tambah' : 'Ubah'}} Instansi Partner</h3>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-sm" data-action="collapse" data-container="body" data-toggle="tooltip"
                                data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                    </div>
                    <div class="clearfix"></div>
                </div><!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{url($action_url)}}" method="post" >
                                <input type="hidden" class="form-control" name="id" value="{{$partner['id']}}" required >

                                <div class="form-group {{$errors->has('name') ? 'has-error' : null}}">
                                    <label for="name" class="control-label">Nama Instansi Partner : </label>
                                    <input type="text" class="form-control" name="name" id='nama_perusahaan' value="{{$partner['name']}}" required >
                                    @if($errors->has('name'))
                                        <label id="bv_required-error" class="error" for="bv_required" style="display: inline-block;">
                                            {{$errors->first('name')}}
                                        </label>
                                    @endif
                                </div>
                                <div class="form-group {{$errors->has('address') ? 'has-error' : null}}">
                                    <label for="address" class="control-label">Alamat : </label>
                                    <textarea name="address" id="alamat" cols="30" rows="5" class="form-control">{{$partner['address']}}</textarea>
                                    @if($errors->has('address'))
                                        <label id="bv_required-error" class="error" for="bv_required" style="display: inline-block;">
                                            {{$errors->first('address')}}
                                        </label>
                                    @endif
                                </div>

                                @if($upd_mode == 'edit')
                                    <input type="hidden" name="_method" value="PUT">
                                @endif
                                {{csrf_field()}}

                                <div class="panel-footer">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button class="btn btn-success rounded" type="submit">Submit</button>
                                                <a href="{{url('partners')}}" class="btn btn-danger rounded">Batal</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div><!-- /.col-md-12 -->
                    </div><!-- /.row -->
                </div><!-- /.panel-body -->
            </div><!-- /.panel -->
        </div><!-- /.body-content -->
        <!--/ End body content -->

        <!-- Start footer content -->
        @include('layout.footer')
        <!--/ End footer content -->

    </section><!-- /#page-content -->
    <!--/ END PAGE CONTENT -->
@endsection