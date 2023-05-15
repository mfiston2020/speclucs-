var room = -1;

function education_fields() {

    room++;
    var objTo = document.getElementById('education_fields')
    var divtest = document.createElement("div");
    divtest.setAttribute("class", "form-group removeclass" + room);
    var rdiv = 'removeclass' + room;
    divtest.innerHTML = '<div class="row">\
    <div class="col-md-2">\
    <div class="form-group">\
        <label>SPHERE</label>\
        <input type="text" class="form-control" name="sphere[]" value="" required>\
    </div>\
    </div>\
    <div class="col-md-2">\
        <div class="form-group">\
            <label>CYLINDER</label>\
            <input type="text" class="form-control" name="cylinder[]" value=""required>\
        </div>\
    </div>\
    <div class="col-md-2">\
        <div class="form-group" id="axis">\
            <label>AXIS</label>\
            <input type="text" class="form-control" name="axis[]" value="">\
        </div>\
    </div>\
    <div class="col-md-2">\
        <div class="form-group" id="add">\
            <label>ADD</label>\
            <input type="text" class="form-control" name="add[]" value="">\
        </div>\
    </div>\
    <div class="col-md-4" id="eyes">\
        <div class="form-group row">\
            <label class="control-label col-12">Eye</label>\
            <div class="custom-control custom-radio col-6">\
                <select class="form-control" id="eyes" name="eye[]">\
                    <option>--- Select ---</option>\
                    <option value="left">left</option>\
                    <option value="right">right</option>\
                </select>\
            </div>\
            <div class="col-4">\
            <div class="form-group">\
                <button class="btn btn-danger" type="button" onclick="remove_education_fields(' + room + ');"> <i class="fa fa-minus"></i> </button>\
                    </div>\
            </div>\
        </div>\
    </div>\
        <div class="col-md-4">\
                    <div class="form-group">\
                        <label>Stock</label>\
                        <input type="text" class="form-control" name="lens_stock[]" value="" required>\
                    </div>\
                </div>\
                <div class="col-md-4">\
                    <div class="form-group">\
                        <label>Cost</label>\
                        <input type="text" class="form-control" name="lens_cost[]" value="" required>\
                    </div>\
                </div>\
                <div class="col-md-4">\
                    <div class="form-group">\
                        <label>Price</label>\
                        <input type="text" class="form-control" name="lens_price[]" value="" required>\
                    </div>\
                </div>\</div>\
    <hr>';

    objTo.appendChild(divtest)
}

function remove_education_fields(rid) {
    $('.removeclass' + rid).remove();
}
