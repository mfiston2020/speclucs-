var room = 1;

function productPricingSection() {

    room++;
    var objTo = document.getElementById('product-pricing')
    var divtest = document.createElement("div");
    divtest.setAttribute("class", "form-group removeclass" + room);
    var rdiv = 'removeclass' + room;
    divtest.innerHTML = `<div class="row col-12">
                            <!--/span-->
                            <div class="col-md-4">
                                <label>Sphere</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">From</span>
                                    </div>
                                    <input type="text" name="sphere_from[]" class="form-control" required>

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">to</span>
                                    </div>
                                    <input type="text" name="sphere_to[]" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Cylinder</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">From</span>
                                    </div>
                                    <input type="text" name="cylinder_from[]" class="form-control" required>

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">to</span>
                                    </div>
                                    <input type="text" name="cylinder_to[]" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label>Addition</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">From</span>
                                    </div>
                                    <input type="text" name="addition_from[]" class="form-control">

                                    <div class="input-group-prepend">
                                        <span class="input-group-text">to</span>
                                    </div>
                                    <input type="text" name="addition_to[]" class="form-control">
                                </div>
                            </div>


                            <div class="col-md-4 mt-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Cost</span>
                                    </div>
                                    <input type="text" name="cost[]" class="form-control" required>

                                </div>
                            </div>

                            <div class="col-md-4 mt-3">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Price</span>
                                    </div>
                                    <input type="text" name="price[]" class="form-control" required>

                                </div>
                            </div>
                            <div class="col-md-2 flex">
                                <button class="btn btn-danger mt-3" type="button" onclick="remove_product_fields('` + room + `');">
                                        <i class="fa fa-minus"></i>
                                </button>
                            </div>

                        </div>`;

    objTo.appendChild(divtest)
}

function remove_product_fields(rid) {
    $('.removeclass' + rid).remove();
}
