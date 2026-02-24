<form id="insertsup" method="post" class="sign-up-form">
  <img class="mb-4" src="../static/img/9.png" width="20%" alt="">
  <h2 class="title">Register</h2>
  <b>
    <p id="messagedisplay"></p>
  </b>
  <div class="input-field">
    <i class="fas fa-user"></i>
    <input type="text" placeholder="Supplier Name" name="supname" id="supname" required />
  </div>
  <p class="note" style="color:red">* Please make sure that supplier name is same as BR name.</p>

  <div class="input-field mb-3">
    <i class="fas fa-list"></i>
    <select class="form-select" name="supcat" id="supcat" required>
      <option value="">Loading categories...</option>
    </select>
  </div>
  <div class="input-field">
    <i class="fas fa-list"></i>
    <input type="textarea" placeholder="Category Description" name="description" id="description" required />
  </div>
  <div class="input-field">
    <i class="fas fa-map-marker"></i>
    <input type="text" placeholder="Address" name="address" id="address" required />
  </div>
  <div class="input-field">
    <i class="fas fa-phone-alt"></i>
    <input type="tel" placeholder="0778978987" name="mobile" id="mobile1" pattern="[0]{1}[7]{1}[0-9]{8}" required />
  </div>
  <div class="input-field">
    <i class="fas fa-envelope"></i>
    <input type="email" placeholder="Email Address" name="email" id="email" required />
  </div>

  <div class="form-group ">
    <div class="g-recaptcha" data-sitekey="6LeyhpcgAAAAAAwsDOsKlWMVpwvmorC6sJ6oLNRz"></div>
  </div>
  <input type="submit" name="insertbtn" id="insertbtn" class="btn" value="Register" />
</form>
