<div class="modal fade" id="json-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-capitalize">@{{title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <pre class="m-0"><code class="mb-0 p-4 json hljs" v-html="result"></code></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times-circle"></i>
                    Close
                </button>
                <a class="btn btn-styled" :href="'/tg/test/?uid=' + id"  v-if="type == 'update'">
                    <i class="fas fa-rocket mr-1"></i>
                    Test the Result
                </a>
              </div>
        </div>
    </div>
</div>