var app = new Vue({
	el : '#app',
	data : {
		domainToClear : '',
		domains : [],
		registrars : [],
		selectedRegistrar : '',
		domainsList : [],
		selectedDomains : [],
		selectedServer : '',
		servers : [],
		ipList : [],
		selectedIp : [],
		subDomains : [],
		DKIM : '',
	},
	methods : {
		load()
		{
			$('#loader').css('display','none');
			$('#app').css('display','block');
		},
		clearDomain()
		{
			if(this.domainToClear == '')
			{
				return;
			}
			if(!confirm('clear this domain ?'))
			{
				this.domainToClear = '';
				return;
			}
			$('#loader').css('display','block');
			$('#app').css('display','none');
			axios.post('api/clearDomain/'+this.domainToClear).then((res)=>
			{
				$.notify('Operation done Successfully','success')
				$(".domains_select").val(null).trigger("change");
				this.load();

			}).catch((err)=>
			{

				this.load();

				$.notify(JSON.parse(err.request.response).message,'error');
			})
		},
		getDomains()
		{
			axios.get('api/getDomains').then((res)=>{
				this.domains = res.data.data;
			})
		},
		getRegistrars()
		{
			axios.get('api/getRegistrars').then((res)=>
			{
				this.registrars = res.data.data;
			})
		},
		getServers()
		{
			axios.get('api/getAllServers').then((res)=>
			{
				this.servers = res.data.data;
			})
		},
		setDomainsList()
		{
			if(this.selectedRegistrar == '')
			{
				this.domainsList = [];
				this.selectedDomains = [];
				return;
			}
			axios.get('api/getDomainsList/'+this.selectedRegistrar).then((res)=>
			{
				this.domainsList = res.data.data;
			})
		},
		toDomainsList(n,domain)
		{
			if(n == 2)
			{
				this.selectedDomains.push(domain);
				this.domainsList = this.domainsList.filter((d)=>d.id != domain.id);
			}
			else if(n == 1)
			{
				this.domainsList.push(domain);
				this.selectedDomains = this.selectedDomains.filter((d)=>d.id != domain.id);
			}
			
		},
		setIpList()
		{
			this.ipList = [];
			if(this.selectedServer == '')
			{
				return;
			}
			if(!this.selectedDomains[0])
			{
				$.notify('Choose a Domain !','error');
				return;
			}
			var ips = [];
			axios.post('api/setIpList',{'server_id':this.selectedServer,'domain_id':this.selectedDomains[0].id}).then((res)=>
			{
				var domain = res.data.domain;
				var main_ip = res.data.main_ip;
				var ips = res.data.ips.filter((i)=>i.ip != main_ip);

				this.ipList.push({'ip':main_ip,'subDomain':domain.domain,'name':'@'});

				for(i=0;i<ips.length;i++)
				{
					var subDomain_ = this.getSubDomain(domain);
					this.ipList.push({'ip':ips[i].ip,'subDomain':subDomain_.subDomain,'name':subDomain_.name})
				}
			})

		},
		updateDNS()
		{
			if(this.DKIM == "")
			{
				alert('Set the value of DKIM key!');
				return;
			}
			$('#loader').css('display','block');
			$('#app').css('display','none');
			axios.post('api/updateDNS',{'server_id':this.selectedServer,
				'registrar_id':this.selectedRegistrar,
				'ipList':this.ipList,
				'DKIM':this.DKIM})
			.then((res)=>{
					this.load();
					$.notify('DNS updated Successfully','success');
					this.domainsList = [];
					this.selectedDomains = [];
					this.ipList = [];
					this.DKIM = "";
					$(".servers_select").val(null).trigger("change");
					$(".registrars_select").val(null).trigger("change");

			}).catch((err)=>
			{
				this.load();

				$.notify(JSON.parse(err.request.response).message,'error');
			})
		},
		getSubDomain(domain)
		{
			var randomWord = wordList[Math.floor(Math.random()*wordList.length)];
			return {'subDomain':randomWord+'.'+domain.domain,'name':randomWord};
		}
	},
	mounted : function()
	{
		//this.getDomains();
		//this.getRegistrars();
		//this.getServers();
		this.load();
	}
})