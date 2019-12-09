var app = new Vue({
	el : '#app',
	data : {
		newUser : {},
		users : [],
		selectedUser : {'group':{}},
		filter : {limit:10,'group_id':'','username':'','name':'','email':'','active':'','role':'',sortObject : {'champ':'username','order':'asc'},'created_by':'',},
		pagination : {},
		roles:[],
		limits : limits,
		username : {'champ':'username','order':'asc'},
		role : {'champ':'role','order':'asc'},
		f_name : {'champ':'f_name','order':'asc'},
		email : {'champ':'email','order':'asc'},
		groups : [],
	},
	methods : {
		createUser(){
			$('#createUser').modal();
		},
		storeUser(){
			axios.post('api/storeUser',this.newUser).then((res)=>{
				this.filterUsers();
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
				this.newUser = {};
				$('#createUser').modal('hide');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		filterUsers(url){
			this.users = [];
			$('#spin').toggle();
			var url = url || 'api/filterUsers';
			axios.post(url,this.filter).then((res)=>{
				this.users = res.data.data;
				$('#spin').toggle();
				this.load();
				this.pagination = {
					'next':res.data.links.next,
					'prev':res.data.links.prev,
					'currentPage':res.data.meta.current_page,
					'lastPage':res.data.meta.last_page,
					'path':res.data.meta.path,
					'first':res.data.links.first,
					'last':res.data.links.last,
				}
			})
		},
		refreshUsers(){
			this.filter = {limit:10,'group_id':'','username':'','name':'','email':'','active':'','role':'',sortObject : {'champ':'username','order':'desc'},'created_by':'',};
			this.filterUsers();
			$.notify.defaults({'autoHideDelay':1200});
			$.notify('Operation done Successfully','success');
			
		},
		editUser(user){
			this.selectedUser = user;
			$('#editUser').modal();
		},
		updateUser(user){
			axios.put('api/updateUser/'+user.username,this.selectedUser).then((res)=>{
				this.filterUsers(this.pagination.path+'?page='+this.pagination.currentPage);
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
				this.selectedUser = {'group':{'id':''}};
				$('#editUser').modal('hide');
			}).catch((err)=>{
				$.notify(err);
			})
		},
		deleteUser(user){
			if(!confirm('Are you certain you want to delete the user ?')){
				this.selectedUser = {'group':{'id':''}};
				$('#editUser').modal('hide');
				return;
			}
			axios.delete('api/deleteUser/'+user.id).then((res)=>{
				$.notify.defaults({'autoHideDelay':1200});
				this.filterUsers();
				$.notify('Operation done Successfully','success');
				this.selectedUser = {'group':{'id':''}};
				$('#editUser').modal('hide');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		sort(champ)
		{
			if(champ == 'username')
			{
				this.filter.sortObject.champ = this.username.champ,
				this.username.order == 'asc' ? this.filter.sortObject.order = 'desc' : this.filter.sortObject.order = 'asc';
				this.filter.sortObject.order == 'asc' ? this.username.order = 'asc' : this.username.order = 'desc';
			}
			if(champ == 'role')
			{
				this.filter.sortObject.champ = this.role.champ,
				this.role.order == 'asc' ? this.filter.sortObject.order = 'desc' : this.filter.sortObject.order = 'asc';
				this.filter.sortObject.order == 'asc' ? this.role.order = 'asc' : this.role.order = 'desc';
			}
			if(champ == 'name')
			{
				this.filter.sortObject.champ = this.f_name.champ,
				this.f_name.order == 'asc' ? this.filter.sortObject.order = 'desc' : this.filter.sortObject.order = 'asc';
				this.filter.sortObject.order == 'asc' ? this.f_name.order = 'asc' : this.f_name.order = 'desc';
			}
			if(champ == 'email')
			{
				this.filter.sortObject.champ = this.email.champ,
				this.email.order == 'asc' ? this.filter.sortObject.order = 'desc' : this.filter.sortObject.order = 'asc';
				this.filter.sortObject.order == 'asc' ? this.email.order = 'asc' : this.email.order = 'desc';
			}
			this.filterUsers();
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
		},
		getRoles(){
			axios.get('api/getRoles').then((res)=>{
				console.log(res.data);
				this.roles = res.data;
			})
		}
		
	},
	mounted : function(){
		this.filterUsers();
		this.getGroups();
		this.getRoles();
	}
});