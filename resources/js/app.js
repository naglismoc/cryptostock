/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const { default: axios } = require('axios');

require('./bootstrap');

window.Vue = require('vue').default;

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});

urlArr = window.location.href.split("/");
stockId = urlArr[urlArr.length - 1];


document.getElementById('click').addEventListener('click', function() {
    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(drawChart2);
    google.charts.setOnLoadCallback(drawChart);



});

function drawChart2() {
    console.log(stockId);
    axios.get(getStockStepped, {
            params: {
                'stock_id': stockId
            }

        })
        .then(response => {
            var data = google.visualization.arrayToDataTable(JSON.parse(response.data.orders));

            var options = {
                title: 'The decline of \'The 39 Steps\'',
                vAxis: { title: 'Accumulated Rating' },
                isStacked: true
            };

            var chart = new google.visualization.SteppedAreaChart(document.getElementById('chart_div2'));
            chart.draw(data, options);
        });

}









function drawChart() {

    axios.get(getStock, {
            params: {
                'stock_id': stockId
            }

        })
        .then(response => {
            console.log(response.data.orders);

            var data = google.visualization.arrayToDataTable(JSON.parse(response.data.orders));

            var options = {
                title: 'Company Performance',
                hAxis: { title: 'Year', titleTextStyle: { color: '#333' } },
                vAxis: { minValue: 0 }
            };

            var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
            chart.draw(data, options);

        });



}