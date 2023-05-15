var room = 1;

function education_fields() {

    room++;
    var objTo = document.getElementById('education_fields')
    var divtest = document.createElement("div");
    divtest.setAttribute("class", "form-group removeclass" + room);
    var rdiv = 'removeclass' + room;
    divtest.innerHTML = '<div class="row col-12">\
                                        <!--/span-->\
                                        <div class="col-md-2">\
                                            <div class="form-group">\
                                                <label>SPHERE</label>\
                                                <input type="text" class="form-control" name="sphere[]" placeholder="Sphere" required>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-2">\
                                            <div class="form-group">\
                                                <label>CYLINDER</label>\
                                                <input type="text" class="form-control" name="cylinder[]" placeholder="Cylinder" required>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-2">\
                                            <div class="form-group">\
                                                <label>AXIS</label>\
                                                <input type="text" class="form-control" name="axis[]" placeholder="Axis">\
                                            </div>\
                                        </div>\
                                        <div class="col-md-2">\
                                            <div class="form-group">\
                                                <label>Add</label>\
                                                <input type="text" class="form-control" name="add[]" placeholder="Add">\
                                            </div>\
                                        </div>\
                                        <div class="col-md-2">\
                                            <div class="form-group">\
                                                <label>Eye</label>\
                                                <select class="form-control custom-select" name="eye[]" required>\
                                                    <option value="">-- Select Eye --</option>\
                                                    <option value="any">any</option>\
                                                    <option value="right">Right</option>\
                                                    <option value="left">Left</option>\
                                                </select>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-2 flex">\
                                                <button class="btn btn-danger mt-4" type="button" onclick="remove_education_fields(' + room + ');">\
                                                    <i class="fa fa-minus"></i>\
                                                </button>\
                                        </div>\
                                        <!--/span-->\
                                    </div>\
                                    <div class="row col-12 mt-3">\
                                        <!--/span-->\
                                        <div class="col-md-3">\
                                            <div class="form-group">\
                                                <label>Stock</label>\
                                                <input type="text" class="form-control" name="lens_stock[]" placeholder="Stock" required>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-3">\
                                            <div class="form-group">\
                                                <label>Cost</label>\
                                                <input type="text" class="form-control" name="lens_cost[]" placeholder="Cost" required>\
                                            </div>\
                                        </div>\
                                        <div class="col-md-3">\
                                            <div class="form-group">\
                                                <label>Price</label>\
                                                <input type="text" class="form-control" name="lens_price[]" placeholder="Price">\
                                            </div>\
                                        </div>\
                                        <div class="col-md-3">\
                                            <div class="form-group">\
                                                <label>Fitting Cost</label>\
                                                <input type="text" class="form-control" name="fitting_cost[]" placeholder="Fitting Cost">\
                                            </div>\
                                        </div><!--/span--></div><hr>';

    objTo.appendChild(divtest)
}

function remove_education_fields(rid) {
    $('.removeclass' + rid).remove();
}
