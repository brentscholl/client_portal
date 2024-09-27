<div
    x-data="{chart: null}"
    x-init="chart = new Chartisan({
        el: '#login-chart',
        url: '@chart('login_chart')',
        hooks: new ChartisanHooks()
            .beginAtZero()
            .colors()
            .borderColors()
            .datasets('line'),
        });"
    class="bg-white shadow overflow-hidden sm:rounded-lg mt-4">
    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
        <div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-no-wrap">
            <div class="ml-4 mt-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Logins Last 3 Days
                </h3>
            </div>
        </div>
    </div>
    <div class="p-10">
        <div id="login-chart" style="height: 300px;"></div>
    </div>
</div>
