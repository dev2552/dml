var notification = new Vue({
	el : '#notifications',
	data : {
		unreadNotificationsCount : 'wait ...',
	},
	methods : {
		getUnreadNotificationsCount()
		{
			var url = $('#notificationsUrl').val();
				 axios.get(url).then((res)=>{
	                    this.unreadNotificationsCount = res.data;
	             })
		},
		exec()
		{
			setInterval(()=>{
				var url = $('#notificationsUrl').val();
				 axios.get(url).then((res)=>{
	                    this.unreadNotificationsCount = res.data;
	             })
				},60000)
		}
	},
	mounted : function()
	{
		this.getUnreadNotificationsCount();
		this.exec();
	}
})