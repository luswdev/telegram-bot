<div class="tab-content" id="result-content">
    <log-tab title="update" v-bind:logs="update" v-bind:sec="sec" v-bind:day="day" @get-log="openJson"></log-tab>
    <log-tab title="exec" v-bind:logs="exec" v-bind:sec="sec" v-bind:day="day" @get-log="openJson"></log-tab>
    <div class="d-none d-md-block text-right">
        <button class="btn btn-styled" v-on:click="download_log()">
            <i class="fas fa-save mr-2"></i>
            Download
        </button>
    </div>
</div>  