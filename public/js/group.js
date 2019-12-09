var app = new Vue({
	el : '#app',
	data : {
		groups : [],
		pagination : {},
		newModel : {'name':''},
		selectedGroup : {},
	},
	mounted : function(){
		this.filterGroups();
	},
	methods:{
		addGroup(){
			axios.post('api/addGroup',this.newModel).then((res)=>{
				this.newModel = {'name':''};
				this.filterGroups();
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			});
		},
		filterGroups(link){
			this.groups = [];
			$('#spin').toggle();
			var url = link || 'api/filterGroups';
			axios.post(url,{'filter':this.newModel.name}).then((res)=>{
				this.groups = res.data.data;
				$('#spin').toggle();
				this.load();
				this.pagination={
						'next':res.data.links.next,
						'prev':res.data.links.prev,
						'currentPage':res.data.meta.current_page,
						'lastPage':res.data.meta.last_page,
						'path':res.data.meta.path
					}

			}).catch((err)=>console.log(err));
		},
		updateGroup(){
			axios.put('api/updateGroup/'+this.selectedGroup.id,this.selectedGroup).then((res)=>{
				this.filterGroups(this.pagination.path+'?page='+this.pagination.currentPage);
				$('#editGroup').modal('hide');
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			});
		},
		refreshGroups(){
			this.newModel = {'name':''};
			this.filterGroups();
			$.notify.defaults({'autoHideDelay':1200});
			$.notify('Operation done Successfully','success');
			
		},
		
		editGroup(group){
			this.selectedGroup = group;
			$('#editGroup').modal();
		},
		deleteGroup(){
			if(!confirm('delete group ?'))
			{
				$('#editGroup').modal('hide');
				return;
			}
			axios.delete('api/deleteGroup/'+this.selectedGroup.id).then((res)=>{
				this.filterGroups();
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
				$('#editGroup').modal('hide');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		load()
		{
			$('#loader').css('display','none');
			$('#app').css('display','block');
		}

	}
});