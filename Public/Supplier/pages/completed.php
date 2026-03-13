<?php
// File: Public/Supplier/pages/completed.php
?>
<fieldset>
    <div class="form-card text-center" style="padding: 40px 20px;">
        <div class="row">
            <div class="col-12 text-end">
                <h2 class="steps" style="margin: 0; padding: 0; font-size: 22px; color: #6c757d; font-weight: 500;">Step 3 - 3</h2>
            </div>
        </div>
        
        <div class="row justify-content-center mt-4">
            <div class="col-3">
                <img src="../static/img/photos/success.gif" class="fit-image" style="width: 100px; height: 100px;" alt="Success">
            </div>
        </div>
        
        <br><br>
        
        <div class="row justify-content-center">
            <div class="col-7 text-center">
                <h2 class="purple-text text-center" style="color: #28a745; font-weight: bold;">TENDER SUBMITTED SUCCESSFULLY!</h2>
                <p style="font-size: 16px; color: #6c757d;">Your bid has been recorded. You can view your submission history or print the confirmation below.</p>
            </div>
        </div>
        
        <br>
        
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="d-flex justify-content-center gap-3">
                    <a href="pages/tenderHistory.php" class="btn btn-primary" style="padding: 10px 25px; border-radius: 5px;">View Tender History</a>
                    <button type="button" class="btn btn-success" style="padding: 10px 25px; border-radius: 5px;" onclick="window.location.reload();">Done</button>
                </div>
            </div>
        </div>
    </div>
</fieldset>

<style>
.gap-3 {
    gap: 1rem;
}
.fit-image {
    width: 100%;
    object-fit: cover;
}
</style>
