<?php namespace Waka\Publisher\Updates;

//use Excel;
use Seeder;
use DB;
use Waka\Publisher\Models\BlocType;
use System\Models\File;;
// use Waka\Crsm\Classes\CountryImport;



class SeedTables extends Seeder
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
        $blocType = BlocType::create([
            'name'                 => 'Bloc de texte',
            'code'                 => 'textes',
            'type'                 => 'bloc',
            'compiler'             => 'Waka\\Publisher\\Contents\\Compilers\\Textes',
            'use_icon'             => true,
            'icon'                 => 'icon-align-left',
        ]);
        $explication = new File;
        $explication->is_public = true;
        $explication->data = plugins_path('waka\publisher\updates\exemples\textes.docx');
        $blocType->src_explication = $explication;
        $blocType->save();
        //
        $blocType = BlocType::create([
            'name'                 => 'Blocs texte + media',
            'code'                 => 'mediasTextes',
            'type'                 => 'bloc',
            'compiler'             => 'Waka\\Publisher\\Contents\\Compilers\\MediasTextes',
        ]);
        $file = new File;
        $file->is_public = true;
        $file->data = plugins_path('waka\publisher\updates\src\images\icones_editor_image_media.png');
        $blocType->icon_png = $file;
        $explication = new File;
        $explication->is_public = true;
        $explication->data = plugins_path('waka\publisher\updates\exemples\media_textes.docx');
        $blocType->src_explication = $explication;
        $blocType->save();
        //
        $blocType = BlocType::create([
            'name'                 => 'Bloc photos liés',
            'code'                 => 'linkedPhoto',
            'type'                 => 'bloc',
            'compiler'             => 'Waka\\Publisher\\Contents\\Compilers\\LinkedPhoto',
        ]);
        $file = new File;
        $file->is_public = true;
        $file->data = plugins_path('waka\publisher\updates\src\images\icones_editor_image_model.png');
        $blocType->icon_png = $file;
        $explication = new File;
        $explication->is_public = true;
        $explication->data = plugins_path('waka\publisher\updates\exemples\linked_image.docx');
        $blocType->src_explication = $explication;
        $blocType->save();
        // $sql = plugins_path('waka\publisher\updates\sql\waka_publisher_bloc_types.sql');
        // DB::unprepared(file_get_contents($sql));

        // $sql = plugins_path('waka\publisher\updates\sql\waka_publisher_contents.sql');
        // DB::unprepared(file_get_contents($sql));

        // $sql = plugins_path('waka\publisher\updates\sql\waka_publisher_documents.sql');
        // DB::unprepared(file_get_contents($sql));

        // $sql = plugins_path('waka\publisher\updates\sql\waka_publisher_blocs.sql');
        // DB::unprepared(file_get_contents($sql));

        
    }
}
