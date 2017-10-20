@php
    if(!isset($coop_tree_relations))
        $coop_tree_relations = new \App\Cooperation();
    $_GET['id'] = $_GET['id']+0;
@endphp

<div class="row">
    <div class="col-md-12">
        <div class="panel rounded shadow">
            <div class="panel-heading">
                <div class="pull-left">
                    <h3 class="panel-title">Relasi Kerjasama</h3>
                </div>
                <div class="pull-right">
                    <button class="btn btn-sm" data-action="collapse" data-container="body"
                            data-toggle="tooltip"
                            data-placement="top" data-title="Collapse"><i class="fa fa-angle-up"></i></button>
                </div>
                <div class="clearfix"></div>
            </div><!-- /.panel-heading -->
            <div class="panel-body">
                <div>
                    <ul class="tree">
                        <!-- MOU -->
                        @foreach($coop_tree_relations as $coop_relation)
                            @if($coop_relation['level'] == 1)
                                <li>{{$coop_relation['coop_type']}} : <a href="{{url('cooperations/display?id=' . $coop_relation['id'])}}" {{$coop_relation['id'] == $_GET['id']? 'style=text-decoration: underline;' : null}}>{{\Illuminate\Support\Str::limit($coop_relation['area_of_coop'], 50)}}</a>
                            @endif
                        @endforeach
                        @php($curr_level = 1)
                        <!-- END MOU -->

                        <!-- ADDENDUM MOU -->
                        @php($is_level_2 = false)
                        @foreach($coop_tree_relations as $coop_relation)
                            @if($coop_relation['level'] == 2)
                                @php($is_level_2 = true)
                                <ul>
                                @break
                            @endif
                        @endforeach
                        @php($first = true)
                        @if($is_level_2)
                            @foreach($coop_tree_relations as $coop_relation)
                                @if($coop_relation['level'] == 2)
                                    @if(!$first)
                                        </li>
                                    @endif
                                    <li>{{$coop_relation['coop_type']}} : <a href="{{url('cooperations/display?id=' . $coop_relation['id'])}}" {{$coop_relation['id'] == $_GET['id']? 'style=text-decoration: underline;' : null}} >{{\Illuminate\Support\Str::limit($coop_relation['area_of_coop'], 50)}}</a>
                                    @php($first = false)
                                @endif
                            @endforeach
                        @endif
                        <!-- END ADDENDUM MOU -->

                        <!-- MOA -->
                        @php($is_level_3 = false)
                        @foreach($coop_tree_relations as $coop_relation)
                            @if($coop_relation['level'] == 3)
                                @php($is_level_3 = true)
                                <ul>
                                @break
                            @endif
                        @endforeach
                        @php($first = true)
                        @if($is_level_3)
                            @foreach($coop_tree_relations as $coop_relation)
                                @if($coop_relation['level'] == 3)
                                    @if(!$first)
                                        </li>
                                    @endif
                                    <li>{{$coop_relation['coop_type']}} : <a href="{{url('cooperations/display?id=' . $coop_relation['id'])}}" {{$coop_relation['id'] == $_GET['id'] ? 'style=text-decoration: underline;' : null}}>{{\Illuminate\Support\Str::limit($coop_relation['area_of_coop'], 50)}}</a>
                                    @php($is_level_4 = false)
                                    @foreach($coop_tree_relations as $coop_relation_2)
                                        @if($coop_relation_2['level'] == 4 && $coop_relation_2['parent_id'] == $coop_relation['id'])
                                            @php($is_level_4 = true)
                                            <ul>
                                            @break
                                        @endif
                                    @endforeach
                                    @foreach($coop_tree_relations as $coop_relation_2)
                                        @if($coop_relation_2['level'] == 4 && $coop_relation_2['parent_id'] == $coop_relation['id'])
                                            <li>{{$coop_relation_2['coop_type']}} : <a href="{{url('cooperations/display?id=' . $coop_relation_2['id'])}}" {{$coop_relation_2['id'] == $_GET['id'] ? "style='text-decoration: underline;font-weight:bold;'" : null}}>{{\Illuminate\Support\Str::limit($coop_relation_2['area_of_coop'], 50)}}</a></li>
                                        @endif
                                    @endforeach
                                    @if($is_level_4)
                                        </ul>
                                    @endif
                                    @php($first = false)
                                @endif
                            @endforeach
                        @endif
                        <!-- END MOA -->

                        <!-- ADDENDUM MOA -->
                        {{--@php($is_level_4 = false)--}}
                        {{--@foreach($coop_tree_relations as $coop_relation)--}}
                            {{--@if($coop_relation['level'] == 4)--}}
                                {{--@php($is_level_4 = true)--}}
                                {{--<ul>--}}
                            {{--@endif--}}
                        {{--@endforeach--}}
                        {{--@if($is_level_4)--}}
                            {{--@foreach($coop_tree_relations as $coop_relation)--}}
                                {{--@if($coop_relation['level'] == 4)--}}
                                    {{--<li>{{$coop_relation['coop_type']}} : <a href="{{url('cooperations/display?id=' . $coop_relation['id'])}}">{{$coop_relation['area_of_coop']}}</a></li>--}}
                                {{--@endif--}}
                            {{--@endforeach--}}
                            {{--</ul>--}}
                        {{--@endif--}}
                        <!-- END ADDENDUM MOA -->

                        @if($is_level_3)
                            </li></ul>
                        @endif
                        @if($is_level_2)
                            </li></ul>
                        @endif
                        </li>
                    </ul>

                </div>
            </div><!-- /.panel-body -->
        </div>
    </div><!-- /.col-md-12 -->
</div><!-- /.row -->
