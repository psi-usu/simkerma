@extends('main_layout')

@section('content')
    <!-- START @PAGE CONTENT -->
    <section id="page-content">

        <!-- Start page header -->
        <div id="tour-11" class="header-content">
            <h2><i class="fa fa-bar-chart-o"></i>Laporan</h2>
            <div class="breadcrumb-wrapper hidden-xs">
                <span class="label">Direktori Anda:</span>
                <ol class="breadcrumb">
                    <li class="active">Kerjasama > Laporan</li>
                </ol>
            </div>
        </div><!-- /.header-content -->
        <!--/ End page header -->

        <!-- Start body content -->
        <div class="body-content animated fadeIn">
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
                            {{--id='report_coop'--}}
                            <form action="report" method="post" enctype="multipart/form-data">
                                {{csrf_field()}}
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-3 text-danger text-center"><small><i>Tanggal Tanda Tangan</i></small></div>
                                        <div class="col-sm-3 text-danger text-center"><small><i>Tanggal Berakhir Kerjasama</i></small></div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="select2 multiple mb-15" name='coop_type' id="coop_type" data-placeholder="Pilih Jenis Kerjasama" required>
                                            <option value="all">Semua Kerjasama</option>
                                            <option value="MOU">MoU / Nota Kesepahaman</option>
                                            <option value="MOA">MoA / Perjanjian Kerja Sama</option>
                                            <option value="ADDENDUM">ADDENDUM</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="date-range-picker form-control" required="" name='sign_date' id='sign_date' placeholder="Tanggal Tandatangan">
                                    </div>
                                    <div class="col-sm-3">
                                        <input type="text" class="date-range-picker form-control" required="" name='end_date' id='end_date' />
                                    </div>
                                    <div class="col-sm-3">
                                        <select class="select2 multiple mb-15" name='partner' id="partner" data-placeholder="Pilih Instansi/Unit" required>
                                            <option value="all">Semua Instansi / Unit</option>
                                            @foreach($partners as $partner)
                                                <option value="{{$partner->id}}">{{$partner->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <br/>
                                <div class="form-footer">
                                    <button name="filter-report" type="submit" id="report-btn" class="btn btn-success btn-slideright submit">Filter</button>
                                    <div class="clearfix"></div>
                                </div>
                            </form>
                        </div><!-- /.panel -->
                    </div><!-- /.body-content -->
                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
            <div class="panel rounded shadow">
                <div class="panel-heading">
                    <div class="pull-left">
                        <h3 class="panel-title">Laporan</h3>
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
                    <div class="form-group">
                        <p class="text-center loading" style="font-size: 18pt; display: none;">Loading Process . . . .</p>
                        <div id="result" style="display: none;">
                            <a href="" style="display: none;" id="btn-download"><button name="filter-report" type="button" class="btn btn-success btn-slidedown submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i>
                                Export Excel</button></a><br/><br/>
                            <div class="clearfix"></div>
                            <table id="table-report" class="table table-striped table-theme">

                            </table>
                        </div>
                    </div>
                </div><!-- /.panel -->
        </div>
        <!--/ End body content -->

        <!-- Start footer content -->
    @include('layout.footer')
    <!--/ End footer content -->

    </section><!-- /#page-content -->
    <!--/ END PAGE CONTENT -->
@endsection