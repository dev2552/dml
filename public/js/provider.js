var app = new Vue({
	el : '#app',
	data : {
		providers : [],
		newModel : {'status':'Active'},
		pagination : {},
		model : {},
		filter : {'limit':limits[0],'start':moment().startOf("year"),'end':moment().add(2,'minute'),'name':'','country':'','inscr_email':'','created':'','status':'','type':'',sortObject:{'champ':'id','order':'desc'},'created_by':''},
		countries:countries,
		limits : limits,
		types : ['Principal','Reseller','Canceled'],
		ss : ['Active','Inactive','Other'],
		sName : {'champ':'name','order':'asc'},
		sCountry : {'champ':'country','order':'asc'},
		sEmail : {'champ':'inscr_email','order':'asc'},
	},
	methods : {

		createProvider(){
			$('#addProvider').modal();
		},
		storeProvider(){
			axios.post('api/storeProvider',this.newModel).then((res)=>{
				this.filterProviders();
				$.notify('Operation done Successfully','success');
				$('#addProvider').modal('hide');
			}).catch((err)=>{
				if(err.request) $.notify(JSON.parse(err.request.response).message);
			});
		},
		filterProviders(url){
			this.providers = [];
			$('#spin').toggle();
			var url = url || 'api/filterProviders';
			axios.post(url,this.filter).then((res)=>{
				this.providers = res.data.data;
				$('#spin').toggle();
				this.load();
				this.pagination={
					'next':res.data.links.next,
					'prev':res.data.links.prev,
					'currentPage':res.data.meta.current_page,
					'lastPage':res.data.meta.last_page,
					'path':res.data.meta.path,
					'first':res.data.links.first,
					'last':res.data.links.last};
			}).catch((err)=>console.log(err));
		},
		editProvider(provider){
			this.model = provider;
			$('#editProvider').modal();
		},
		updateProvider(provider){
			axios.put('api/updateProvider/'+provider.id,this.model).then((res)=>{
				this.filterProviders(this.pagination.path+'?page='+this.pagination.currentPage);
				$.notify('Operation done Successfully','success');
				this.model = {};
				$('#editProvider').modal('hide'); 
			}).catch((err)=>{
				$.notify(err);
			});
		},
		deleteProvider(provider){
			if(!confirm('Delete Provider ?'))
			{
				$('#editProvider').modal('hide');
				return;
			}
			axios.delete('api/deleteProvider/'+provider.id).then((res)=>{
				this.filterProviders();
				$.notify('Operation done Successfully','success');
				this.model = {};
				$('#editProvider').modal('hide');
			}).catch((err)=>{
				console.log(err)
				$.notify(err);
			});
		},
		refreshProviders(){
			filter={'limit':limits[0],'start':moment().startOf("year"),'end':moment().add(2,'minute'),'name':'','country':'','inscr_email':'','created':'','status':'','type':'',sortObject:{'champ':'id','order':'desc'},'created_by':''},
			this.filterProviders();
			$.notify.defaults({'autoHideDelay':1200});
			$.notify('Operation done Successfully','success');
			

		},
		initDateRangePicker(){
    		  $('#reportrange').daterangepicker({
    		  	startDate: this.filter.start,
    		  	endDate: this.filter.end,
    		  	"drops": "down",
    		  	"showDropdowns" : true,
    		  	"linkedCalendars" : false,
    		  	ranges: {
		            'Today': [moment(), moment()],
		            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
		            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		            'This Month': [moment().startOf('month'), moment().endOf('month')],
		            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
		        },
    		  },this.cb);
    		 this.cb(this.filter.start,this.filter.end);
		},
		cb(start,end){
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			this.filter.start = start;
			this.filter.end = end;
		},
		filterByDates(){
			this.filterProviders();
		},
		status(status,provider){
			if(status == provider.status){
				return true;
			}
			return false;
		},

		toggleOrder(o)
		{
			if(o.order == 'asc')
			{
				o.order = 'desc';
				return 'desc';
			}
			if(o.order == 'desc')
			{
				o.order = 'asc';
				return 'asc';
			}
		},

		sort(champ)
		{
			if(champ == 'name')
			{
				this.filter.sortObject.champ = this.sName.champ;
				this.filter.sortObject.order = this.toggleOrder(this.sName);
			}
			if(champ == 'country')
			{
				this.filter.sortObject.champ = this.sCountry.champ;
				this.filter.sortObject.order = this.toggleOrder(this.sCountry);
			}
			if(champ == 'email')
			{
				this.filter.sortObject.champ = this.sEmail.champ;
				this.filter.sortObject.order = this.toggleOrder(this.sEmail);
			}
			this.filterProviders();
		},
		load()
		{
			$('#loader').css('display','none');
			$('#app').css('display','block');
		}
		


	},
	mounted : function(){
		this.filterProviders();
		this.initDateRangePicker();
	}
})

