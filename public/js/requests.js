var app = new Vue({
	el : '#app',
	data : {
		types : ['New Server','Server Cancelation','Ip Range','Sponsor','Boxes','Other'],
		priorities : ['Low','Medium','High','Meduim'],
		newRequest : {'type':'New Server','priority':'Low'},
		filter:{'limit':10,'group_id':'','subject':'','type':'','user_id':'','created_at':'','priority':'','_status':'',sortObject:{'champ':'id','order':'desc'},},
		requests : [],
		request : '',
		status : {'status':[],'request':''},
		statusElements : ['New','Inprocess','Solved','Other','NotFound'],
		newStatus : {'newStatus':{},'request':''},
		pagination : {},
		limits : limits,
		sSubject : {'champ':'subject','order':'asc'},
		groups:[],
	},
	methods : {
		createRequest(){
			$('#createRequest').modal();
		},
		storeRequest(){
			axios.post('api/storeRequest',this.newRequest).then((res)=>{
				this.filterRequests()
				notification.getUnreadNotificationsCount();
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
				newRequest = {'type':'New Server','user_id':$('#user_id').val(),'group_id':$('#group_id').val()};
				$('#createRequest').modal('hide');

			}).catch((err)=>{
				$.notify(err);
			})
		},
		
		showRequest(request){
			this.request = request.request;
			$('#showRequest').modal();
		},
		setStatusColor(status){
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
			else if(status == 'NotFound' || status=='Not found')
			{
				return 'btn-danger';
			}
		},
		showStatus(request){
			this.status={'status':request.allStatus,'request':request};
			$('#showStatus').modal();
		},
		createStatus(request){
			this.newStatus.request = request;
			$('#createStatus').modal();
		},
		storeStatus(){
			axios.post('api/storeStatus/'+this.newStatus.request.id,this.newStatus).then((res)=>{
				this.filterRequests()
				notification.getUnreadNotificationsCount();
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
				$('#createStatus').modal('hide');
				$('#showStatus').modal('hide');
			}).catch((err)=>{
				$.notify(err);
			})
		},
		refreshRequests(){
			this.filter = {'limit':10,'group_id':'','subject':'','type':'','user_id':'','created_at':'','priority':'','_status':'',sortObject:{'champ':'id','order':'desc'},};
			this.filterRequests();
			$.notify.defaults({'autoHideDelay':1200});
			$.notify('Operation done Successfully','success');
			
		},
		filterRequests(url){
			this.requests = [];
			$('#spin').toggle();
			var url = url || 'api/filterRequests';
			axios.post(url,this.filter).then((res)=>{
				this.requests = res.data.data;
				console.log(res.data.data);
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
			if(champ == 'subject')
			{
				this.filter.sortObject.champ = this.sSubject.champ;
				this.filter.sortObject.order = this.toggleOrder(this.sSubject);
			}
			this.filterRequests();
		},
		getGroups()
		{
			axios.get('api/getGroups').then((res)=>{
				this.groups = res.data.data;
			})
		},
		load()
		{
			$('#loader').css('display','none');
			$('#app').css('display','block');
		}
		
	},
	mounted : function(){
		this.filterRequests();
		this.getGroups();
	}
})