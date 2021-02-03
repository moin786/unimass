{{-- Customer Basic --}}
<div class="box box-success">
    <div class="box-header with-border ">
        <h3 class="box-title">Customer Basic</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <label for="lead_id">Lead ID :</label>
                <h5>USERGROPCODE+YYMM+99999</h5>
            </div>

            <div class="col-md-4">
                <label for="cus_entry_date">Date :</label>
                <h5><?php echo date('d-m-Y'); ?></h5>
            </div>

            <div class="col-md-4">
                <label for="">Customer Name :</label>
                <h5>First Name</h5>
                <h5>Last Name</h5>
            </div>

            <div class="col-md-4">
                <label for="">Phone Number :</label>
                <h5>Phone Number-1</h5>
                <label for="">Phone Number :</label>
                <h5>Phone Number-1</h5>
            </div>

            <div class="col-md-4">
                <label for="cus_email">Customer Email :</label>
                <h5>customeremail@mail.com</h5>
            </div>

            <div class="col-md-4">
                <label>Occupation :</label>
                <h5>Customer Occupation</h5>
            </div>

            <div class="col-md-4">
                <label for="cus_occ_org">Organization :</label>
                <h5>Customer Organization</h5>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>

{{-- Project Detail --}}
<div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Project Detail</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <label>Category :</label>
                <h5>This is Setected project Category</h5>
            </div>
            <div class="col-md-4">
                <label>Area :</label>
                <h5>This is Selected Aria</h5>
            </div>

            <div class="col-md-4">
                <label>Project Name :</label>
                <h5>This is Selected Project List</h5>
            </div>

            <div class="col-md-4">
                <label>Size :</label>
                <h5>This is Selected Size</h5>
            </div>
        </div>
    </div>
</div>

{{-- Source Detail (Auto) --}}
<div class="box" style="border-color:#ff851b;">
    <div class="box-header with-border">
        <h3 class="box-title">Source Detail (Auto)</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="src_title">Source Title :</label>
                    <input type="email" class="form-control" id="src_title" name="src_title" value="" title="Source Title" readonly="readonly" placeholder="Source Title"/>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="src_auto_name">Source Name :</label>
                    <input type="email" class="form-control" id="src_auto_name" name="src_auto_name" value="" title="Source Name" readonly="readonly" placeholder="Source Name"/>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Source Detail --}}
<div class="box" style="border-color:#39cccc;">
    <div class="box-header with-border">
        <h3 class="box-title">Source Detail</h3>
    </div>
    <div class="box-body">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-3">
                    <div class="box" style="border: 0px;">
                        <div class="box-header">
                            <h3 class="box-title">SAC</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="src_name">Name :</label>
                                    <h5>This is Source Name</h5>
                                </div>
                                <div class="col-md-12">
                                    <label for="src_note">Note :</label>
                                    <h5>Note</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="box" style="border: 0px;">
                        <div class="box-header">
                            <h3 class="box-title">Digital Marketing</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group" style="margin: 0;">
                                <label style="cursor:pointer;">
                                  <div class="icheckbox_minimal-blue checked" aria-checked="true" aria-disabled="false" style="position: relative;"><input type="checkbox" class="minimal"  style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                                  <span style="font-size:18px; margin-top:-5px;"><i class="fa fa-facebook"></i>
                                  Facebook</span>
                              </label>
                          </div>

                          <div class="form-group" style="margin: 0;">
                            <label style="cursor:pointer;">
                              <div class="icheckbox_minimal-blue checked" aria-checked="true" aria-disabled="false" style="position: relative;"><input type="checkbox" class="minimal"  style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                              <span style="font-size:18px; margin-top:-5px;"><i class="fa fa-youtube-play"></i>
                              Youtube</span>
                          </label>
                      </div>

                  </div>
              </div>
          </div>

          <div class="col-md-6">
            <div class="box" style="border: 0px;">
                <div class="box-header">
                    <h3 class="box-title">Internal Reference</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="sale_agent">Emp ID :</label>
                            <h5>Emp-12254</h5>
                        </div>

                        <div class="col-md-6">
                            <label for="emp_name">Name :</label>
                            <h5>Emp_Name</h5>
                        </div>

                        <div class="col-md-6">
                            <label for="emp_position">Position :</label>
                            <h5>Emp-Position</h5>
                        </div>

                        <div class="col-md-6">
                            <label for="emp_contact">Contact Number :</label>
                            <h5>Emp-contact Number</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>

{{-- hotline --}}
<div class="box" style="border-color: #f00">
    <div class="box-header with-border">
        <h3 class="box-title">Hotline</h3>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label style="cursor:pointer;">
                      <div class="icheckbox_minimal-blue checked" aria-checked="true" aria-disabled="false" style="position: relative;"><input type="checkbox" class="minimal"  style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                      <span style="font-size:18px; margin-top:-5px;"><i class="fa fa-facebook"></i>
                      Facebook</span>
                  </label>
              </div>
          </div>

          <div class="col-md-6">
            <div class="form-group">
                <label style="cursor:pointer;">
                  <div class="icheckbox_minimal-blue checked" aria-checked="true" aria-disabled="false" style="position: relative;"><input type="checkbox" class="minimal"  style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div>
                  <span style="font-size:18px; margin-top:-5px;"><i class="fa fa-youtube-play"></i>
                  Youtube</span>
              </label>
          </div>
      </div>

      <div class="col-md-4">
        <label>Press Ad. :</label>
        <h5>This is Selected paper name</h5>
    </div>

    <div class="col-md-4">
        <label>Billboard :</label>
        <h5>This is Selected Billboard</h5>
    </div>

    <div class="col-md-4">
        <label>Project Board :</label>
        <h5>This is Selected Project Name</h5>
    </div>

    <div class="col-md-4">
        <label>Flyer :</label>
        <h5>This is Selected Flyer</h5>
    </div>

    <div class="col-md-4">
        <label>FNF :</label>
        <h5>This is Existing Customer Name</h5>
    </div>

    <div class="col-md-4">
        <label for="atl_other">Others :</label>
        <h5>Other Source</h5>
    </div>

    <div class="col-md-4">
        <label for="sale_agent">Sales Event :</label>
        <h5>Sales Event Name</h5>
    </div>

    <div class="col-md-4">
        <label for="cust_engagment">Customer Engagement :</label>
        <h5>Here is Customer Engagement</h5>
    </div>

    <div class="col-md-4">
        <label for="fair">Fair :</label>
        <h5>Hare is Sales Fair</h5>
    </div>
</div>
</div>
</div>

{{-- Sales Executive --}}
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Sales Executive</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sale_executive_user_group">User Group :</label>
                    <input type="text" class="form-control" id="sale_executive_user_group" name="sale_executive_user_group" value="" readonly="readonly" title="User Group" placeholder="User Group"/>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="sale_ex_user">User Name :</label>
                    <input type="text" class="form-control" id="sale_ex_user" name="sale_ex_user" value="" readonly="readonly" title="User Name" placeholder="User Name"/>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- More Details --}}
<div class="box" style="border-color:#9384ff;">
    <div class="box-header with-border">
        <h3 class="box-title">More Datails</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-4">
                <label for="">Customer DOB :</label>
                <h5><?php echo date('d-m-Y'); ?></h5>
            </div>

            <div class="col-md-4">
                <label for="">Wife Name :</label>
                <h5>Wife Name</h5>
            </div>

            <div class="col-md-4">
                <label for="">Wife DOB</label>
                <h5><?php echo date('d-m-Y'); ?></h5>
            </div>

            <div class="col-md-4">
                <label for="">Marriage Anniversary :</label>
                <h5><?php echo date('d-m-Y'); ?></h5>
            </div>

            <div class="col-md-4">
                <label for="">Children Name :</label>
                <h5>Here is Chaildren Name</h5>
            </div>

            <div class="col-md-4">
                <label for="">Children DOB :</label>
                <h5><?php echo date('d-m-Y'); ?></h5>
            </div>
        </div>
    </div>
</div>