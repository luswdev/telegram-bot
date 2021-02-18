<div id="verify-container">
    <h3 class="text-center col-sm-12 my-4">Two-factor authentication</h3>
    <div class="card text-left col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-12 my-2" id="verify-vue">
        <div class="card-body">
            <h6 class="card-subtitle mb-2 font-weight-normal">Authentication code</h6>
            <input type="text" class="form-control" id="totp" name="totp" placeholder="6-digit code" v-model="code" required v-on:keyup="onKeyPress">
            <button class="btn btn-styled w-100 mt-4" v-on:click="check">Verify</button>
        </div>
    </div>
    <div class="text-left col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-12 my-2">
        Open the two-factor authentication app on your device to view your authentication code and verify your identity.
    </div>
</div>
