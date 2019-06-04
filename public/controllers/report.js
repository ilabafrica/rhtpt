Vue.http.headers.common['X-CSRF-TOKEN'] = $("#token").attr("value");
Vue.http.interceptors.unshift(function(request, next) {
    next(function(response) {
        if(typeof response.headers['content-type'] != 'undefined') {
            response.headers['Content-Type'] = response.headers['content-type'];
        }
    });
});
new Vue({

    el: '#manage-report',

    data: {
        tallies: [],
        percentiles: [],
        uns: [],
        talliesChart: [],
        percentilesChart: [],
        unsChart: [],
        rounds: [],
        loading: false,
        error: false,
        query: '',
        //variables used in the filters
        counties:[],
        counties_:[],
        subcounties: [],
        sub_counties: [],
        facilities:[],
        role: '',
        county:'',
        sub_county:'',
        facility:'',
        round_start: '',
        round_end: '',
        feedback_status: '',
        filters:''
    },

    computed: 
    {
    },

    mounted : function()
    {
        this.getRole();
        this.loadRounds();
        this.getVueReports();
    },

    methods : {
        getVueReports: function(page){
          this.$http.get('/vuereports').then((response) => {
            this.tallies = response.data.summaries;
            this.percentiles = response.data.percentiles;
            this.uns = response.data.unsperf;
            this.talliesChart = response.data.summariesChart;
            this.percentilesChart = response.data.percentilesChart;
            this.unsChart = response.data.unsPerfChart;
            this.getTallies(this.talliesChart);
            this.getPercentiles(this.percentilesChart);
            this.getUnperfs(this.unsChart);
          });
        },

        getTallies: function(content)
        {
            var xCategories = this.buildChart(content)[0];
            var seriesData = this.buildChart(content)[1];
        
            $('#talliesContainer').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Enrollment, Response and Performance',
                    x: -20 //center
                },
                subtitle: {
                    text: '',
                    x: -20
                },
                xAxis: {
                    categories: xCategories
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Count'
                    }
                },
                colors: ['#00acc1', '#bf360c', '#afb42b', '#45526E'],
                credits: {
                    enabled: false
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: false
                    }
                },
                series:seriesData
            });
        },

        getPercentiles: function(content)
        {
            var xCategories = this.buildChart(content)[0];
            var seriesData = this.buildChart(content)[1];
        
            $('#persContainer').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Response and Satisfaction Rates (%)',
                    x: -20 //center
                },
                subtitle: {
                    text: '',
                    x: -20
                },
                xAxis: {
                    categories: xCategories
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Percentage'
                    }
                },
                colors: ['#00acc1', '#afb42b'],
                credits: {
                    enabled: false
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: false
                    }
                },
                series:seriesData
            });
        },

        getUnperfs: function(content){
            var xCategories = this.buildChart(content)[0];
            var seriesData = this.buildChart(content)[1];
        
            $('#unsPerfContainer').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Reasons for Unsatisfactory Performance',
                    x: -20 //center
                },
                subtitle: {
                    text: '',
                    x: -20
                },
                xAxis: {
                    categories: xCategories
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Percentage'
                    }
                },
                colors: ['#2E2E2E', '#007E33', '#e91e63', '#6d4c41','#1a237e', '#ff6f00', '#bf360c', '#607d8b', '#45526E'],
                credits: {
                    enabled: false
                },
                plotOptions: {
                    column: {
                        dataLabels: {
                            enabled: true
                        },
                        enableMouseTracking: false
                    }
                },
                series:seriesData
            });
        },

        getRole: function(page){
            this.$http.get('/userrole').then((response) => {
                if(response.data){
                    this.role = response.data.role_id;
                    this.loadCounties();
                    if (this.role == 4) { //County Role
                        this.county = response.data.tier;
                        this.loadSubcounties();
                    }
                    if (this.role == 7) {// Subcounty Role
                        this.sub_county = response.data.tier;
                        this.loadFacilities();
                    }
                }
            })
        },

        loadRounds: function() 
        {
            this.$http.get('/rnds').then((response) => {
                this.rounds = response.data;
            }, (response) => {
                //console.log(response);
            });
        },

        //Populate counties from FacilityController
        loadCounties: function() {
            var url = '/cnts';
            this.counties = [];
            this.facilities = [];
            if(this.role == 3) url = '/partnercounties';
            this.$http.get(url).then((response) => {
                this.counties = response.data;
                this.jimbo = response.data;
            }, (response) => {
            });
        },

        // Populate subcounties from FacilityController
        loadSubcounties: function() {
            this.sub_county = "";
            this.facility = "";
            this.facilities = [];
            this.subcounties = [];
            this.$http.get('/subs/'+ this.county).then((response) => { 
                this.subcounties = response.data;
            }, (response) => {
            });
        }, 

        // Populate facilities from FacilityController
        loadFacilities: function() {
            this.facility = "";
            this.facilities = [];
            this.$http.get('/fclts/' + this.sub_county).then((response) => { 
                this.facilities = response.data;
            }, (response) => {
            });
        },

         //    Populate programs from ProgramController
        loadPrograms: function() {
            this.$http.get('/progs').then((response) => { 
                this.programs = response.data;
                for (var i = response.data.length - 1; i >= 0; i--) {
                    this.programs[i] = {id: response.data[i].id, value: response.data[i].value};
                    if (this.evaluated_results.program_name == this.programs[i].value) {
                        this.evaluated_results.program = this.programs[i].id;
                    }
                }
            }, (response) => {
            });
        },

        filter: function(page) {
            // Clear the error message.
            this.error = '';
            // Empty the results array so we can fill it with the new results.
            this.results = [];
            // Set the loading property to true, this will display the "Searching..." button.
            this.loading = true;
            var link = '/vuereports?page='+page;

            //if county
            if (this.facility) {

                link = link +'&facility='+this.facility;
            }else if (this.sub_county) {
            
                link = link +'&sub_county='+this.sub_county;
            }else if (this.county) {

                link= link +'&county='+this.county;
            }

            if (this.feedback_status) {

               link = link +'&feedback_status='+this.feedback_status;
            }

            if (this.round_start) {

               link = link +'&round_start='+this.round_start;
            }

            if (this.round_end) {

               link = link +'&round_end='+this.round_end;
            }

            console.log(link);
            // Making a get request to our API and passing the query to it.
            this.$http.get(link).then((response) => {
                this.tallies = response.data.summaries;
                this.percentiles = response.data.percentiles;
                this.uns = response.data.unsperf;
                this.talliesChart = response.data.summariesChart;
                this.percentilesChart = response.data.percentilesChart;
                this.unsChart = response.data.unsPerfChart;
                this.getTallies(this.talliesChart);
                this.getPercentiles(this.percentilesChart);
                this.getUnperfs(this.unsChart);
                // The request is finished, change the loading to false again.
                this.loading = false;
                // Clear the query.
                this.filters = 1;
            });
        },

        getData: function() 
        {
            this.$http.get('/rdata').then((response) => {
                this.tallies = response.data;
                //console.log(this.tallies);
                this.percentiles = response.data;
                this.uns = response.data;
            }, (response) => {
                //console.log(response);
            });
        },

        buildChart: function(content)
        {
            var jsonRoundData = content;
                
            var seriesData = [];
            var xCategories = [];
            var i, cat;
            for(i = 0; i < jsonRoundData.length; i++)
            {
                cat = '' + jsonRoundData[i].round;
                if(xCategories.indexOf(cat) === -1)
                {
                   xCategories[xCategories.length] = cat;
                }
            }
            for(i = 0; i < jsonRoundData.length; i++)
            {
                if(seriesData)
                {
                    var currSeries = seriesData.filter(function(seriesObject)
                    {
                        return seriesObject.name == jsonRoundData[i].title;
                    });
                    if(currSeries.length === 0)
                    {
                        seriesData[seriesData.length] = currSeries = {name: jsonRoundData[i].title, data: []};
                    } 
                    else 
                    {
                        currSeries = currSeries[0];
                    }
                    var index = currSeries.data.length;
                    currSeries.data[index] = jsonRoundData[i].total;
                } 
                else 
                {
                   seriesData[0] = {name: jsonRoundData[i].title, data: [jsonRoundData[i].total]}
                }
            }
            return [xCategories, seriesData];
        }
    }
});