<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />


<?php
include("../app/v1/connection-branch-admin.php");
include("common/header.php");
include("common/navbar.php");
include("common/sidebar.php");
require_once("common/pagination.php");
include("../app/v1/functions/branch/func-customers.php");

administratorAuth();

if (isset($_POST["changeStatus"])) {
  $newStatusObj = ChangeStatusCustomer($_POST, "customer_id", "customer_status");
  swalToast($newStatusObj["status"], $newStatusObj["message"]);
}


if (isset($_POST["createdata"])) {
  $addNewObj = createDataCustomer($_POST);
  swalToast($addNewObj["status"], $addNewObj["message"]);
}

if (isset($_POST["editdata"])) {
  $editDataObj = updateDataCustomer($_POST);

  swalToast($editDataObj["status"], $editDataObj["message"]);
}

if (isset($_POST["add-table-settings"])) {
  $editDataObj = updateInsertTableSettings($_POST, $_SESSION["logedBranchAdminInfo"]["adminId"]);
  swalToast($editDataObj["status"], $editDataObj["message"]);
}

$branch_id = $_SESSION["logedBranchAdminInfo"]["fldAdminBranchId"];
$company_id = $_SESSION["logedBranchAdminInfo"]["fldAdminCompanyId"];
if (isset($_GET['create'])) {
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mb-2 p-0  border-bottom">
      <?php if (isset($msg)) { ?>
        <div style="z-index: 999; float:right" class="mx-3 p-1 alert-success rounded">
          <?= $msg ?>
        </div>
      <?php } ?>
      <div class="container-fluid">
        <div class="row pt-2 pb-2">
          <div class="col-md-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= BRANCH_URL ?>" class="text-dark"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item"><a href="<?= basename($_SERVER['PHP_SELF']); ?>" class="text-dark">Manage Customer</a></li>
              <li class="breadcrumb-item active"><a href="<?= basename($_SERVER['PHP_SELF']); ?>" class="text-dark">Add Customer</a></li>
            </ol>
          </div>
          <div class="col-md-6" style="display: flex;">
            <button class="btn btn-danger btnstyle ml-2 add_data" value="add_draft">Save As Draft</button>
            <button class="btn btn-primary btnstyle gradientBtn ml-2 add_data" value="add_post"><i class="fa fa-plus fontSize"></i> Final Submit</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" id="add_frm" name="add_frm">
          <input type="hidden" name="createdata" id="createdata" value="">
          <input type="hidden" name="company_id" id="company_id" value="<?php echo $company_id; ?>">
          <input type="hidden" name="branch_id" id="branch_id" value="<?php echo $branch_id; ?>">
          <div class="row">
            <div class="col-md-8">
              <button type="button" class="btn-position" data-toggle="modal" data-target="#myModal"><i class="fa fa-cog" aria-hidden="true"></i></button>
              <div id="accordion">
                <div class="card card-primary">
                  <div class="card-header cardHeader">
                    <h4 class="card-title w-100"> <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseOne"> Basic Details </a> </h4>
                  </div>
                  <div id="collapseOne" class="collapse show" data-parent="#accordion">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <input type="text" class="m-input" id="customer_name" name="customer_name">
                            <label>Customer Name</label>
                            <span class="error"></span>
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <input type="text" class="m-input" id="customer_gstin" name="customer_gstin">
                            <label>GSTIN </label>
                          </div>
                        </div>

                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <select id="customer_currency" name="customer_currency" class="form-control form-control-border borderColor">
                              <option value="">Select Currency</option>
                              <?php
                              $listResult = getAllCurrencyType();
                              if ($listResult["status"] == "success") {
                                foreach ($listResult["data"] as $listRow) {
                              ?>
                                  <option value="<?php echo $listRow['currency_id']; ?>"><?php echo $listRow['currency_name']; ?></option>
                              <?php }
                              } ?>
                            </select>
                          </div>
                        </div>

                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <select id="customer_language" name="customer_language" class="form-control form-control-border borderColor">
                              <option value="">Select Language</option>
                              <?php
                              $listResult = getAllLanguage();
                              if ($listResult["status"] == "success") {
                                foreach ($listResult["data"] as $listRow) {
                              ?>
                                  <option value="<?php echo $listRow['language_id']; ?>"><?php echo $listRow['language_name']; ?></option>
                              <?php }
                              } ?>
                            </select>
                          </div>
                        </div>


                      </div>
                    </div>
                  </div>
                </div>
                <div class="card card-primary">
                  <div class="card-header cardHeader">
                    <h4 class="card-title w-100"> <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseTwo"> Customer Admin Details </a> </h4>
                  </div>
                  <div id="collapseTwo" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <input type="text" name="adminName" class="m-input" id="adminName">
                            <label>User Name</label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <input type="email" name="adminEmail" class="m-input" id="adminEmail">
                            <label>User Email</label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <input type="text" name="adminPhone" class="m-input" id="adminPhone">
                            <label>User Phone</label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <input type="text" name="adminPassword" class="m-input" id="adminPassword" value="<?php echo rand(1111, 9999); ?>">
                            <label>Password</label>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>

              </div>
            </div>
            <!---------------------------------------------------------------------------------------------->
            <div class="col-md-4">
              <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                  <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Home</a> </li>
                    <li class="nav-item"> <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Profile</a> </li>
                    <li class="nav-item"> <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Messages</a> </li>
                    <li class="nav-item"> <a class="nav-link" id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-three-settings" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Settings</a> </li>
                  </ul>
                </div>
                <div class="card-body fontSize">
                  <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab"> 90 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada lacus ullamcorper
                      dui
                      molestie, sit amet congue quam finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam
                      odio
                      magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin pellentesque tincidunt nisi,
                      vitae ullamcorper felis aliquam id. Pellentesque habitant morbi tristique senectus et netus et
                      malesuada fames ac turpis egestas. Proin id orci eu lectus blandit suscipit. Phasellus porta,
                      ante
                      et varius ornare, sem enim sollicitudin eros, at commodo leo est vitae lacus. Etiam ut porta
                      sem.
                      Proin porttitor porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus pulvinar non
                      consequat neque. Mauris lacus elit, condimentum ac condimentum at, semper vitae lectus. Cras
                      lacinia erat eget sapien porta consectetur. </div>
                    <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab"> Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut
                      ligula
                      tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                      Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas
                      sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu
                      lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod
                      pellentesque diam. </div>
                    <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab"> Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue
                      id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac
                      tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit
                      condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus
                      tristique.
                      Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est
                      libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id
                      fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna. </div>
                    <div class="tab-pane fade" id="custom-tabs-three-settings" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab"> Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis
                      ac,
                      ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi
                      euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum
                      placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam.
                      Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet
                      accumsan ex sit amet facilisis. </div>
                  </div>
                </div>
                <!-- /.card -->
              </div>
              <div class="w-100 mt-3">
                <button type="submit" name="addInventoryItem" class="gradientBtn btn-success btn btn-block btn-sm"> <i class="fa fa-plus fontSize"></i> Add New </button>
              </div>
            </div>
          </div>
        </form>

        <!-- modal -->
        <div class="modal" id="myModal3">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Heading</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="col-md-12 mb-3">
                  <div class="input-group">
                    <select name="goodsGroup" class="form-control form-control-border borderColor">
                      <option value="">Customer Group</option>
                      <option value="A">A</option>
                      <option value="B">B</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="input-group">
                    <input type="text" name="itemCode" class="m-input" id="exampleInputBorderWidth2">
                    <label>Item Code</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="input-group btn-col">
                    <button type="submit" class="btn btn-primary btnstyle">Submit</button>
                  </div>
                </div>
              </div>
              <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> -->
            </div>
          </div>
        </div>
        <!-- modal end -->
        <!-- modal -->
        <div class="modal" id="myModal4">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Heading4</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="col-md-12 mb-3">
                  <div class="input-group">
                    <select name="goodsGroup" class="form-control form-control-border borderColor">
                      <option value="">Customer Group</option>
                      <option value="A">A</option>
                      <option value="B">B</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="input-group">
                    <input type="text" name="itemCode" class="m-input" id="exampleInputBorderWidth2">
                    <label>Item Code</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="input-group btn-col">
                    <button type="submit" class="btn btn-primary btnstyle">Submit</button>
                  </div>
                </div>
              </div>
              <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> -->
            </div>
          </div>
        </div>
        <!-- modal end -->
      </div>
    </section>



    <!-- /.content -->
  </div>
<?php
} else if (isset($_GET['edit']) && $_GET["edit"] > 0) {
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mb-2 p-0  border-bottom">
      <?php if (isset($msg)) { ?>
        <div style="z-index: 999; float:right" class="mx-3 p-1 alert-success rounded">
          <?= $msg ?>
        </div>
      <?php } ?>
      <div class="container-fluid">
        <div class="row pt-2 pb-2">
          <div class="col-md-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= BRANCH_URL ?>" class="text-dark"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item"><a href="<?= basename($_SERVER['PHP_SELF']); ?>" class="text-dark">Manage Customer</a></li>
              <li class="breadcrumb-item active"><a href="<?= basename($_SERVER['PHP_SELF']); ?>" class="text-dark">Edit Customer</a></li>
            </ol>
          </div>
          <div class="col-md-6" style="display: flex;">
            <a href="<?= basename($_SERVER['PHP_SELF']); ?>"><button class="btn btn-danger btnstyle ml-2">Back</button></a>
            <button class="btn btn-danger btnstyle ml-2 edit_data">Save As Draft</button>
            <button class="btn btn-primary btnstyle gradientBtn ml-2 edit_data"><i class="fa fa-plus fontSize"></i> Final Submit</button>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" name="edit_frm" id="edit_frm">
          <input type="hidden" name="editdata" id="editdata" value="">
          <div class="row">
            <div class="col-md-8">
              <button type="button" class="btn-position" data-toggle="modal" data-target="#myModal"><i class="fa fa-cog" aria-hidden="true"></i></button>
              <div id="accordion">
                <div class="card card-primary">
                  <div class="card-header cardHeader">
                    <h4 class="card-title w-100"> <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseOne"> Classification </a> </h4>
                  </div>
                  <div id="collapseOne" class="collapse show" data-parent="#accordion">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <select id="" name="goodsType" class="select2 form-control form-control-border borderColor">
                              <option value="">Customer Type</option>
                              <option value="A">A</option>
                              <option value="B">B</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <select name="goodsGroup" class="select4 form-control form-control-border borderColor">
                              <option value="">Customer Group</option>
                              <option value="A">A</option>
                              <option value="B">B</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <select name="purchaseGroup" class="select2 form-control form-control-border borderColor">
                              <option value="">Purchase Group</option>
                              <option value="">A</option>
                              <option value="">B</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" name="branh" class="m-input" id="exampleInputBorderWidth2">
                            <label>Customer</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <select name="availabilityCheck" class="select2 form-control form-control-border borderColor">
                              <option value="">Availability Check</option>
                              <option value="Daily">Daily</option>
                              <option value="Weekly">Weekly</option>
                              <option value="By Weekly">By Weekly</option>
                              <option value="Monthly">Monthly</option>
                              <option value="Qtr">Qtr</option>
                              <option value="Half Y">Half Y</option>
                              <option value="Year">Year</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card card-danger">
                  <div class="card-header cardHeader">
                    <h4 class="card-title w-100"> <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseTwo"> Basic Details </a> </h4>
                  </div>
                  <div id="collapseTwo" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" name="itemCode" class="m-input" id="exampleInputBorderWidth2">
                            <label>Item Code</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" name="itemName" class="m-input" id="exampleInputBorderWidth2">
                            <label>Item Name</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" name="netWeight" class="m-input" id="exampleInputBorderWidth2">
                            <label>Net Weight</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" name="grossWeight" class="m-input" id="exampleInputBorderWidth2">
                            <label>Gross Weight</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Volume :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="volume" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="volume">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">height :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="height" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="height">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">width :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="width" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="width">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">length :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="length" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="length">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Base Unit Of Measure :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="baseUnitMeasure" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="baseUnitOfMeasure">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Issue Unit :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="issueUnit" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="issueUnit">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <textarea type="text" name="itemDesc" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Item Description"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card card-success">
                  <div class="card-header cardHeader">
                    <h4 class="card-title w-100"> <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseThree"> Storage Details </a> </h4>
                  </div>
                  <div id="collapseThree" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Storage Bin :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="storageBin" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Storage Bin">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Picking Area :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="pickingArea" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Picking Area">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Temp Control :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="tempControl" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Temp Control">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Storage Control :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="storageControl" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Storage Control">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Max Storage Period :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="maxStoragePeriod" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Max Storage Period">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Time Unit :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="timeUnit" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Time Unit">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Min Remain Self Life :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="minRemainSelfLife" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Min Remain Self Life">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card card-success">
                  <div class="card-header cardHeader">
                    <h4 class="card-title w-100"> <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseFour"> Purchase Details </a> </h4>
                  </div>
                  <div id="collapseFour" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Purchasing Value Key :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="purchasingValueKey" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Purchasing Value Key">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!----------------------------------------------------------------------------------------------->

            <div class="col-md-4">
              <div class="card card-primary card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                  <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item"> <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Home</a> </li>
                    <li class="nav-item"> <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Profile</a> </li>
                    <li class="nav-item"> <a class="nav-link" id="custom-tabs-three-messages-tab" data-toggle="pill" href="#custom-tabs-three-messages" role="tab" aria-controls="custom-tabs-three-messages" aria-selected="false">Messages</a> </li>
                    <li class="nav-item"> <a class="nav-link" id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-three-settings" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Settings</a> </li>
                  </ul>
                </div>
                <div class="card-body fontSize">
                  <div class="tab-content" id="custom-tabs-three-tabContent">
                    <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab"> 90 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada lacus ullamcorper
                      dui
                      molestie, sit amet congue quam finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam
                      odio
                      magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin pellentesque tincidunt nisi,
                      vitae ullamcorper felis aliquam id. Pellentesque habitant morbi tristique senectus et netus et
                      malesuada fames ac turpis egestas. Proin id orci eu lectus blandit suscipit. Phasellus porta,
                      ante
                      et varius ornare, sem enim sollicitudin eros, at commodo leo est vitae lacus. Etiam ut porta
                      sem.
                      Proin porttitor porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus pulvinar non
                      consequat neque. Mauris lacus elit, condimentum ac condimentum at, semper vitae lectus. Cras
                      lacinia erat eget sapien porta consectetur. </div>
                    <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab"> Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut
                      ligula
                      tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                      Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas
                      sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu
                      lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod
                      pellentesque diam. </div>
                    <div class="tab-pane fade" id="custom-tabs-three-messages" role="tabpanel" aria-labelledby="custom-tabs-three-messages-tab"> Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue
                      id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac
                      tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit
                      condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus
                      tristique.
                      Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est
                      libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id
                      fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna. </div>
                    <div class="tab-pane fade" id="custom-tabs-three-settings" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab"> Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis
                      ac,
                      ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi
                      euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum
                      placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam.
                      Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet
                      accumsan ex sit amet facilisis. </div>
                  </div>
                </div>
                <!-- /.card -->
              </div>
              <div class="w-100 mt-3">
                <button type="submit" name="addInventoryItem" class="gradientBtn btn-success btn btn-block btn-sm"> <i class="fa fa-plus fontSize"></i> Add New </button>
              </div>
            </div>
          </div>
        </form>

        <!-- modal -->
        <div class="modal" id="myModal3">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Heading</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="col-md-12 mb-3">
                  <div class="input-group">
                    <select name="goodsGroup" class="form-control form-control-border borderColor">
                      <option value="">Customer Group</option>
                      <option value="A">A</option>
                      <option value="B">B</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="input-group">
                    <input type="text" name="itemCode" class="m-input" id="exampleInputBorderWidth2">
                    <label>Item Code</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="input-group btn-col">
                    <button type="submit" class="btn btn-primary btnstyle">Submit</button>
                  </div>
                </div>
              </div>
              <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> -->
            </div>
          </div>
        </div>
        <!-- modal end -->
        <!-- modal -->
        <div class="modal" id="myModal4">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h4 class="modal-title">Heading4</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="col-md-12 mb-3">
                  <div class="input-group">
                    <select name="goodsGroup" class="form-control form-control-border borderColor">
                      <option value="">Customer Group</option>
                      <option value="A">A</option>
                      <option value="B">B</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="input-group">
                    <input type="text" name="itemCode" class="m-input" id="exampleInputBorderWidth2">
                    <label>Item Code</label>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="input-group btn-col">
                    <button type="submit" class="btn btn-primary btnstyle">Submit</button>
                  </div>
                </div>
              </div>
              <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div> -->
            </div>
          </div>
        </div>
        <!-- modal end -->
      </div>
    </section>

    <!-- /.content -->
  </div>
<?php
} else if (isset($_GET['view']) && $_GET["view"] > 0) {
?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header mb-2 p-0  border-bottom">
      <?php if (isset($msg)) { ?>
        <div style="z-index: 999; float:right" class="mx-3 p-1 alert-success rounded">
          <?= $msg ?>
        </div>
      <?php } ?>
      <div class="container-fluid">
        <div class="row pt-2 pb-2">
          <div class="col-md-6">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= BRANCH_URL ?>" class="text-dark"><i class="fas fa-home"></i> Home</a></li>
              <li class="breadcrumb-item"><a href="<?= basename($_SERVER['PHP_SELF']); ?>" class="text-dark">Manage Customer</a></li>
              <li class="breadcrumb-item active"><a href="<?= basename($_SERVER['PHP_SELF']); ?>" class="text-dark">View Customer</a></li>
            </ol>
          </div>
          <div class="col-md-6" style="display: flex;">
            <a href="<?= basename($_SERVER['PHP_SELF']); ?>"><button class="btn btn-danger btnstyle ml-2">Back</button></a>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <form action="" method="POST">
          <div class="row">
            <div class="col-md-8">
              <button type="button" class="btn-position" data-toggle="modal" data-target="#myModal"><i class="fa fa-cog" aria-hidden="true"></i></button>
              <div id="accordion">
                <div class="card card-primary">
                  <div class="card-header cardHeader">
                    <h4 class="card-title w-100"> <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseOne"> Classification </a> </h4>
                  </div>
                  <div id="collapseOne" class="collapse show" data-parent="#accordion">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <select id="" name="goodsType" class="select2 form-control form-control-border borderColor">
                              <option value="">Customer Type</option>
                              <option value="A">A</option>
                              <option value="B">B</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <div class="input-group">
                            <select name="goodsGroup" class="select4 form-control form-control-border borderColor">
                              <option value="">Customer Group</option>
                              <option value="A">A</option>
                              <option value="B">B</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <select name="purchaseGroup" class="select2 form-control form-control-border borderColor">
                              <option value="">Purchase Group</option>
                              <option value="">A</option>
                              <option value="">B</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" name="branh" class="m-input" id="exampleInputBorderWidth2">
                            <label>Customer</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <select name="availabilityCheck" class="select2 form-control form-control-border borderColor">
                              <option value="">Availability Check</option>
                              <option value="Daily">Daily</option>
                              <option value="Weekly">Weekly</option>
                              <option value="By Weekly">By Weekly</option>
                              <option value="Monthly">Monthly</option>
                              <option value="Qtr">Qtr</option>
                              <option value="Half Y">Half Y</option>
                              <option value="Year">Year</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card card-danger">
                  <div class="card-header cardHeader">
                    <h4 class="card-title w-100"> <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseTwo"> Basic Details </a> </h4>
                  </div>
                  <div id="collapseTwo" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" name="itemCode" class="m-input" id="exampleInputBorderWidth2">
                            <label>Item Code</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" name="itemName" class="m-input" id="exampleInputBorderWidth2">
                            <label>Item Name</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" name="netWeight" class="m-input" id="exampleInputBorderWidth2">
                            <label>Net Weight</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="input-group">
                            <input type="text" name="grossWeight" class="m-input" id="exampleInputBorderWidth2">
                            <label>Gross Weight</label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Volume :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="volume" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="volume">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">height :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="height" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="height">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">width :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="width" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="width">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">length :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="length" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="length">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Base Unit Of Measure :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="baseUnitMeasure" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="baseUnitOfMeasure">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Issue Unit :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="issueUnit" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="issueUnit">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <textarea type="text" name="itemDesc" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Item Description"></textarea>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card card-success">
                  <div class="card-header cardHeader">
                    <h4 class="card-title w-100"> <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseThree"> Storage Details </a> </h4>
                  </div>
                  <div id="collapseThree" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Storage Bin :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="storageBin" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Storage Bin">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Picking Area :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="pickingArea" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Picking Area">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Temp Control :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="tempControl" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Temp Control">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Storage Control :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="storageControl" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Storage Control">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Max Storage Period :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="maxStoragePeriod" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Max Storage Period">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Time Unit :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="timeUnit" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Time Unit">
                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Min Remain Self Life :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="minRemainSelfLife" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Min Remain Self Life">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card card-success">
                  <div class="card-header cardHeader">
                    <h4 class="card-title w-100"> <a class="d-block w-100 text-dark" data-toggle="collapse" href="#collapseFour"> Purchase Details </a> </h4>
                  </div>
                  <div id="collapseFour" class="collapse" data-parent="#accordion">
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="row">
                            <div class="col-md-6">
                              <label for="" class="form-control borderNone">Purchasing Value Key :</label>
                            </div>
                            <div class="col-md-6">
                              <input type="text" name="purchasingValueKey" class="form-control form-control-border borderColor" id="exampleInputBorderWidth2" placeholder="Purchasing Value Key">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </section>
    <!-- /.content -->
  </div>
<?php
} else {

?>
  <!-- Content Wrapper. Contains page content -->

  <!-- /.Content Wrapper. Contains page content -->






  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">

      <ul id='frontEnd'>
        <div class="row btn-row">
          <div class="col-sm-6"></div>
          <div class="col-sm-6">
            <div class="btn-group tree-btn">
              <button class="btn btn-success">Add Group</button>
              <button class="btn btn-success">Add Account</button>
            </div>
          </div>
        </div>


        <li><span class='caret'>Assests</span>
          <ul class='nested'>
            <li>child-1
              <div class="btn-group-action">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="customSwitches">
                  <label class="custom-control-label" for="customSwitches"></label>
                </div>
                <i class="fa fa-pen-to-square"></i>
                <i class="fa-solid fa-trash" style="color: red;"></i>
              </div>
            </li>
            <li>child-2
              <div class="btn-group-action">
                <button class="btn btn-primary">Edit</button>
                <button class="btn btn-primary">Delete</button>
              </div>
            </li>
          </ul>
        </li>
        <li><span class='caret'>Liability</span>
          <ul class='nested'>
            <li>child-1
              <div class="btn-group-action">
                <button class="btn btn-primary">Edit</button>
                <button class="btn btn-primary">Delete</button>
              </div>
            </li>
            <li>child-2
              <div class="btn-group-action">
                <button class="btn btn-primary">Edit</button>
                <button class="btn btn-primary">Delete</button>
              </div>
            </li>
          </ul>
        </li>
        <li><span class='caret'>Income</span>
          <ul class='nested'>
            <li>child-1
              <div class="btn-group-action">
                <button class="btn btn-primary">Edit</button>
                <button class="btn btn-primary">Delete</button>
              </div>
            </li>
            <li>Child-2
              <div class="btn-group-action">
                <button class="btn btn-primary">Edit</button>
                <button class="btn btn-primary">Delete</button>
              </div>
            </li>
          </ul>
        </li>
        <li><span class='caret'>Expenditure</span>
          <ul class='nested'>
            <li>child-1
              <div class="btn-group-action">
                <button class="btn btn-primary">Edit</button>
                <button class="btn btn-primary">Delete</button>
              </div>
            </li>
            <li>child-2
              <div class="btn-group-action">
                <button class="btn btn-primary">Edit</button>
                <button class="btn btn-primary">Delete</button>
              </div>
            </li>
          </ul>
        </li>

      </ul>
    </div>

    <!-- <div class="col-md-8">


            <ul id="backend">


            </ul>
        </div> -->
  </div>


  </div>


  <!-- For Pegination------->
  <form name="frm_opts" action="<?= $_SERVER['REQUEST_URI']; ?>" method="post">
    <input type="hidden" name="pageNo" value="<?php if (isset($_REQUEST['pageNo'])) {
                                                echo  $_REQUEST['pageNo'];
                                              } ?>">
  </form>
  <!-- End Pegination from------->




<?php
}
include("common/footer.php");
?>

<script>
  $(".add_data").click(function() {
    var data = this.value;
    $("#createdata").val(data);
    //confirm('Are you sure to Submit?')
    $("#add_frm").submit();
  });
  $(".edit_data").click(function() {
    var data = this.value;
    $("#editdata").val(data);
    alert(data);
    //$( "#edit_frm" ).submit();
  });


  function srch_frm() {
    if ($('#form_date_s').val().trim() != '' && $('#to_date_s').val().trim() === '') { //$("#phone_r_err").css('display','block');
      //$("#phone_r_err").html("Your Phone Number");
      alert("Enter To Date");
      $('#to_date_s').focus();
      return false;
    }
    if ($('#to_date_s').val().trim() != '' && $('#form_date_s').val().trim() === '') { //$("#phone_r_err").css('display','block');
      //$("#phone_r_err").html("Your Phone Number");
      alert("Enter From Date");
      $('#form_date_s').focus();
      return false;
    }

  }

  function table_settings() {
    var favorite = [];
    $.each($("input[name='settingsCheckbox[]']:checked"), function() {
      favorite.push($(this).val());
    });
    var check = favorite.length;
    if (check < 5) {
      alert("Please Check Atlast 5");
      return false;
    }

  }


  $(document).ready(function() {



    $('.select2')
      .select2()
      .on('select2:open', () => {
        $(".select2-results:not(:has(a))").append(`<div class="btn-row"><a type="button" class="btn btn-primary add-btn" data-toggle="modal" data-target="#myModal3">
    Add New
  </a></div>`);
      });
    //**************************************************************
    $('.select4')
      .select4()
      .on('select4:open', () => {
        $(".select4-results:not(:has(a))").append(`<div class="btn-row"><a type="button" class="btn btn-primary add-btn" data-toggle="modal" data-target="#myModal4">
    Add New
  </a></div>`);
      });
  });
</script>
<script>
  function leaveInput(el) {
    if (el.value.length > 0) {
      if (!el.classList.contains('active')) {
        el.classList.add('active');
      }
    } else {
      if (el.classList.contains('active')) {
        el.classList.remove('active');
      }
    }
  }

  var inputs = document.getElementsByClassName("m-input");
  for (var i = 0; i < inputs.length; i++) {
    var el = inputs[i];
    el.addEventListener("blur", function() {
      leaveInput(this);
    });
  }

  // *** autocomplite select *** //
  wow = new WOW({
    boxClass: 'wow', // default
    animateClass: 'animated', // default
    offset: 0, // default
    mobile: true, // default
    live: true // default
  })
  wow.init();
</script>



<!---Nodetree start--->


<script>
  function expand() {
    $('.nested').toggle('caret-down');
  }

  // function collapse() {
  //    $('.nested').toggle('hide');
  // }

  carets = document.getElementsByClassName('caret');

  for (var i = 0; i < carets.length; i++) {
    carets[i].addEventListener('click', function() {
      this.classList.toggle('caret-down')
      parent = this.parentElement;
      parent.querySelector('.nested').classList.toggle('active')
    })
  }


  // createParent = document.getElementById('createParent')
  // backend = document.getElementById('backend')
  // createParent.addEventListener('click', function() {

  //     backend.innerHTML += ` <li>
  //     <input type="text" value='Parent'>
  //     <button class='createChild'><i class="fa fa-sitemap" aria-hidden="true"></i></button>
  //     <span class='closeIT'>X</span>
  // </li>`
  // })

  backend.addEventListener('click', function(e) {
    if (e.target.classList == 'closeIT') {
      // e.target.parentElement.remove();
    }

    // if (e.target.classList == 'createChild') {
    //     createChildBTN = e.target;
    //     li = createChildBTN.parentElement;
    //     count = prompt('Enter the Number of Row');
    //     var x = '';
    //     for (var i = 1; i <= count; i++) {
    //         x += ` <li>
    //         <input type="text" value='Child'>
    //         <button class='createGrandChild'><i class="fa fa-sitemap" aria-hidden="true"></i></button>
    //         <span class='closeIT'>X</span>
    //     </li>`
    //     }

    //     li.innerHTML += `<ul>${x}</ul>`;

    // }

    if (e.target.classList == 'createGrandChild') {
      createChildBTN = e.target;
      li = createChildBTN.parentElement;
      count = prompt('Enter the Number of Row');
      var x = '';
      for (var i = 1; i <= count; i++) {
        x += ` <li>
            <input type="text" value='Grand Child'>
            <button class='createGreatGrandChild'><i class="fa fa-sitemap" aria-hidden="true"></i></button>
            <span class='closeIT'>X</span>
        </li>`
      }

      li.innerHTML += `<ul>${x}</ul>`;

    }

    if (e.target.classList == 'createGreatGrandChild') {
      createChildBTN = e.target;
      li = createChildBTN.parentElement;
      count = prompt('Enter the Number of Row');
      var x = '';
      for (var i = 1; i <= count; i++) {
        x += ` <li>
            <input type="text" value='Great Grand Child'>
           
            <span class='closeIT'>X</span>
        </li>`
      }

      li.innerHTML += `<ul>${x}</ul>`;

    }
  });


  function expandAll() {
    $(".collapsible-header").addClass("active");
    $(".collapsible").collapsible({
      accordion: false
    });
  }

  function collapseAll() {
    $(".collapsible-header").removeClass(function() {
      return "active";
    });
    $(".collapsible").collapsible({
      accordion: true
    });
    $(".collapsible").collapsible({
      accordion: false
    });
  }
</script>


<!------Nodetree end------->