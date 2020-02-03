<?php namespace Waka\Publisher\Updates;

//use Excel;
use Seeder;
use DB;
use Storage;
use Waka\Publisher\Models\BlocType;
use System\Models\File;
// use Waka\Crsm\Classes\CountryImport;



class SeedTables extends Seeder
{
    public function run()
    {
        // Creation des répoertoires publisher
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

        $this->call('Waka\Crsm\Updates\Seeders\SeedProjectsMissions');
    }
}
