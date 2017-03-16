Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");

new Vue({

  el: '#manage-report',

  data: {
    tallies: [],
    percentiles: [],
    uns: [],
    talliesChart: null,
    percentilesChart: null,
    unsChart: null,
    from: '',
    to: '',
    rounds: [],
  },

  computed: {
    },

  ready : function(){
        this.loadRounds();
        this.getTallies();
  		//this.getTallies(this.from, this.to);
        //this.getPercentiles(this.from, this.to);
        //this.getUns(this.from, this.to);
  },

  methods : {

        getTallies: function(){
            Highcharts.chart('talliesContainer', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Enrollment, Response and Satisfaction'
                },
                subtitle: {
                    text: 'Rounds 13 - 16'
                },
                xAxis: {
                    categories: [
                        'Round 13',
                        'Round 14',
                        'Round 15',
                        'Round 16'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Counts'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Enrollment',
                    data: [7333, 7319, 9541, 19600]

                }, {
                    name: 'Response',
                    data: [4283, 4152, 7534, 0]

                }, {
                    name: 'Satisfactory',
                    data: [3609, 3062, 6069, 0]

                }, {
                    name: 'Unsatisfactory',
                    data: [674, 1090, 1465, 0]

                }]
            });
        },

      loadRounds: function() {
        this.$http.get('/rnds').then((response) => {
            this.rounds = response.data;

        }, (response) => {
            console.log(response);
        });
      },
  }

});