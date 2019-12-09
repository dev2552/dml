<?php

use App\Models\DomainModel;
use App\Models\GroupModel;
use App\Models\IpModel;
use App\Models\PaymentModel;
use App\Models\ProviderModel;
use App\Models\RegistrarModel;
use App\Models\RequestModel;
use App\Models\ServerModel;
use App\Models\StatusModel;
use App\Models\SubDomainModel;
use App\OpenExchangeRates;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory(GroupModel::class,6)->create();

        $data = ['role'=>'root',
        'email'=>'admin@admin.com',
        'password'=>'$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
        'group_id'=>1,
        'username'=>'admin',
        'enable'=>true];

        factory(User::class,1)->create($data);

        $data = ['role'=>'root',
        'email'=>'admin2@admin.com',
        'password'=>'$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
        'group_id'=>1,
        'username'=>'admin2',
        'enable'=>true];

        factory(User::class,1)->create($data);

        $data = ['role'=>'user',
        'email'=>'user@user.com',
        'password'=>'$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
        'group_id'=>1,
        'username'=>'user',
        'enable'=>true];

        factory(User::class,1)->create($data);

        $data = ['role'=>'manager',
        'email'=>'manager@manager.com',
        'password'=>'$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm',
        'group_id'=>1,
        'username'=>'manager',
        'enable'=>true];

        factory(User::class,1)->create($data);

        factory(RequestModel::class,50)->create()->each(function($request){
           $status = factory(StatusModel::class)->make(['status'=>$request->_status]);
           $request->status()->save($status);
        });

        factory(ProviderModel::class,10)->create();

        factory(RegistrarModel::class,10)->create();
        
        factory(DomainModel::class,50)->create()->each(function($domain){
            $payment = factory(PaymentModel::class)->make(['type'=>'Domain']);
            $domain->payments()->save($payment);
        });

        factory(ServerModel::class,100)->create()->each(function($server){
            $status = factory(StatusModel::class)->make();
            $server->status()->save($status);
            $server->update(['_status'=>$status->status]);
            $payment = factory(PaymentModel::class)->make(['type'=>'Server']);
            $server->payments()->save($payment);
            $ip = factory(IpModel::class)->make();
            $server->ips()->save($ip);
        });
        
        factory(IpModel::class,100)->create()->each(function($ip){
            $payment = factory(PaymentModel::class)->make(['type'=>'Ip']);
            $ip->payments()->save($payment);
         });

        factory(SubDomainModel::class,100)->create();

        factory(OpenExchangeRates::class,1)->create();
    }
}
