<?php

// Database connection file
require_once('config.php');

// Select all images from database and display them in HTML <table>.

                        $sql = "SELECT * FROM mms_suppliers_details   WHERE msd_supplier_code = '1661824373'";
                        $res = mysqli_query($con,  $sql);

                        if (mysqli_num_rows($res) > 0) {
                          while ($images = mysqli_fetch_assoc($res)) {  ?>
                          
                          <div class="alb">
                          <button>
                            <img src="bank_details/<?=$images['image_url']?>">
                            </button> 
                          </div>
                          
                  <?php } }?> 
