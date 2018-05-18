<?php

use Illuminate\Database\Seeder;

class GdprTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lab_parameters')->insert([
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_site_name',			'value' => 'Es: Games shop', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_site_url',			'value' => 'Es: www.gameshop.com', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_tit_trat',			'value' => 'Es: Mario Rossi', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_email_titolare',			'value' => '', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_tit_trat_2',			'value' => 'Es: se diverso dal titolare', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_luogo_trat',			'value' => 'Es: Via Napoli 70, 70123 bari', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_cookie_terzi',			'value' => 'Es: 1 = si; 0 = no', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_cookie_profilazione',			'value' => 'Es: 1 = si; 0 = no', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_dati_finalita',			'value' => '1 = solo sicurezza; 2 = marketing', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_dati_finalita_desc',			'value' => null, 'extras' => 'Motivo per la richiesta'],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_dati_periodo',			'value' => 'Es: 2 anni', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_plugin_social',			'value' => 'Es: 1 = si; 0 = no', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_url',			'value' => 'Es: www.gamesshop/privacy', 'extras' => null],
        	['module' => 'parameter',	'module2nd' => 'gdpr',	'label' => 'gdpr_data_modifica',			'value' => 'Es: 18/05/2018', 'extras' => null],
        ]);
    }
}
