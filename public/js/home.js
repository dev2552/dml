var app = new Vue({
	el : '#app',
	data : {
		serversToReturn : [],
		unreadNotificationsCount : 0,
		serversToReturnPagination : {},
		serversInProductionWithIssue : [],
		serversInProductionWithIssuePagination : {},
		serversInstalling : [],
		serversInstallingPagination : {},
		serversExpiration : [],
		serversExpirationPagination : {},
		requests : [],
		requestsPagination : {},
		servers : [],
		paymentElements : [],
		currencies : currencies,
		symbols : data,
		selectedServer : {'group':{},'domain':{},'provider':{},'status':{}},
		selectedProvider : {},
		selectedRequest : {},
		serverGroupsId : [],
		payment : {},
		requestFilter : null,
		paymentsLoading:true,
		serversExpirationLoading:true,
		serversToReturnLoading:true,
		serversInProductionWithIssueLoading:true,
		serversInstallingLoading:true,
		requestsLoading:true,
		serversLoading:true,
	},
	methods : {

		getServersToReturn(url){
			url = url || 'api/servers/return'
			axios.get(url).then((res)=>{
				this.serversToReturn = res.data.data;
				this.serversToReturnPagination = {
					'next':res.data.links.next,
					'prev':res.data.links.prev,
					'currentPage':res.data.meta.current_page,
					'lastPage':res.data.meta.last_page,
					'path':res.data.meta.path,
					'first':res.data.links.first,
					'last':res.data.links.last};
				this.serversToReturnLoading=false;	
			});
		},
		getServersInProductionWithIssue(url){
			url = url || 'api/servers/production/issue';
			axios.get(url).then((res)=>{
				this.serversInProductionWithIssue = res.data.data;
				this.serversInProductionWithIssuePagination = {
					'next':res.data.links.next,
					'prev':res.data.links.prev,
					'currentPage':res.data.meta.current_page,
					'lastPage':res.data.meta.last_page,
					'path':res.data.meta.path,
					'first':res.data.links.first,
					'last':res.data.links.last};
				this.serversInProductionWithIssueLoading=false;	
			});
		},
		getServersInstalling(url){
			url = url || 'api/servers/installing';
			axios.get(url).then((res)=>{
				this.serversInstalling = res.data.data;
				this.serversInstallingPagination =  {
					'next':res.data.links.next,
					'prev':res.data.links.prev,
					'currentPage':res.data.meta.current_page,
					'lastPage':res.data.meta.last_page,
					'path':res.data.meta.path,
					'first':res.data.links.first,
					'last':res.data.links.last};
				this.serversInstallingLoading=false;	
			})
		},
		getServersExpiration(url){
			url = url || 'api/servers/expiration';
			axios.get(url).then((res)=>{
				this.serversExpiration = res.data.data;
				this.serversExpirationPagination = {
					'next':res.data.links.next,
					'prev':res.data.links.prev,
					'currentPage':res.data.meta.current_page,
					'lastPage':res.data.meta.last_page,
					'path':res.data.meta.path,
					'first':res.data.links.first,
					'last':res.data.links.last};
				this.serversExpirationLoading=false;	
			})
		},
		getRequests(url){
			url = url || 'api/requests';
			axios.post(url,{'requestFilter':this.requestFilter}).then((res)=>{
				this.requests = res.data.data;
				this.requestsPagination = {
					'next':res.data.links.next,
					'prev':res.data.links.prev,
					'currentPage':res.data.meta.current_page,
					'lastPage':res.data.meta.last_page,
					'path':res.data.meta.path,
					'first':res.data.links.first,
					'last':res.data.links.last};
				this.requestsLoading=false;	
			})
		},
		color(status){
			if(status == 'new' || status == 'New')
			{
				return 'btn-warning';
			}
			else if(status == 'Inprocess')
			{
				return 'btn-info';
			}
			else if(status == 'Solved')
			{
				return 'btn-success';
			}
			else if(status == 'Other')
			{
				return 'btn-default';
			}
			else if(status == 'NotFound')
			{
				return 'btn-danger';
			}
		},
		getServers(){
			axios.get('api/servers').then((res)=>{
				this.servers = res.data;
				this.serversLoading=false;
			})
		},
		payments(){
			axios.get('api/payments').then((res)=>{
				this.paymentElements = res.data;
				this.paymentsLoading=false;
			})
		},
		currencyCode(currency)
		{
			return this.symbols.find((s)=>(s.currency == currency)).code;
		},
		expirationColor(days)
		{
			if(days < 3)
			{
				return 'badge-danger';
			}
			else if(days > 3 && days < 7)
			{
				return 'badge-warning';
			}
			else
			{
				return 'badge-primary';
			}
		},
		showServerDetails(server){
			this.selectedServer = server;
			$('#serverDetails').modal();
		},
		showProviderDetails(provider){
			this.selectedProvider = provider;
			$('#providerDetails').modal();
		},
		showRequestDetails(request)
		{
			this.selectedRequest = request;
			$('#requestDetails').modal();
		},
		load()
		{
			$('#loader').css('display','none');
			$('#app').css('display','block');
		},
		paymentDetail(groupName)
		{
			this.payment = this.paymentElements.find((el)=>el.groupName == groupName);
			$('#paymentDetail').modal();
		},
		setServerStatus(status){	
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
		
	},
	created:function(){
		this.servers['status'] = [];
		this.servers['counts'] = [];
		this.servers['groups'] = [];
	},
	mounted : function(){

		this.payments();
		this.getServersExpiration();
		this.getRequests();
		this.getServersToReturn();
		this.getServersInProductionWithIssue();
		this.getServersInstalling();
		this.getServers();
		

		this.load();
	}
})

