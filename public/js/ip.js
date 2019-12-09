var app = new Vue({
	el : '#app',
	data : {
		newIp : {'currency':currencies[0],'ip_status':'New'},
		types : ['IPV4','IPV6','FailOver','Subnet'],
		currencies : currencies,
		filter : {'limit':10,'group_id':'','provider':'','ip':'','netmask':'','server_id':'','gateway':'','price':'','ip_status':'','is_active':'',sortObject:{'champ':'id','order':'desc'},'currency':'','created_by':''},
		ips : [],
		pagination : {},
		selectedIp : {'provider':{},'server':{}},
		limits : limits,
		status : ['New','prod','block','canc'],
		sPrice : {'champ':'price','order':'asc'},
		servers : [],
		providers : [],
		exportStart : moment().subtract(29,'days'),
		exportEnd : moment().add(1,'minute'),
		symbols : data,

	},
	methods : {
		createIp(){
			$('#createIp').modal();
		},
		storeIp(){
			axios.post('api/storeIp',this.newIp).then((res)=>{
				this.filterIps();
				notification.getUnreadNotificationsCount();
				$.notify('Operation done Successfully','success');
				$('#createIp').modal('hide');
			}).catch((err)=>{
				if(err.request) $.notify(JSON.parse(err.request.response).message);
			})
		},
		filterIps(url){
			this.ips = [];
			$('#spin').toggle();
			var url = url || 'api/filterIps';
			axios.post(url,this.filter).then((res)=>{
				this.ips = res.data.data;
				//this.getCountryCode(this.ips);
				$('#spin').toggle();
				this.load();
				this.pagination = {
					'next':res.data.links.next,
					'prev':res.data.links.prev,
					'lastPage':res.data.meta.last_page,
					'currentPage':res.data.meta.current_page,
					'path':res.data.meta.path,
					'first':res.data.links.first,
					'last':res.data.links.last
				}
			})
		},
		refreshIps(){
			this.filter ={'limit':10,'provider':'','ip':'','netmask':'','server_id':'','gateway':'','price':'','ip_status':'','is_active':'',sortObject:{'champ':'id','order':'desc'},'currency':'','created_by':''};
			this.filterIps();
			$.notify('Operation done Successfully','success');
		},
		editIp(ip){
			this.selectedIp = ip;
			$('.edit_ip_server_select').append(new Option(ip.server.s_name,ip.server.id,false,true)).trigger('change');
			$('#editIp').modal();
		},
		updateIp(){
			axios.put('api/updateIp/'+this.selectedIp.id,this.selectedIp).then((res)=>{
				this.filterIps(this.pagination.path+'?page='+this.pagination.currentPage);
				$.notify('Operation done Successfully','success');
				$('#editIp').modal('hide');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			});
		},
		color(status){
			if(status == 'New'){
				return 'btn-warning';
			}else if(status == 'prod'){
				return 'btn-info';
			}else if(status == 'block')
			{
				return 'btn-default';
			}else if(status == 'canc')
			{
				return 'btn-danger';
			}
		},
		setStatus(status){
			if(status == 'New'){
				return 'New';
			}else if(status == 'prod'){
				return 'In prodcution'
			}else if(status == 'block')
			{
				return 'Blocked'
			}else if(status == 'canc')
			{
				return 'Canceled';
			}
		},
		deleteIp(){
			if(!confirm('Delete Ip ?'))
			{
				$('#editIp').modal('hide');
				return;
			}
			axios.delete('api/deleteIp/'+this.selectedIp.id).then((res)=>{
				this.filterIps();
				$.notify('Operation done Successfully','success');
				$('#editIp').modal('hide');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		toggleOrder(o){
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
			if(champ == 'price')
			{
				this.filter.sortObject.champ = this.sPrice.champ;
				this.filter.sortObject.order = this.toggleOrder(this.sPrice);
			}
				this.filterIps();
		},
		getServers()
		{
			axios.get('api/getAllServers').then((res)=>{
				this.servers = res.data.data;
			})
		},
		getProviders()
		{
			axios.get('api/getProviders').then((res)=>{
				this.providers = res.data.data;
			})
		},
		load()
		{
			$('#loader').css('display','none');
			$('#app').css('display','block');
		},
		exportModal()
		{
			$('#exportModal').modal();
		},
		exportIps()
		{
			$('#exportIps').text('wait...');
			axios.post('api/exportIps',
				{'startDate':this.exportStart,'endDate':this.exportEnd},
				{'responseType':'blob'})
			.then((res)=>
			{
				$('#exportIps').text('export');
				const url = window.URL.createObjectURL(new Blob([res.data]));
				const link = document.createElement('a');
				link.href = url;
				link.setAttribute('download', 'ips_'+moment().format('Y-m-d')+'.xlsx');
				document.body.appendChild(link);
				link.click();
			})
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
		currencyCode(currency)
		{
			return this.symbols.find((s)=>(s.currency == currency)).code;
		},
		getCountryCode(ips)
		{
			ips.forEach((ip)=>{
				axios.get(`http://ip-api.com/json/${ip.ip}`)
				.then((res)=>{
					ip.countryCode = res.data.countryCode.toLowerCase();
				})
				.catch((err)=>{
					console.log(err);
				})
			})
			
		}
		
		

	},
	mounted : function(){
		this.filterIps();
		this.getServers();
		this.getProviders();
		this.initDateRangePicker();
	}
});