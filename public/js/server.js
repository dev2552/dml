//https://www.facebook.com/evelyn.martinezurra

var app = new Vue({
	el : '#app',
	data : {
		newModel : {
			'date_order':moment().format('YYYY-MM-DD'),
			'date_delivred':moment().format('YYYY-MM-DD'),
			'date_purchase':moment().format('YYYY-MM-DD'),
			'date_expiration':moment().add(1,'month').format('YYYY-MM-DD'),
			'main_ip':'',
			'listIps':{'provider_id':0,'ips':'','range':'-','netMask':'-','price':0,'currency':currencies[0],'type':'IPV4'},
			'provider_id':'',
			'main_domain':'',
			'currency':currencies[0],
			'is_active':1,
		},
		servers : [],
		filter : {'limit':limits[0],'group_id':'','s_name':'','provider_id':'','main_ip':'','main_domain':'','numberIps':'','date_purchase':'','price':'','last_status':'',sortObject:{'champ':'id','order':'desc'},'currency':'','created_by':''},
		pagination : {},
		status : ['pen','inst','prod','issue','toRet','ret','canc','susp','block'],
		selectedServer : {'group':{},'domain':{},'provider':{},'status':{},'ips':{},'subDomains':[]},
		listStatus : [],
		newStatus : {'data':{},'server':{}},
		selectedProvider : {},
		limits : limits,
		sName : {'champ':'s_name','order':'asc'},
		sPrice : {'champ':'price','order':'asc'},
		currencies : currencies,
		countries : countries,
		newProvider : {'status':'Active'},
		newDomain : {'datex':moment().add(1,'year').format('YYYY-MM-DD'),
		'entered':moment().format('YYYY-MM-DD'),
		'status':'NEW',
		'currency':currencies[0]},
		providerTypes : ['Principal','Reseller','Canceled'],
		providers : [],
		groups : [],
		domains : [],
		allDomains : [],
		ipTypes : ['IPV4','IPV6','FailOver','Subnet'],
		list : [],
		dateExpiration : moment().add(1,'month').format('YYYY-MM-DD'),
		registrars : [],
		serversToReturn : [],
		serversRenewal : [],
		subDomains : [],
		exportGroup : {},
		exportStart : moment().subtract(29,'days'),
		exportEnd : moment().add(1,'minute'),
		storedProvider : {},
		symbols : data,
		sshType : "",
	},
	mounted : function(){

		this.getServers();
		this.getProviders();
		this.getGroups();
		this.getDomains();
		this.getAllDomains();
		this.getRegistrars();
		this.initDateRangePicker();
	},
	computed:{
		getSshType:function(){
			return this.sshType;
		}
	},
	methods : {
		createServer()
		{
			$('#addServer').modal();
		},
		storeServer()
		{
			if(!this.newModel.listIps.ips || this.newModel.listIps.ips.length == 0)
			{
				$.notify('Ip List is Empty!');
				return;
			}
			if(!this.newModel.main_domain)
			{
				$.notify('Main Domain is Empty!');
				return;
			}
			axios.post('api/storeServer',this.newModel).then((res)=>{
				this.getServers();
				this.getDomains();
				notification.getUnreadNotificationsCount();
				$.notify('Operation done Successfully','success');
			}).catch((err)=>{
				if(err.request)	$.notify(JSON.parse(err.request.response).message);
			});
		},
		getServers(url)
		{
			this.servers = [];
			$('#spin').toggle();
			var url = url || 'api/getServers';
			axios.post(url,this.filter).then((res)=>{
				this.servers = res.data.data;
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
					console.log(this.pagination);
			})

		},
		setColor(status){	
			if(status == 'pen')
			{
				return 'btn-warning';
			}
			else if(status == 'inst')
			{
				return 'btn-primary';
			}
			else if(status == 'prod')
			{
				return 'btn-success';
			}
			else if(status == 'issue')
			{
				return 'btn-warning';
			}
			else if(status == 'toRet')
			{
				return 'btn-inverse-default';
			}
			else if(status=='ret'){
				return 'btn-black';
			}
			else if(status == 'canc'){
				return 'btn-danger';
			}
			else if(status == 'susp'){
				return 'btn-info';
			}
			else if(status == 'block')
			{
				return 'btn-black';
			}
		},
		setStatus(status){	
			if(status == 'pen')
			{
				return 'Pending';
			}
			else if(status == 'inst')
			{
				return 'Installing';
			}
			else if(status == 'prod')
			{
				return 'In production';
			}
			else if(status == 'issue')
			{
				return 'In production with issue';
			}
			else if(status == 'toRet')
			{
				return 'To return';
			}
			else if(status=='ret'){
				return 'Returned';
			}
			else if(status == 'canc'){
				return 'Canceled';
			}
			else if(status == 'susp'){
				return 'Suspended';
			}
			else if(status == 'block')
			{
				return 'Block';
			}
		},
		showStatus(server){
			this.listStatus = server.listStatus;
			this.newStatus.server = server;
			$('#showStatus').modal();
		},
		editServer(server){
			this.selectedServer = server;
			console.log(this.selectedServer);
			$('.edit_server_provider_select').append(new Option(server.provider.name,server.provider.id,false,true)).trigger('change');
			$('.edit_server_domain_select').append(new Option(server.main_domain,server.main_domain,false,true)).trigger('change');
			$('#editServer').modal();
		},
		updateServer(){
			axios.put('api/updateServer/'+this.selectedServer.id,this.selectedServer).then((res)=>{
				this.getServers(this.pagination.path+'?page='+this.pagination.currentPage);
				$.notify('Operation done Successfully','success');
				$('#editServer').modal('hide');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		createStatus(){
			$('#createStatus').modal();
		},
		storeStatus(){
			axios.post('api/addToServer/'+this.newStatus.server.id,this.newStatus.data).then((res)=>{
				this.getServers(this.pagination.path+'?page='+this.pagination.currentPage);
				$.notify('Operation done Successfully','success');
				$('#createStatus').modal('hide');
				$('#showStatus').modal('hide');
			}).catch((err)=>{
				$.notify(err);
			})
		},
		showServerDetails(server){
			this.selectedServer = server;
			this.subDomains = this.selectedServer.subDomains;
			$('#serverDetails').modal();
			console.log(server);
		},
		showProviderDetails(provider){
			this.selectedProvider = provider;
			$('#providerDetails').modal();
		},
		toggle(id){
			$('#'+id).toggle();
		},
		getIdForDates(server){
			return 'dates_'+server.id;
		},
		getIdForConfig(server){
			return 'config_'+server.id;
		},
		refreshServers(){
			this.filter = {'limit':limits[0],'group_id':'','s_name':'','provider_id':'','main_ip':'','main_domain':'','numberIps':'','date_purchase':'','price':'','last_status':'',sortObject:{'champ':'id','order':'desc'},'currency':'','created_by':''};
			this.getServers();
			$.notify('Operation done Successfully','success');
			
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
			if(champ == 'price')
			{
				this.filter.sortObject.champ = this.sPrice.champ;
				this.filter.sortObject.order = this.toggleOrder(this.sPrice);
			}
			this.getServers();
		},
		duplicateServer()
		{
			this.newModel = this.selectedServer;
			console.log(this.newModel);
			if( this.newModel.ssh_key ) this.sshType='key';
			if( this.newModel.ssh_pwd_default ) this.sshType='Normal';
			this.newModel.listIps = {'provider_id':this.selectedServer.provider.id,'ips':'','ip_range':'-','netmask':'-','price':0,'currency':currencies[0],'type':'IPV4'};
			$('.add_server_provider_select').append(new Option(this.newModel.provider.name,this.newModel.provider.id,false,true)).trigger('change');
			$('.add_server_domain_select').append(new Option(this.newModel.main_domain,this.newModel.main_domain,false,true)).trigger('change');
			$('#editServer').modal('hide');
			$('#addServer').modal();
		},
		deleteServer()
		{
			if(!confirm('delete server ?'))
			{
				$('#editServer').modal('hide');
				return;
			}
			axios.delete('api/deleteServer/'+this.selectedServer.id).then((res)=>{
				this.getServers();
				$.notify('Operation done Successfully','success');
				$('#editServer').modal('hide');

			}).catch((err)=>{
				if(err.request)	$.notify(JSON.parse(err.request.response).message);
			})
		},
		createProvider()
		{
			$('#createProvider').modal();
		},
		createDomain()
		{
			$('#createDomain').modal();
		},
		storeProvider(){
			axios.post('api/storeProvider',this.newProvider).then((res)=>{
				this.storedProvider = res.data.data;
				$('.add_server_provider_select').append(new Option(this.storedProvider.name,this.storedProvider.id,false,true)).trigger('change');
				$('#createProvider').modal('hide');
				
			}).catch((err)=>{

				if(err.request) $.notify(JSON.parse(err.request.response).message);
			})
		},
		storeDomain()
		{
			axios.post('api/addOneDomain',this.newDomain).then((res)=>{
				notification.getUnreadNotificationsCount();
				$('.add_server_domain_select').append(new Option(res.data.data.domain,res.data.data.domain,false,true)).trigger('change');
				$('#createDomain').modal('hide');
			}).catch((err)=>{
				if(err.request) $.notify(JSON.parse(err.request.response).message);
			})
		},
		getProviders()
		{
			axios.get('api/getProviders').then((res)=>{
				this.providers = res.data.data;
			})
		},
		getGroups()
		{
			axios.get('api/getGroups').then((res)=>{
				this.groups = res.data.data;
			})
		},
		getDomains()
		{
			axios.get('api/getNewDomains').then((res)=>{
				this.domains = res.data.data;
			})
		},
		getAllDomains()
		{
			axios.get('api/getDomains').then((res)=>{
				this.allDomains = res.data.data;
			})
		},
		createIp()
		{
			$('#createIps').modal();
			

		},
		closeCreateIps()
		{
			$('#createIps').modal('hide');
			this.newModel.main_ip = this.newModel.listIps.ips.split("\n")[0];
		},
		toggleServer(event,server)
		{
			if(event.target.checked)
			{
				this.list.push(server);
			}
			else
			{
				this.list = this.list.filter((s)=>s.id != server.id);
			}
		},
		renewalServers()
		{
				axios.post('api/renewalServers',this.serversRenewal).then((res)=>{
				this.getServers(this.pagination.path+'?page='+this.pagination.currentPage);
				this.list = [];
				notification.getUnreadNotificationsCount();
				$('#renewalServers').modal('hide');
				$.notify('Operation done Successfully','success');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		toggleStatus(status)
		{
			axios.post('api/toggleStatus',{'list':this.list,'status':status,'dateExpiration':this.dateExpiration}).then((res)=>{
				this.getServers(this.pagination.path+'?page='+this.pagination.currentPage);
				this.list = [];
				$('#toggleStatusModal').modal('hide');
				$('#toReturn').modal('hide');
				$.notify('Operation done Successfully','success');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		toggleStatusModal()
		{
			$('#toggleStatusModal').modal();
		},
		toggleGroup(event)
		{
			axios.post('api/toggleGroup',{'list':this.list,'groupId':event.target.value}).then((res)=>{
				this.getServers(this.pagination.path+'?page='+this.pagination.currentPage);
				notification.getUnreadNotificationsCount();
				this.list = [];
				$('#toggleStatusModal').modal('hide');
				$.notify('Operation done Successfully','success');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		toReturnModal()
		{
			this.serversToReturn = [];
			for(i=0;i<this.list.length;i++)
			{
				var item = {};
				item.id = this.list[i].id;
				item.s_name = this.list[i].s_name;
				item.date_expiration = moment(new Date(this.list[i].date_expiration)).format('YYYY-MM-DD');
				item.main_ip = this.list[i].main_ip;
				this.serversToReturn.push(item);
			}
			$('#toReturn').modal();
		},
		toggleProvider(x)
		{
			axios.get('api/providers/'+x).then((res)=>{
				$('.create_ip_provider_select').append(new Option(res.data.data.name,res.data.data.id,false,true)).trigger('change');
			});
			
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
		toReturn()
		{
			console.log(this.serversToReturn);
			axios.post('api/serversToReturn',this.serversToReturn).then((res)=>{
				this.getServers(this.pagination.path+'?page='+this.pagination.currentPage);
				this.list = [];
				$('#toReturn').modal('hide');
				$('#toggleStatusModal').modal('hide');
				$.notify('Operation done Successfully','success');
			})
		},
		renewalServersModal()
		{
			this.serversRenewal = [];
			for(i=0;i<this.list.length;i++)
			{
				var item = this.list[i];
				this.serversRenewal.push(item);
			}
			$('#renewalServers').modal();
		},
		copierSubDomains()
		{
			$('.c_subDomain')[0].execCommand('copy');
		},
		exportModal()
		{
			$('#exportModal').modal();
		},
		exportServers()
		{
			$('#exportServers').text('wait...');
			axios.post('api/exportServers',
				{'startDate':this.exportStart,'endDate':this.exportEnd,'groupId':this.exportGroup.id},
				{'responseType':'blob'})
			.then((res)=>
			{
				$('#exportServers').text('export');
				const url = window.URL.createObjectURL(new Blob([res.data]));
				const link = document.createElement('a');
				link.href = url;
				var name =  'servers_'+moment().format('Y-m-d');
				if(this.exportGroup.name) name = name + '_group_' + this.exportGroup.name;
				name = name + '.xlsx';
				link.setAttribute('download', name);
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
		numberIpsDetail(x)
		{
			$('#'+x).toggle()
		},
		datesDetail(x)
		{
			$('#'+x).toggle()
		},
		configDetail(x)
		{
			$('#'+x).toggle()
		},
		currencyCode(currency)
		{
			var symbol = this.symbols.find((s)=>(s.currency == currency));
			if(symbol) return symbol.code;
		},

		
		
	}
});