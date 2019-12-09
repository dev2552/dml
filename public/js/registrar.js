
	var app = new Vue({
		el:'#app',
		data : {
			registrars : [],
			filter : {'limit':10,'name':'','company':'','website':'','eml_contact':'','is_active':'',sortObject:{'champ':'id','order':'desc'},'created_by':''},
			pagination:{},
			model : {},
			newModel : {'enable':false},
			limits : limits,
			sName : {'champ':'name','order':'asc'},
			sCompany : {'champ':'company','order':'asc'},
			sWebsite : {'champ':'website','order':'asc'},
			sEmail : {'champ':'eml_contact','order':'asc'},
		},
		mounted : function()
		{
			this.fetchData();
		},
		methods:{
			fetchData(link){
				this.registrars = [];
				$('#spin').toggle();
				var url = link || 'api/fetchRegistrars';
				axios.post(url,this.filter).then((res)=>{
					this.registrars = res.data.data;
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
			addRegistrar(){
				axios.post('api/addRegistrar',this.newModel).then((res)=>{
					$('#addRegistrar').modal('hide');
					$('#alert').show();
					this.newModel={'enable':false};
					this.fetchData();
					$.notify('Operation done Successfully','success');
				}).catch((err)=>{
					if(err.request) $.notify(JSON.parse(err.request.response).message);
				})
			},
			editRegistrar(registrar){
				this.model = registrar;
				$('#editRegistrar').modal();
			},
			updateRegistrar(registrar){
				axios.put('api/updateRegistrar/'+registrar.id,this.model).then((res)=>{
					$('#editRegistrar').modal('hide');
					this.fetchData(this.pagination.path+'?page='+this.pagination.currentPage);
					$.notify('Operation done Successfully','success');
					
				}).catch((err)=>{
					$.notify(JSON.parse(err.request.response).error);
					
				})
			},
			removeRegistrar(registrar){
				if(!confirm('delete Registrar ?'))
				{
					$('#editRegistrar').modal('hide');
					return;
				}
				axios.delete('api/removeRegistrar/'+registrar.id).then((res)=>{
					$('#editRegistrar').modal('hide');
					this.fetchData();
					$.notify('Operation done Successfully','success');
				}).catch((err)=>{
					$.notify(JSON.parse(err.request.response).error);
				})
			},
			filterRegistrars(){
				this.fetchData();
			},
			createRegistrar(){
				$('#addRegistrar').modal();
			},
			refreshData(){
				this.filter = {'limit':10,'name':'','company':'','website':'','eml_contact':'','is_active':'',sortObject:{'champ':'id','order':'desc'},'created_by':''};
				this.fetchData();
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
					o.ordrr = 'asc';
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
				if(champ == 'company')
				{
					this.filter.sortObject.champ = this.sCompany.champ;
					this.filter.sortObject.order = this.toggleOrder(this.sCompany);
				}
				if(champ == 'website')
				{
					this.filter.sortObject.champ = this.sWebsite.champ;
					this.filter.sortObject.order = this.toggleOrder(this.sWebsite);
				}
				if(champ == 'eml_contact')
				{
					this.filter.sortObject.champ = this.sEmail.champ;
					this.filter.sortObject.order = this.toggleOrder(this.sEmail);
				}
				this.filterRegistrars();
			},
			load()
			{
				$('#loader').css('display','none');
				$('#app').css('display','block');
			}
			
		},
	})
