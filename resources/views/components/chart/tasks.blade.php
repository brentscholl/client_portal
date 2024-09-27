<div
    x-data="{chart: null}"
    x-init="chart = new Chartisan({
        el: '#tasks-chart',
        url: '@chart('tasks_chart')',
        hooks: new ChartisanHooks()
            .datasets('pie')
            .pieColors(['#6B7280', '#0DB7A7', '#0E9F6E', '#FF5A1F', '#F05252']),
        });"
    class="bg-white shadow overflow-hidden h-full sm:rounded-lg mt-4 {{ $attributes->get('class') }}">
    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
        <div class="-ml-4 -mt-4 flex justify-between items-center flex-wrap sm:flex-no-wrap">
            <div class="ml-4 mt-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Tasks
                </h3>
            </div>
        </div>
    </div>
    <div class="p-10">
        <div id="tasks-chart" style="height: 300px;"></div>
    </div>
</div>

