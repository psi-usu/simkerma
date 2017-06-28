/**
 * Created by Surya on 29/05/2017.
 */
$(document).ready(function () {
    var getUrl = window.location,
        baseUrl = getUrl.protocol + "//" + getUrl.host + "/";

    if ($("#coop-list").length) {
        var coopDatatable = $("#coop-list").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'cooperations/ajax',
            columnDefs: [
                {
                    orderable: false,
                    defaultContent: '<button class="btn btn-theme btn-sm rounded coop-view-btn" data-toggle="tooltip" data-placement="top" title="Lihat"><i class="fa fa-eye"></i></button>',
                    targets: 7
                },
                {
                    className: "dt-center",
                    targets: [1, 4, 5, 6, 7]
                },
                {
                    width: "5%",
                    targets: 1,
                },
                {
                    width: "10%",
                    targets: 6,
                },
                {
                    visible: false,
                    targets: 0,
                }
            ],
        });
    }

    $(document).on("click", ".coop-view-btn", function (e) {
        e.preventDefault();
        var dt_row = $(this).closest("li").data("dt-row");

        if (dt_row >= 0) {
            var position = dt_row;
        } else {
            var target_row = $(this).closest("tr").get(0);
            var position = coopDatatable.fnGetPosition(target_row);
        }
        var id = coopDatatable.fnGetData(position)[0];
        window.open(baseUrl + "cooperations/display?id=" + id, "_self");

        // $.ajax({
        //     url: baseUrl + 'cooperations/ajax/is-having-relation',
        //     dataType: "json",
        //     data: {
        //         id: id
        //     },
        //     success: function (data) {
        //         if (data['iTotalRecords'] == 0) {
        //             window.open(baseUrl + "cooperations/display?id=" + id, "_self");
        //         } else {
        //             // $("#view").modal("show");
        //         }
        //     }
        // });

    });

    if ($("#partner-list").length) {
        var partnerDatatable = $("#partner-list").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'partners/ajax',
            columnDefs: [
                {
                    orderable: false,
                    defaultContent: '<a data-toggle="tooltip" data-placement="top" title="Edit"><button class="btn btn-theme btn-sm rounded edit"><i class="fa fa-pencil" style="color:white;"></i></button></a>' +
                    '<a data-toggle="tooltip" data-placement="top" data-original-title="Delete"><button class="btn btn-danger btn-sm rounded delete" data-toggle="modal" data-target="#delete"><i class="fa fa-times"></i></button></a>',
                    targets: 4
                },
                {
                    className: "dt-center",
                    targets: [1, 4]
                },
                {
                    width: "5%",
                    targets: 1,
                },
                {
                    width: "20%",
                    targets: [2, 4],
                },
                {
                    visible: false,
                    targets: 0,
                },
            ],
        });

        $(document).on("click", "#partner-list a button.delete", function (e) {
            e.preventDefault();
            var dt_row = $(this).closest("li").data("dt-row");

            if (dt_row >= 0) {
                var position = dt_row;
            } else {
                var target_row = $(this).closest("tr").get(0);
                var position = partnerDatatable.fnGetPosition(target_row);
            }
            var partner_id = partnerDatatable.fnGetData(position)[0];

            $("#delete form").attr("action", baseUrl + "partners/delete?id=" + partner_id);

            // window.open(baseUrl + "partners/delete?id=" + partner_id);
        });

        $(document).on("click", "#partner-list a button.edit", function (e) {
            e.preventDefault();
            var dt_row = $(this).closest("li").data("dt-row");

            if (dt_row >= 0) {
                var position = dt_row;
            } else {
                var target_row = $(this).closest("tr").get(0);
                var position = partnerDatatable.fnGetPosition(target_row);
            }
            var partner_id = partnerDatatable.fnGetData(position)[0];

            window.open(baseUrl + "partners/edit?id=" + partner_id, "_self");
        });
    }


    if ($("#unit-list").length) {
        var unitDatatable = $("#unit-list").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'units/ajax',
        });
    }

    if ($("#user-list").length) {
        var userDatatable = $("#user-list").dataTable({
            autoWidth: false,
            responsive: true,
            ajax: baseUrl + 'users/ajax',
            columnDefs: [
                {
                    orderable: false,
                    defaultContent: '<a data-toggle="tooltip" data-placement="top" title="Edit"><button class="btn btn-theme btn-sm rounded edit"><i class="fa fa-pencil" style="color:white;"></i></button></a>' +
                    '<a data-toggle="tooltip" data-placement="top" data-original-title="Delete"><button class="btn btn-danger btn-sm rounded delete" data-toggle="modal" data-target="#delete"><i class="fa fa-times"></i></button></a>',
                    targets: 4
                },
                {
                    className: "dt-center",
                    targets: [0, 3, 4]
                },
                {
                    width: "5%",
                    targets: 0,
                },
            ],
        });

        $(document).on("click", "#user-list a button.delete", function (e) {
            e.preventDefault();
            var dt_row = $(this).closest("li").data("dt-row");

            if (dt_row >= 0) {
                var position = dt_row;
            } else {
                var target_row = $(this).closest("tr").get(0);
                var position = userDatatable.fnGetPosition(target_row);
            }
            var username = userDatatable.fnGetData(position)[1];

            $("#delete form").attr("action", baseUrl + "users/delete?username=" + username);
        });

        $(document).on("click", "#user-list a button.edit", function (e) {
            e.preventDefault();
            var dt_row = $(this).closest("li").data("dt-row");

            if (dt_row >= 0) {
                var position = dt_row;
            } else {
                var target_row = $(this).closest("tr").get(0);
                var position = userDatatable.fnGetPosition(target_row);
            }
            var username = userDatatable.fnGetData(position)[1];

            window.open(baseUrl + "users/edit?id=" + username, "_self");
        });
    }

    if ($("input[name=coop_type]:checked").val() == "MOU") {
        toggleCoopDetail(true, false, false);
    }
    else if ($("input[name=coop_type]:checked").val() == "MOA") {
        toggleCoopDetail(false, true, false);
    }
    else {
        toggleCoopDetail(false, false, true);
    }

    if ($("#fileinput-mou-doc").length) {
        var cooperation_id = $("input[name=id]").attr("value");
        if (cooperation_id > 0) {
            var initialPreview = "<a href='" + baseUrl + "cooperations/download-document?id=" + cooperation_id + "' class='file-preview-other'>download</a>";
            $("#fileinput-mou-doc").fileinput({
                "showCaption": true,
                "showRemove": false,
                "showUpload": true,
                "initialPreview": [
                    initialPreview
                ],
                "browseLabel": "Pilih File...",
                "language": "en"
            });
        } else {
            // $("#fileinput-mou-doc").fileinput({
            //     "showCaption": true,
            //     "showRemove": false,
            //     "showUpload": true,
            //     "browseLabel": "Pilih File...",
            //     "language": "en"
            // });
        }
    }

    var button = '<button class="btn btn-theme rounded" type="submit" id="submit">Submit</button>'
        + '  <button class="btn btn-danger rounded" type="reset">Reset</button>';
    $('#submit').html(button);

    $("input:radio[name=coop_type]").change(function () {
        if ($('input[name=coop_type]:checked').val() == 'MOU') {
            toggleCoopDetail(true, false, false);
            refreshMOUData();
        } else if ($('input[name=coop_type]:checked').val() == 'MOA') {
            toggleCoopDetail(false, true, false);
            refreshMOAData();
        } else if ($('input[name=coop_type]:checked').val() == 'ADDENDUM') {
            toggleCoopDetail(false, false, true);
            $("#choose-addendum-type").find("select").val("").change();
            $("#choose-addendum-type").find("select").trigger("chosen: updated");
        }
    });

    $("#choose-addendum-type select[name=addendum_type]").change(function () {
        if ($(this).val() == 'MOU') {
            $("#choose-mou").fadeIn("slow").find("select").val("").change().attr("disabled", false);
            $("#choose-mou").find("select").trigger("chosen: udpated");
            $("#choose-moa").hide().find("select").attr("disabled", true);
            toggleCoopDetail(false, false, true);
        } else if ($(this).val() == 'MOA') {
            $("#choose-moa").fadeIn("slow").find("select").val("").change().attr("disabled", false);
            $("#choose-moa").find("select").trigger("chosen: udpated");
            $("#choose-mou").hide().find("select").attr("disabled", true);
            toggleCoopDetail(false, false, true);
        }
    });

    if ($("#choose-addendum-type select[name=addendum_type]").val() != null) {
        if ($("#choose-addendum-type select[name=addendum_type]").val() == 'MOU') {
            $("#choose-mou").fadeIn("slow");
            toggleCoopDetail(false, false, true);
        } else if ($("#choose-addendum-type select[name=addendum_type]").val() == 'MOA') {
            $("#choose-moa").fadeIn("slow");
            toggleCoopDetail(false, false, true);
        }
    }
    if ($("#choose-mou select[name=cooperation_id]:visible").val() > 0) {
        toggleCoopDetail(true, false, true);
    }
    if ($("#choose-moa select[name=cooperation_id]:visible").val() > 0) {
        toggleCoopDetail(false, true, true);
    }

    $("#choose-mou select[name=cooperation_id]").change(function () {
        toggleCoopDetail(true, false, true);
        refreshMOUData();
        var id = $(this).val();
        $.ajax({
            url: baseUrl + 'cooperations/ajax/cooperation-detail',
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                $("select[name=partner_id]").val(data['partner_id']).change();
                $("select[name=partner_id]").trigger("chosen:updated");
                $("select[name=form_of_coop]").val(data['form_of_coop']).change();
                $("select[name=form_of_coop]").trigger("chosen:updated");
                $("textarea[name=area_of_coop]").val(data['area_of_coop']);
                $("input[name=sign_date]").val(data['sign_date']);
                $("input[name=end_date]").val(data['end_date']);
                $("input[name=usu_doc_no]").val(data['usu_doc_no']);
                $("input[name=partner_doc_no]").val(data['partner_doc_no']);
            }
        });
    });

    $("#choose-moa select[name=cooperation_id]").change(function () {
        toggleCoopDetail(false, true, true);
        refreshMOAData();
        var id = $(this).val();
        $.ajax({
            url: baseUrl + 'cooperations/ajax/cooperation-detail',
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                $("#MoA select[name=cooperation_id]").val(data['cooperation_id']).change();
                $("#MoA select[name=cooperation_id]").trigger("chosen: updated");
                $("#MoA select[name=cooperation_id]").attr("disabled", true);

                $("#MoA textarea[name=area_of_coop]").val(data['area_of_coop']);
                $("#MoA textarea[name=implementation]").val(data['implementation']);
                $("#MoA input[name=sign_date]").val(data['sign_date']);
                $("#MoA input[name=end_date]").val(data['end_date']);
                $("#MoA input[name=usu_doc_no]").val(data['usu_doc_no']);
                $("#MoA input[name=partner_doc_no]").val(data['partner_doc_no']);
                $("#MoA select[name=unit]").val(data['unit']).change();
                $("#MoA select[name=unit]").trigger("chosen: updated");
                $("#MoA input[name=contract_amount]").val(data['contract_amount']);

                $.each(data.coop_items, function (k, v) {
                    if (k > 0) {
                        var $clone = $("#moa-table").find('tr.hide').clone(true).removeClass('hide table-line');
                        $clone.find("input").attr("disabled", false);
                        $("#moa-table").find('table').append($clone);
                        $(":input").inputmask()
                    }
                });

                $.each($("#moa-table input[name^=item_name]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_name']);
                });
                $.each($("#moa-table input[name^=item_quantity]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_quantity']);
                });
                $.each($("#moa-table input[name^=item_uom]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_uom']);
                });
                $.each($("#moa-table input[name^=item_total_amount]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_total_amount']);
                });
                $.each($("#moa-table input[name^=item_annotation]:visible"), function (k, v) {
                    $(this).val(data.coop_items[k]['item_annotation']);
                });
            }
        });
    });

    function toggleCoopDetail(mou, moa, addendum) {
        if (mou) {
            $('#MoU').fadeIn('slow').find('input, textarea, select').attr('disabled', false);
        } else {
            $('#MoU').hide().find('input, textarea, select').attr('disabled', true);
        }
        if (moa) {
            $('#MoA').fadeIn('slow').find('input, textarea, select').attr('disabled', false);
            $('#MoA').find('input[name^=mou_detail_], textarea[name^=mou_detail_], select[name^=mou_detail_], input[name=contract_amount], input[name^=item_]:hidden').attr('disabled', true);
        } else {
            $('#MoA').hide().find('input, textarea, select').attr('disabled', true);
        }
        if (addendum) {
            $('#Addendum').fadeIn('slow').find('input, textarea, select').attr('disabled', false);
            $('#choose-addendum-type').fadeIn('slow').find('input, textarea, select').attr('disabled', false);
        } else {
            $('#Addendum').hide().find('input, textarea, select').attr('disabled', true);
            $('#choose-addendum-type').hide().find('select').attr('disabled', false);
            $('#choose-mou').hide().find('select').attr('disabled', false);
            $('#choose-moa').hide().find('select').attr('disabled', false);
        }
    }

    function refreshMOUData() {
        $("#MoU select[name=partner_id]").val("").change();
        $("#MoU select[name=partner_id]").trigger("chosen:updated");
        $("#MoU select[name=form_of_coop]").val("").change();
        $("#MoU select[name=form_of_coop]").trigger("chosen:updated");
        $("#MoU textarea[name=area_of_coop]").val("");
        $("#MoU input[name=sign_date]").val("");
        $("#MoU input[name=end_date]").val("");
        $("#MoU input[name=usu_doc_no]").val("");
        $("#MoU input[name=partner_doc_no]").val("");
    }

    function refreshMOAData() {
        $("#MoA select[name=cooperation_id]").val("").change();
        $("#MoA select[name=cooperation_id]").trigger("chosen: updated");

        $("#MoA input[name^=mou_detail_]").val("");

        $("#MoA textarea[name=area_of_coop]").val("");
        $("#MoA textarea[name=implementation]").val("");
        $("#MoA input[name=sign_date]").val("");
        $("#MoA input[name=end_date]").val("");
        $("#MoA input[name=usu_doc_no]").val("");
        $("#MoA input[name=partner_doc_no]").val("");
        $("#MoA select[name=unit]").val("").change();
        $("#MoA select[name=unit]").trigger("chosen: updated");
        $("#MoA input[name=contract_amount]").val(0);

        $("#moa-table").find("tbody tr:visible").detach();
        var $clone = $("#moa-table").find('tr.hide').clone(true).removeClass('hide table-line');
        $clone.find("input").attr("disabled", false);
        $("#moa-table").find('table').append($clone);
        $(":input").inputmask();
    }

    if ($("input[name=upd_mode]").val() == 'display') {
        $("#tambah_kerma input:visible, #tambah_kerma select:visible, #tambah_kerma textarea:visible").attr("disabled", true);
    }

    // $('#pilihan').on('change', function () {
    //     if (document.getElementById("pilihan").value == "mou") {
    //         $('#mou_addendum').fadeIn('slow');
    //         $('#moa_addendum').hide();
    //     } else {
    //         $('#moa_addendum').fadeIn('slow');
    //         $('#mou_addendum').hide();php a
    //     }
    // });

    if ($(".select2").length) {
        $(".select2").select2();
    }

    $("#data").DataTable();
    $('#back-top').on('click', function (e) {
        e.preventDefault();
        $('html,body').animate({
            scrollTop: 0
        }, 700);
    });

    var i = 0;
    while (i < 5) {
        var element = $("#datepicker");
        if (i > 0) {
            element = $("#datepicker" + i);
        }
        if (element.length) {
            element.datepicker({
                changeMonth: true,
                changeYear: true
            });
        }
        i++;
    }

    if ($("#MoA select[name=cooperation_id]").val() > 0) {
        var id = $("#MoA select[name=cooperation_id]").val();
        if (id > 0) {
            getCoopDetail(id)
        }
    }

    $("#MoA select[name=cooperation_id]").change(function () {
        var id = $("#MoA select[name=cooperation_id]").val();
        if (id > 0) {
            getCoopDetail(id)
        }
    });

    function getCoopDetail(id) {
        $.ajax({
            url: baseUrl + 'cooperations/ajax/cooperation-detail',
            dataType: "json",
            data: {
                id: id
            },
            success: function (data) {
                $("input[name=mou_detail_partner_id]").val(data['partner_name']);
                $("input[name=mou_detail_area_of_coop]").val(data['area_of_coop']);
                $("input[name=mou_detail_sign_date]").val(data['sign_date']);
                $("input[name=mou_detail_end_date]").val(data['end_date']);
                $("input[name=mou_detail_usu_doc_no]").val(data['usu_doc_no']);
                $("input[name=mou_detail_partner_doc_no]").val(data['partner_doc_no']);
            }
        });
    }

    if ($("#tambah_kerma").length) {
        $("#tambah_kerma").validate({
            rules: {
                "item_name[]": {
                    required: true
                },
                "item_quantity[]": {
                    required: true
                },
                "item_uom[]": {
                    required: true
                },
                "item_total_amount[]": {
                    required: true
                },
                "item_annotation[]": {
                    required: true
                },
            },
            highlight: function (element) {
                $(element).parents('.form-group').addClass('has-error has-feedback');
            },
            unhighlight: function (element) {
                $(element).parents('.form-group').removeClass('has-error');
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    }

    $('.table-add').click(function (e) {
        e.preventDefault();
        if ($("#moa-table").length) {
            var v_table = $("#moa-table");
        } else if ($("#user-auth-table").length) {
            var v_table = $("#user-auth-table");
        }
        var $clone = v_table.find('tr.hide').clone(true).removeClass('hide table-line');
        if ($("#moa-table").length) {
            $clone.find("input").attr("disabled", false);
        }
        else if ($("#user-auth-table").length) {
            $clone.find("select").attr("disabled", false);
            $clone.find("select").addClass("select2");
        }
        v_table.find('table').append($clone);
        if ($("#moa-table").length) {
            $(":input").inputmask()
            $("#tambah_kerma").validate({
                rules: {
                    "item_name[]": {
                        required: true
                    },
                    "item_quantity[]": {
                        required: true
                    },
                    "item_uom[]": {
                        required: true
                    },
                    "item_total_amount[]": {
                        required: true
                    },
                    "item_annotation[]": {
                        required: true
                    },
                },
                highlight: function (element) {
                    $(element).parents('.form-group').addClass('has-error has-feedback');
                },
                unhighlight: function (element) {
                    $(element).parents('.form-group').removeClass('has-error');
                },
                submitHandler: function (form) {
                    form.submit();
                }
            });
        } else if ($("#user-auth-table").length) {
            $(".select2").select2();
        }
    });

    $('.table-remove').click(function (e) {
        e.preventDefault();
        $(this).parents('tr').detach();
        sumTotalAmount();
    });

    if ($("#moa-table").length) {
        $(":input").inputmask()
    }

    $(document).on("change", "#moa-table input[name^=item_total_amount]", function () {
        sumTotalAmount();
    })

    function sumTotalAmount() {
        var contractElement = $("input[name=contract_amount]");
        var sum = 0;
        $("input[name^=item_total_amount]:visible").each(function () {
            var value = $(this).val();
            if (value != "") {
                value = value.replace(/\,/g, "");
                sum += parseInt(value);
            }
        });
        contractElement.val(sum)
    }

    $("#MoA select[name=unit]").change(function () {
        if ($(this).val() != null) {
            $.ajax({
                url: baseUrl + 'cooperations/ajax/get-study-program',
                data: {
                    faculty: $(this).val()
                },
                dataType: "json",
                success: function (data) {
                    var subUnitElement = $("#MoA select[name=sub_unit]");
                    subUnitElement.find("option").remove();
                    subUnitElement.append("<option value='' disabled selected>Pilih Sub Unit</option>")
                    subUnitElement.select2('data', null);
                    subUnitElement.select2({placeholder: "-- Pilih Sub Unit --"});
                    $.each(data, function (k, v) {
                        subUnitElement.append("<option value='" + v["name"] + "'>" + v["name"] + "</option>")
                    });
                    subUnitElement.trigger("chosen: updated");
                }
            });
        }
    });

    if ($(".search-employee").length) {
        var autocomp_opt = {
            source: function (request, response) {
                $.ajax({
                    url: baseUrl + '/users/ajax/search',
                    dataType: "json",
                    data: {
                        query: request.term,
                        limit: 10
                    },
                    success: function (data) {
                        var transformed = $.map(data, function (el) {
                            return {
                                label: el.label,
                                id: el.username,
                                full_name: el.full_name
                            };
                        });
                        response(transformed);
                    }
                });
            },
            select: function (event, ui) {
                $("input[name=username]").val(ui.item.id);
                $("input[name=full_name]").val(ui.item.full_name);
                $('.search-employee').trigger('change');
            }
        };
        $(".search-employee").autocomplete(autocomp_opt);
    }
});