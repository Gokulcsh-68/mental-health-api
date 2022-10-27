<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SystemicValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $master_types = [
            [ 'slug' => 'systemic_sub_types'],
            [ 'slug' => 'systemic-examination']
        ];
        
        DB::table('master_types')->insertOrIgnore($master_types);


        $cloneData = DB::table('masters')->Where('master_type_slug','physical-examination')->get();

        foreach ($cloneData as $key => $value) {
            

            DB::table('masters')->insertOrIgnore(['master_type_slug' => 'systemic-examination','name' => $value->name, 'slug' => $value->slug, 'is_active' => 1]);
        }



        $systemic = [];


        $master = DB::table('masters')->Where('master_type_slug','systemic-examination')->get();

        foreach ($master as $key => $value) {
       
        	$datas = DB::table('dynamic_forms')->Where('slug',$value->slug)->get();

        	foreach ($datas as $k => $v) {        		
        		$v->attributes = json_decode($v->attributes);
        	
        		if($v->attributes->name != 'notes'){
		         $systemic[] = ['attributes' => json_encode(['reference_slug' => $v->slug]),'master_type_slug' => 'systemic_sub_types', 'name' => $v->attributes->label, 'slug' => str_slug($v->slug.$v->attributes->label),'is_active' => 1];
	        	}
        	}
	    }

        foreach ($systemic as $key => $value) {
        	

            DB::table('masters')->insertOrIgnore($value);

            // $matchThese = ['slug'=>$value['slug'],'master_type_slug'=>$value['master_type_slug']];

            // $chk = DB::table('masters')->where('slug',$value['slug'])->where('master_type_slug',$value['master_type_slug'])->value('id');

            // if(!empty($chk)){
            // DB::table('masters')->where('id',$chk)->update($value);

            // }else{
            // DB::table('masters')->insert($value);

            // }
        }

	}
}