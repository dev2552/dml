var app = new Vue(
{
	el : '#app',
	data : 
	{
		subDomains : [],
		filter : {'ip_id':'','subdomain':'','domain_id':'','is_active':'','created':'','limit':limits[0]},
		servers : [],
		pagination : {},
		limits : limits,
		selectedSubDomain : {},
		ips : [],
	},
	methods : 
	{
		load()
		{
			$('#loader').css('display','none');
			$('#app').css('display','block');
		},
		getServers()
		{
			axios.get('api/getAllServers').then((res)=>
			{
				this.servers = res.data.data;
			})
		},
		filterSubDomains(url)
		{
			this.subDomains = [];
			$('#spin').toggle();
			var url = url || 'api/filterSubDomains';
			axios.post(url,this.filter).then((res)=>
			{
				this.subDomains = res.data.data;
				$('#spin').toggle();
				this.pagination={
						'next':res.data.links.next,
						'prev':res.data.links.prev,
						'currentPage':res.data.meta.current_page,
						'lastPage':res.data.meta.last_page,
						'path':res.data.meta.path,
						'first':res.data.links.first,
						'last':res.data.links.last
				};
			})
		},
		refreshSubDomains()
		{
			this.filter =  {'ip_id':'','subdomain':'','domain_id':'','is_active':'','created':'','limit':limits[0]};
			this.filterSubDomains();
			$.notify('Operation done Successfully','success');
		},
		editSubDomain(subDomain)
		{
			this.selectedSubDomain = subDomain;
			$('#editSubDomain').modal();
		},
		listIps()
		{
			axios.post('api/listIps',this.selectedSubDomain).then((res)=>
			{
				this.ips = res.data;
			})
		},
		updateSubDomain()
		{
			axios.put('api/updateSubDomain/'+this.selectedSubDomain.id,this.selectedSubDomain).then((res)=>
			{
				$('#editSubDomain').modal('hide');
				this.filterSubDomains(this.pagination.path+'?page='+this.pagination.currentPage);
				$.notify('Operation done Successfully','success');
			})
		}
	},
	mounted : function()
	{
		this.getServers();
		this.filterSubDomains();
		this.load();
	}
})