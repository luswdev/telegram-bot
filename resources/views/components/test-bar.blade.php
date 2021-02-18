<div class="form-row">
    <div class="col-md-4 mb-3">
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="location-url">
                    <i class="fas fa-globe-asia mr-2"></i>
                    {{app_url()}}/bot/
                </span>
            </div>
            <input type="text" class="form-control" id="post-location" aria-describedby="location-url" v-model="targetUrl">
        </div>
    </div>   
    <div class="col-md-3 offset-md-5 text-right">
        <button class="btn btn-styled" id="submit" v-on:click="test_log">
            <i class="fas fa-rocket mr-1"></i>
            Test
            <span class="spinner-border spinner-border-sm" v-bind:class="{'d-none': !isSending}" role="status" aria-hidden="true"></span>
        </button>
        <button class="btn btn-secondary" id="clear" v-on:click="clear_log">
            <i class="fas fa-backspace mr-1"></i>
            Clear
        </button>
    </div>       
</div>