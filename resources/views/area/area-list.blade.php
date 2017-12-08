@extends('main_layout')

@section('content')
    <!-- Modal for delete area -->
    <div id="delete" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Hapus Area Kerjasama</h4>
                </div>
                <div class="modal-body">
                    <p id="nama_area_hapus">Apakah anda yakin ingin menghapus data ini?</p>
                </div>
                <div class="modal-footer">
                    <form action="" method="post">
                        <input type="hidden" name="_method" value="DELETE">
                        {{csrf_field()}}
                        <button type="submit" class="btn btn-danger rounded">OK</button>
                        <button type="button" class="btn btn-default rounded" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Modal for tambah area -->
    <div id="tambah" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Tambah Area Kerjasama</h4>
                </div>
                <div class="modal-body">
                    <form action="{{url($action_url)}}" method="post">
                        {{csrf_field()}}
                        <div class="form-group {{$errors->has('area_coop') ? 'has-error' : null}}">
                            <label for="address" class="control-label">Bidang Kerjasama : </label>
                            <input type="text" name="area_coop" class="form-control" required>
                            @if($errors->has('area_coop'))
                                <label id="bv_required-error" class="error" for="bv_required" style="display: inline-block;">
                                    {{$errors->first('area_coop')}}
                                </label>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-danger rounded">Submit</button>
                        <button type="button" class="btn btn-default rounded" data-dismiss="modal">Cancel</button>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div id="edit" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Ubah Bidang Kerjasama</h4>
                </div>
                <div class="modal-body">
                    <form method='post' action=''>
                        <input type='hidden' name='id' class='form-control' id="id">
                        {{csrf_field()}}
                        <input type='text' class='form-control' name='area_coop' id="area">
                        <div class='modal-footer'>
                            <input type='submit' value='Update' class='btn btn-theme rounded' id='update'>
                            <button class='btn btn-default rounded' data-dismiss='modal'>Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- START @PAGE CONTENT -->
    <section id="page-content">

        <!-- Start page header -->
        <div id="tour-11" class="header-content">
            <h2><i class="fa fa-building"></i>Bidang Kerjasama</h2>
            <div class="breadcrumb-wrapper hidden-xs">
                <span class="label">Direktori Anda:</span>
                <ol class="breadcrumb">
                    <li class="active">Data Referensi > Bidang Kerjasama</li>
                </ol>
            </div>
        </div><!-- /.header-content -->
        <!--/ End page header -->

        <!-- Start body content -->
        <div class="body-content animated fadeIn">
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Daftar Bidang Kerjasama</h3>
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
                            <div class='pull-left'>
                                <a href="#tambah" class="btn btn-theme btn-md rounded"
                                   data-toggle="modal" title="Tambah">
                                    <i class="fa fa-plus"></i> Tambah Bidang
                                </a>
                            </div>
                            <div class="clearfix"></div>
                            <hr>
                        </div>
                        <div class="col-md-12">
                            <!-- Start sample table -->
                            <!-- <div class="table-responsive  rounded mb-20"> -->
                            <table id="area-list" class="table table-striped table-theme table-hover">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>No</th>
                                    <th>Bidang Kerjasama</th>
                                    <th>Aksi</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                            <!-- </div>/.table-responsive -->
                            <!--/ End sample table -->
                        </div>
                    </div><!-- /.row -->
                </div>
            </div>
        </div><!-- /.body-content -->
        <!--/ End body content -->

        <!-- Start footer content -->
    @include('layout.footer')
    <!--/ End footer content -->

    </section><!-- /#page-content -->
    <!--/ END PAGE CONTENT -->
@endsection