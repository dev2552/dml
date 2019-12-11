
	
	var app = new Vue({
		el:'#app',
		data : {
			domains : [],
			filter:{'limit':limits[0],'group_id':'','domain':'','registrar_id':'','entered':'','datex':'','datec':'','status':'','is_active':'',sortObject:{'champ':'id','order':'desc'},'currency':'','price':'','created_by':''},
			pagination:{},
			show:8,
			model : {},
			newModel : {'entered':moment().format('YYYY-MM-DD'),'datex':moment().add(1,'year').format('YYYY-MM-DD'),'is_active':true,'is_expired':false,'status':'NEW','currency':currencies[0]},
			limits : limits,
			status : ['NEW','PRODUCTION','CANCELLED','In-Prod'],
			sDomain : {'champ':'domain','order':'asc'},
			sPrice : {'champ':'price','order':'asc'},
			currencies : currencies,
			groups : [],
			registrars : [],
			exportGroup : {},
			exportStart : moment().subtract(29, 'days'),
			exportEnd : moment().add(1,'minute'),
			symbols : data,
		},
		mounted : function()
		{
			this.fetchData();
			this.getGroups();
			this.getRegistrars();
			this.initDateRangePicker();
		},
		methods:{
			fetchData(link){
				this.domains = [];
				$('#spin').toggle();
				var url = link || 'api/fetchDomains';
				axios.post(url,this.filter).then((res)=>{
					this.domains = res.data.data;
					$('#spin').toggle();
					this.load();
					this.pagination={
						'next':res.data.links.next,
						'prev':res.data.links.prev,
						'currentPage':res.data.meta.current_page,
						'lastPage':res.data.meta.last_page,
						'path':res.data.meta.path,
						'first':res.data.links.first,
						'last':res.data.links.last
					}
				})
			},
			addDomain(){
				axios.post('api/addDomain',this.newModel).then((res)=>{
					$('#addDomain').modal('hide');
					this.fetchData();
					notification.getUnreadNotificationsCount();
					$.notify('Operation done Successfully','success');
				}).catch((err)=>{
					if(err.request) $.notify(JSON.parse(err.request.response).message);
				})
			},
			editDomain(domain){
				this.model = domain;
				this.model.datePurchase = moment(new Date(domain.datePurchase)).format('YYYY-MM-DD');
				this.model.dateExpiration = moment(new Date(domain.dateExpiration)).format('YYYY-MM-DD');
				$('#editDomain').modal();
			},
			updateDomain(domain){
				axios.put('api/updateDomain/'+domain.id,this.model).then((res)=>{
					$('#editDomain').modal('hide');
					this.fetchData(this.pagination.path+'?page='+this.pagination.currentPage);
					$.notify('Operation done Successfully','success');
					
				}).catch((err)=>{
					$.notify(err);
				})
			},
			removeDomain(domain){
				if(!confirm('Delete Domain ?'))
				{
					$('#editDomain').modal('hide');
					return;
				}
				axios.delete('api/removeDomain/'+domain.id).then((res)=>{
					$('#editDomain').modal('hide');
					this.fetchData();
					$.notify('Operation done Successfully','success');
				}).catch((err)=>{
					$.notify(err);
				})
			},
			filterDomains(){
				this.fetchData();
			},
			createDomain(){
				$('#addDomain').modal();
			},
			refreshData(){
				this.filter = {'limit':10,'group_id':'','domain':'','registrar_id':'','entered':'','datex':'','datec':'','status':'','is_active':'',sortObject:{'champ':'id','order':'desc'},'currency':'','price':'','created_by':''};
				this.fetchData();
				$.notify('Operation done Successfully','success');
				
			},
			color(status){
			if(status == 'NEW'){
				return 'btn-warning';
			}else if(status == 'PRODUCTION' || status == 'In-Prod'){
				return 'btn-info';
			}else if(status == 'CANCELLED')
			{
				return 'btn-danger';
			}
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
			if(champ == 'domain')
			{
				this.filter.sortObject.champ = this.sDomain.champ;
				this.filter.sortObject.order = this.toggleOrder(this.sDomain);
			}
			if(champ == 'price')
			{
				this.filter.sortObject.champ = this.sPrice.champ;
				this.filter.sortObject.order = this.toggleOrder(this.sPrice);
			}
			this.filterDomains();
		},
		getGroups()
		{
			axios.get('api/getGroups').then((res)=>{
				this.groups = res.data.data;
			})
		},
		getRegistrars()
		{
			axios.get('api/getRegistrars').then((res)=>{
				this.registrars = res.data.data;
			})
		},
		load()
		{
			$('#loader').css('display','none');
			$('#app').css('display','block');
		},
		openExportModal()
		{
			$('#exportModal').modal();
		},
		initDateRangePicker()
		{
			$('#reportrange').daterangepicker({
    		  	startDate: this.exportStart,
    		  	endDate: this.exportEnd,
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
    		this.cb(this.exportStart,this.exportEnd);
		},
		cb(start,end){
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			this.exportStart = start;
			this.exportEnd= end;
		},
		exportDomains()
		{
			$('#exportDomains').text('wait...');
			axios.post(
				'api/exportDomains',
				{'startDate':this.exportStart,'endDate':this.exportEnd,'groupId':this.exportGroup.id},
				{'responseType':'blob'})
			.then((res)=>
			{
				$('#exportDomains').text('export');
				const url = window.URL.createObjectURL(new Blob([res.data]));
				const link = document.createElement('a');
				link.href = url;
				var name =  'domains_'+moment().format('Y-m-d');
				if(this.exportGroup.name) name = name + '_group_' + this.exportGroup.name;
				name = name + '.xlsx';
				link.setAttribute('download', name);
				document.body.appendChild(link);
				link.click();
			})
		},
		currencyCode(currency)
		{
			return this.symbols.find((s)=>(s.currency == currency)).code;
		},


		},
	})
