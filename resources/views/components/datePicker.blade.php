<!-- day picker selecter -->
<form class="form-inline mb-4"  id="selecter" v-on:change.prevent="peakLog">
    <label class="my-1 mr-2 mb-1">
        Select a day 
    </label>
    <select class="custom-select mb-1 my-1 mr-sm-2" id="select-year" v-on:change="getDay" v-model="selecting.year">
        <option v-for="y in selecter.years" :value="y">@{{y}}</option>
    </select>
    <select class="custom-select mb-1 my-1 mr-sm-2" id="select-month" v-on:change="getDay" v-model="selecting.month">
        <option v-for="(m, i) in selecter.months" :value="i+1">@{{m}}</option>
    </select>
    <select class="custom-select mb-1 my-1 mr-sm-2" id="select-day" v-model="selecting.day">
        <option v-for="d in selecter.days" :value="d">@{{d}}</option>
    </select>
    <button class="btn btn-secondary my-1 mb-1 mr-2" v-bind:class="{'d-none': isToday}" id="today" v-on:click.prevent="backToday">Back to Today</button>
</form>
<hr>