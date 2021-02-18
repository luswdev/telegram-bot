<!--  warnning for wrong format -->
<bs4-alert classes="warning" title="Warning!" :shown="alertShow">
    <p>Please write down POST data in JSON format!</p>
</bs4-alert>

<!-- success for api response 200 -->
<bs4-alert classes="success" title="Success!" :shown="alertShow">
    <p>POST to @{{targetUrl}} success!</p>
    <details>
        <summary>Response</summary>
        <p><pre>@{{result}}</pre></p>
    </details>
</bs4-alert>

<!-- danger for API NOT response 200 -->
<bs4-alert classes="danger" :title="`${result}!`" :shown="alertShow">
    <p>POST to @{{targetUrl}} failed, please watch log to get more information.</p>
</bs4-alert>