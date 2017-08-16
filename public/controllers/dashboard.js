Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({

    el: '#manage-dashboard',

    data: {
        egender: [],
        percentiles: [],
        uns: [],
        talliesChart: null,
        percentilesChart: null,
        unsChart: null,
        from: '',
        to: '',
        rounds: [],
        loading: false,
        error: false,
        query: ''
    },

    computed: {
    },

    mounted : function(){
        this.getGender();
        this.getProgram();
        //this.getVueReports();
        //this.getTallies();
        //this.getPercentiles();
        //this.getUnperfs();
    		//this.getTallies(this.from, this.to);
        //this.getPercentiles(this.from, this.to);
        //this.getUns(this.from, this.to);
    },

    methods : {
        getVueReports: function(page){
            this.$http.get('/vuereports').then((response) => {
                this.tallies = response.data.summaries;
                this.percentiles = response.data.percentiles;
                this.uns = response.data.unsperf;
            });
        },

        getGender: function()
        {
            this.$http.get('dash/ge').then((response) => {
                processed_json = new Array();
                cont = response.data;
                $.map(cont, function(obj, i) {
                    processed_json.push([obj.key, parseInt(obj.value)]);
                });
                $('#gContainer').highcharts({
                    chart: {
                        type: 'pie',
                        name: 'Gender',
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    },
                    colors: ['#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#64E572', '#FF9655', '#FFF263','#6AF9C4'],
                    title: {
                        text: 'Enrolment By Gender'
                    },
                    credits: {
                      enabled: false
                    },
                    tooltip: {
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                formatter: function() {
                                    return '<b>'+ this.point.name +'</b>: '+ this.y;
                                }
                            }
                        }
                    },
                    series: [{
                        data: processed_json
                    }]
                });
            }, (response) => {
                //cont = response.data;
                //console.log(response);
            });
        },

        getProgram: function()
        {
            this.$http.get('dash/pr').then((response) => {
                processed_json = new Array();
                cont = response.data;
                $.map(cont, function(obj, i) {
                    processed_json.push([obj.key, parseInt(obj.value)]);
                });
                $('#pContainer').highcharts({
                    chart: {
                        type: 'pie',
                        name: 'Program',
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false
                    },
                    colors: ['#50B432', '#ED561B', '#DDDF00', '#24CBE5', '#2E2E2E', '#6d4c41', '#607d8b', '#45526E', '#007E33'],
                    title: {
                        text: 'Enrolment By Program'
                    },
                    credits: {
                      enabled: false
                    },
                    tooltip: {
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',
                            dataLabels: {
                                enabled: true,
                                formatter: function() {
                                    return '<b>'+ this.point.name +'</b>: '+ this.y;
                                }
                            }
                        }
                    },
                    series: [{
                        data: processed_json
                    }]
                });
            }, (response) => {
                //cont = response.data;
                //console.log(response);
            });
        },
    }
});