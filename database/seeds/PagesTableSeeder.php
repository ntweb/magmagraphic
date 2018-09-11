<?php

use Illuminate\Database\Seeder;
use App\Log;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	App::setLocale('it');

    	$delimiter = '#;#';
    	$contents = file_get_contents(public_path('assets/pages.txt'));
		foreach(preg_split("/((\r?\n)|(\r\n?))/", $contents) as $line){
			if(strlen(trim($line)) > 0) {
			    $xxx = explode($delimiter, $line);
		    
			    $p = new \App\Page;
			    $p->title = $xxx[0];
			    $p->module = $xxx[1];
			    $p->description = $xxx[2];
			    $p->active = $xxx[3];

			    $p->id_created_by = 1;

			    $p->mtitle = $p->title;
			    $p->murl = str_slug($p->title);
			    $p->type = 'standard';
			    $p->save();

			    #echo $p->module." - ";
			}
		}     	

		// no index
		$p = new \App\Page;
		$p->title = "Site name";
		$p->module = "noindex";
		$p->description = "Write something...";
		$p->noindex = '1';
		$p->active = '0';

		$p->id_created_by = 1;

		$p->mtitle = $p->title;
		$p->murl = str_slug($p->title);
		$p->type = 'standard';
		$p->save();

    }
}
