<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Get user category from session (you need to set this during login)
// Assuming you have this in your session after login
$user_category = isset($_SESSION['sup_category']) ? $_SESSION['sup_category'] : '';
?>

<fieldset>
    <div class="row pb-4 pt-4 container-fluid">
        <div class="col-7">
            <h2 class="fs-title" style="float:left">Categories</h2>
        </div>
        <div class="col-5">
            <h2 class="steps">Step 2 - 3</h2>
        </div>
    </div>
    <div class="row" style="align-items: center; padding-left: 60px;">

        <?php if ($user_category === 'RI' ): ?>
        <!-- Show all categories for general users (not PVC or Medicine) -->

        <!-- Vegetables -->
        <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0 bg-light" style="width: 15rem;">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#vegModal">
                <img class="card-img-top pt-4" src="../static/img/vegetable.png" style="width: 80px;" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title" style="text-decoration-line: none;">VEGETABLE ITEMS</h5>
                </div>
            </a>
        </div>

        <!-- Spices -->
        <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0" style="width: 15rem;">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#spicesModal">
                <img class="card-img-top pt-4" src="../static/img/spice.png" style="width: 80px;" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">SPICES</h5>
                </div>
            </a>
        </div>

        <!-- Fish -->
        <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0 bg-light" style="width: 15rem;">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#fishModal">
                <img class="card-img-top pt-4" src="../static/img/fish.png" style="width: 80px;" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">FISH</h5>
                </div>
            </a>
        </div>

        <!-- Dry Fish -->
        <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0" style="width: 15rem;">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dryfishModal">
                <img class="card-img-top pt-4" src="../static/img/dried-fish.png" style="width: 80px;" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">DRY FISH</h5>
                </div>
            </a>
        </div>

        <!-- Dry Items -->
        <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0 bg-light" style="width: 15rem;">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#dryItemsModal">
                <img class="card-img-top pt-4" src="../static/img/dried-item.png" style="width: 68px;" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">DRY ITEMS</h5>
                </div>
            </a>
        </div>

        <!-- Rice -->
        <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0 " style="width: 15rem;">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#riceModal">
                <img class="card-img-top pt-4" src="../static/img/rice.png" style="width: 68px;" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">RICE</h5>
                </div>
            </a>
        </div>

        <!-- Meat -->
        <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0 bg-light" style="width: 15rem;">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#chickenModal">
                <img class="card-img-top pt-4" src="../static/img/chicken-leg.png" style="width: 68px;" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">MEAT</h5>
                </div>
            </a>
        </div>

        <!-- Miscellaneous Items -->
        <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0" style="width: 15rem;">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#wrappingpModal">
                <img class="card-img-top pt-4" src="../static/img/gift-wrapping.png" style="width: 68px;" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">MISCELLANEOUS ITEMS</h5>
                </div>
            </a>
        </div>

       

        <?php endif; ?>

        <?php if ($user_category === 'PI' ): ?>
        <!-- PVC Items - Show for PVC users AND general users (but not Medicine users) -->
        <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0 bg-light" style="width: 15rem;">
            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#pvcModal">
                <img class="card-img-top pt-4" src="../static/img/Pvc.png" style="width: 68px;" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">PVC ITEMS</h5>
                </div>
            </a>
        </div>
        <?php endif; ?>
        
        <?php if ($user_category === 'CB' ): ?>
         <!-- Cables -->
            <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0 bg-light" style="width: 15rem;">
                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#cableModal">
                    <img class="card-img-top pt-4" src="../static/img/cables.png" style="width: 68px;" alt="Card image cap">
                    <div class="card-body">
                        <h5 class="card-title">CABLES</h5>
                    </div>
                </a>
            </div>
        <?php endif; ?>

        <?php if ($user_category === 'MI' ): ?>
        <!-- Medicine Items - Show for Medicine users AND general users (but not PVC users) -->
        <div class="card rounded shadow-sm col-2 mb-4 mb-lg-0" style="width: 15rem;">
            <a href="" data-bs-toggle="modal" data-bs-target="#medicineModal">
                <img class="card-img-top pt-4" src="../static/img/medicine.png" style="width: 68px;" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title">MEDICINE ITEMS</h5>
                </div>
            </a>
        </div>
        <?php endif; ?>

    </div>

    <!-- Preview the list -->
    <input type="button" class="btn btn-primary action-button" onclick="loadInventory()" data-bs-toggle="modal" value="Check" data-bs-target="#previewitemlist" />
    <input type="submit" name="btnDoneFunc" class="next action-button" value="" id="btnDoneFunc" title="" hidden />

</fieldset>
