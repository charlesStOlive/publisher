<?php namespace Waka\Publisher\Updates;

//use Excel;
use Seeder;
use DB;
// use Waka\Crsm\Classes\CountryImport;



class SeedAllTable extends Seeder
{
    public function run()
    {
        //Excel::import(new CountryImport, plugins_path('waka/crsm/updates/excels/country.xlsx'));
        // $sector = Sector::create([
        //     'name'                 => 'Défaut',
        //     'slug'                 => 'defaut'
        // ]);
        // $type = Type::create([
        //     'name'                 => 'Prospect',
        //     'slug'                 => 'prospet'
        // ]);
        // $type = Type::create([
        //     'name'                 => 'Client',
        //     'slug'                 => 'client'
        // ]);
        //
        $sql = plugins_path('waka\publisher\updates\sql\waka_publisher_bloc_types.sql');
        DB::unprepared(file_get_contents($sql));
        
    }
}
