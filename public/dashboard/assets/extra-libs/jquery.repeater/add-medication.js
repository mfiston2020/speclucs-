var room = 1;

function add_medication() {

    room++;
    var objTo = document.getElementById('add_meddication')
    var divtest = document.createElement("div");
    divtest.setAttribute("class", "form-group removeclass" + room);
    var rdiv = 'removeclass' + room;
    divtest.innerHTML = '<div class="form-group row">\
                            <div class="col-4">\
                                <div class="input-group mt-3">\
                                    <div class="input-group-prepend">\
                                        <span class="input-group-text">Medication</span>\
                                    </div>\
                                    <input type="text" step="any" class="form-control" name="medication[]" required>\
                                </div>\
                            </div>\
                            <div class="col-4">\
                                <div class="input-group mt-3">\
                                    <div class="input-group-prepend">\
                                        <span class="input-group-text">Strength</span>\
                                    </div>\
                                    <input type="text" step="any" class="form-control" name="medication_strength[]" required>\
                                </div>\
                            </div>\
                            <div class="col-4">\
                                <div class="input-group mt-3">\
                                    <div class="input-group-prepend">\
                                        <span class="input-group-text">Route</span>\
                                    </div>\
                                    <input type="text" step="any" class="form-control" name="medication_route[]" required>\
                                </div>\
                            </div>\
                            <div class="col-3">\
                                <div class="input-group mt-3">\
                                    <div class="input-group-prepend">\
                                        <span class="input-group-text">Dosage</span>\
                                    </div>\
                                    <input type="text" step="any" class="form-control" name="medication_dosage[]" required>\
                                </div>\
                            </div>\
                            <div class="col-3">\
                                <div class="input-group mt-3">\
                                    <div class="input-group-prepend">\
                                        <span class="input-group-text">Frequency</span>\
                                    </div>\
                                    <input type="text" step="any" class="form-control" name="medication_frequency[]" required>\
                                </div>\
                            </div>\
                            <div class="col-3">\
                                <div class="input-group mt-3">\
                                    <div class="input-group-prepend">\
                                        <span class="input-group-text">T.Dosage</span>\
                                    </div>\
                                    <input type="text" step="any" class="form-control" name="medication_total_dosage[]" required>\
                                </div>\
                            </div>\
                            <div class="col-3">\
                                <div class="input-group mt-3">\
                                    <div class="input-group-prepend">\
                                        <span class="input-group-text">Duration</span>\
                                    </div>\
                                    <input type="text" step="any" class="form-control" name="medication_duration[]" required>\
                                </div>\
                            </div>\
                        </div>\
                        <hr>\
                        <div class="form-group m-b-0 text-right">\
                            <button type="button" class="btn btn-danger waves-effect waves-light" onclick="remove_medication(' + room + ');">\
                                <i class="fas fa-minus-circle"></i>\
                            </button>\
                        </div>';

    objTo.appendChild(divtest)
}

function remove_medication(rid) {
    $('.removeclass' + rid).remove();
}
