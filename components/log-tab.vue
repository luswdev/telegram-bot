<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/http-vue-loader"></script>

<template>
    <div class="my-3 tab-pane" v-bind:class="[title=='update'?'fade show active':'']" :id="title+'-list'" role="tabpanel" :aria-labelledby="title+'-tab'">
        <div class="alert alert-styled">
            <span>
                <i class="fas fa-check mr-1"></i>
                Showing rows 0 - {{logs.length>0?logs.length-1:logs.length}} ({{logs.length}} total, Query took {{sec}} seconds.)
            </span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th v-if="title=='exec'" scope="col">API</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="r in logs" v-bind:key="r">
                        <th scope="row">{{r.id}}</th>
                        <td>{{day}}</td>
                        <td>{{r.time}}</td>
                        <td v-if="title=='exec'">{{r.api}}</td>
                        <td class="text-center">
                            <a href="#" v-on:click="$emit('get-log', r.id, title)">
                                <i class="fas fa-info-circle text-styled"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
module.exports = {
    props: ['title', 'logs', 'day', 'sec'],
}
</script>
    