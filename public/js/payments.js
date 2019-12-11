var app = new Vue({
	el : '#app',
	data : {
		filter : {'start':moment().startOf("year"),'end':moment().add(1,'minute'),'limit':10,'type':'','group_id':'','total_price':'','server_id':'','domain_id':'','ip_id':'','created':'','payment_date':'','description':'',sortObject:{'champ':'id','order':'desc'},'currency':'','created_by':''},
		types : ['Server','Setup Fee','Ip','Domain','Transfer Fee','Inboxe','Other'],

		newPayment : {'payment_date':moment().format('YYYY-MM-DD'),'paymentable':{'id':''},
		'currency':currencies[0],
		'created':moment().format('YYYY-MM-DD'),
		'quantity':1,
		'unitPrice':1},

		currencies : currencies,
		payments : [],
		pagination:{},
		selectedPayment : {'group':{},'paymentable':{'id':''}},
		limits : limits,
		selectedServer : {'group':{},'domain':{},'provider':{},'status':{}},
		sPrice : {'champ':'total_price','order':'asc'},
		symbols : data,
		servers : [],
		domains : [],
		ips : [],
		groups:[],
		users : [],
		exportGroup : {},
		exportStart : moment().startOf("year"),
		exportEnd : moment().add(1,'minute'),
		exportCurrency : null,
	},
	methods : {
		initDateRangePicker(){
    		  $('#reportrange2').daterangepicker({
    		  	startDate:this.exportStart,
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
    		  },this.cb2);
    		 this.cb2(this.exportStart,this.exportEnd);

    		 $('#reportrange').daterangepicker({
    		  	startDate: this.filter.start,
    		  	endDate: this.filter.end,
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
    		 this.cb(this.filter.start,this.filter.end);
		},
		cb(start,end){
			$('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			this.filter.start = start;
			this.filter.end = end;
		},
		cb2(start,end){
			$('#reportrange2 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
			this.exportStart= start;
			this.exportEnd = end;
		},
			createPayment(){
			$('#createPayment').modal();
		},
		storePayment(){
			this.newPayment.totalPrice =this.newPayment.unitPrice*this.newPayment.quantity;
			axios.post('api/storePayment',this.newPayment).then((res)=>{
				this.filterPayments();
				notification.getUnreadNotificationsCount();
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
				$('#createPayment').modal('hide');
			}).catch((err)=>{
				$.notify(err.response.data.message);
			})
		},
		filterPayments(url){
			this.payments = [];
			$('#spin').toggle();
			var url = url || 'api/filterPayments';
			axios.post(url,this.filter).then((res)=>{
				this.payments = res.data.data;
				
				$('#spin').toggle();
				this.pagination = {
					'next':res.data.links.next,
					'prev':res.data.links.prev,
					'currentPage':res.data.meta.current_page,
					'lastPage':res.data.meta.last_page,
					'path':res.data.meta.path,
					'first':res.data.links.first,
					'last':res.data.links.last
				}
			}).catch((err)=>console.log(err));
		},
		refreshPayments(){
			this.filter = {'start':moment().startOf("year"),'end':moment().add(1,'minute'),'limit':10,'type':'','group_id':'','total_price':'','server_id':'','domain_id':'','ip_id':'','created':'','payment_date':'','description':'',sortObject:{'champ':'id','order':'desc'},'currency':'','created_by':''};
			this.initDateRangePicker();
			this.filterPayments();
			$.notify.defaults({'autoHideDelay':1200});
			$.notify('Operation done Successfully','success');
		},
		editPayment(payment){
			this.selectedPayment = payment;
			if(payment.server === null && payment.domain === null && payment.ip === null) 
			{
				$('#editPayment').modal();
				return;
			}
			if(payment.type == 'Server') $('.edit_payment_server_select').append(new Option(payment.server.s_name,payment.id,false,true)).trigger('change');
			//if(payment.type == 'Domain') $('.edit_payment_domain_select').append(new Option(payment.domain.domain,payment.id,false,true)).trigger('change');
			//if(payment.type == 'Ip') $('.edit_payment_ip_select').append(new Option(payment.ip.ip,payment.id,false,true)).trigger('change');
			$('#editPayment').modal();
		},
		updatePayment(){
			axios.put('api/updatePayment/'+this.selectedPayment.id,this.selectedPayment).then((res)=>{
				this.filterPayments(this.pagination.path+'?page='+this.pagination.currentPage);
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
				$('#editPayment').modal('hide');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		deletePayment(){
			if(!confirm('Delete Payment ?'))
			{
				$('#editPayment').modal('hide');
				return;
			}
			axios.delete('api/deletePayment/'+this.selectedPayment.id).then((res)=>{
				this.filterPayments();
				$.notify.defaults({'autoHideDelay':1200});
				$.notify('Operation done Successfully','success');
				$('#editPayment').modal('hide');
			}).catch((err)=>{
				console.log(err);
				$.notify(err);
			})
		},
		showServerDetails(server){
			this.selectedServer = server;
			$('#serverDetails').modal();
		},
		sort(champ){
			if(champ == 'price')
			{
				this.filter.sortObject.champ = this.sPrice.champ;
				this.filter.sortObject.order = this.toggleOrder(this.sPrice);
			}
			this.filterPayments();
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
		currencyCode(currency)
		{
			var symbol = this.symbols.find((s)=>(s.currency == currency));
			if(symbol) return symbol.code;
			return "";
		},
		getServers()
		{
			axios.get('api/getAllServers').then((res)=>{
				this.servers = res.data.data;
			})
		},
		getDomains()
		{
			axios.get('api/getDomains').then((res)=>{
				this.domains = res.data.data;
				this.load();
			})
		},
		getIps()
		{
			axios.get('api/getIps').then((res)=>{
				this.ips = res.data.data;
			})
		},
		getGroups()
		{
			axios.get('api/getGroups').then((res)=>{
				this.groups = res.data.data;
			})
		},
		getUsers()
		{
			axios.get('api/getUsers').then((res)=>{
				this.users = res.data.data;
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
		exportPayments()
		{
			$('#exportPayments').text('wait...');
			axios.post('api/exportPayments',
				{'startDate':this.exportStart,'endDate':this.exportEnd,'groupId':this.exportGroup.id,'currency':this.exportCurrency},
				{'responseType':'blob'})
			.then((res)=>
			{
				$('#exportPayments').text('export');
				const url = window.URL.createObjectURL(new Blob([res.data]));
				const link = document.createElement('a');
				link.href = url;
				var name =  'payments_'+moment().format('Y-m-d');
				if(this.exportGroup.name) name = name + '_group_' + this.exportGroup.name;
				if(this.exportCurrency) name = name + '_currency_' + this.exportCurrency;
				name = name + '.xlsx';
				link.setAttribute('download', name);
				document.body.appendChild(link);
				link.click();
			})
		}

		
	},
	mounted : function(){
		
		this.filterPayments();
		this.initDateRangePicker();
		this.load();
		this.getServers();
		this.getDomains();
		this.getIps();
		this.getGroups();
		this.getUsers();
	}
})