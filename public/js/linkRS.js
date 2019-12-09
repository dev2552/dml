var app = new Vue({
	el : '#app',
	data : {
		newModel : {},
		records : [],
		pagination : {},
		filter:{'criteria':''},
		servers : [],
		requests : [],
	},
	methods : {
		save(){
			axios.post('api/linkRS/save',this.newModel).then((res)=>{
				this.newModel = {};
				this.paginate();
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		paginate(url){
			var url = url || 'api/linkRS/paginate';
			axios.post(url,this.filter).then((res)=>{
				this.records = res.data.data;
				this.load();
				this.pagination={
					'next':res.data.next_page_url,
					'prev':res.data.prev_page_url,
					'currentPage':res.data.current_page,
					'lastPage':res.data.last_page,
					'path':res.data.path,
				}
			})
		},
		load()
		{
			$('#loader').css('display','none');
			$('#app').css('display','block');
		},
		getServers()
		{
			axios.get('api/getAllServers').then((res)=>{
				this.servers = res.data.data;
			})
		},
		getRequests()
		{
			axios.get('api/getRequests').then((res)=>
			{
				this.requests = res.data.data;
			})
		}
		
	},
	mounted : function(){
		this.paginate();
		this.getServers();
		this.getRequests();
	}
})