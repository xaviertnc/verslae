<?php

use OneFile\File;
use OneFile\Folder;

/*
 *
 * CSV FORMAAT AFLAAI DOMAIN
 * By: C. Moller - 27 Apr 2016
 *
 */

Class CsvAflaaiDomain
{
	/**
	 *
	 * Maak net die CSV file en stoor dit in tmp/{gebruikernaam}
	 * Om die file af te laai, gebruik (new View)->makeCsvDownloadResponse($csvfilepath)
	 *
	 */
	public static function makeCsvFile($dataModel, $filename, $keepColumns = [], $removeColumns = [])
	{
		$temp_folder = __STORAGE__ . '/tmp';
		$filename .= date('_YmdHis') . '.csv';
		$user_temp_folder = $temp_folder . '/' . (Auth::getAuthUser()->gebruikernaam?:'guest');
		$filepath = $user_temp_folder . '/' . $filename;

		// Maak skoon as .../tmp/{user} reeds bestaan. Anders skep net die folder.
		if (is_dir($user_temp_folder)) { Folder::deleteFiles($user_temp_folder); } else { mkdir($user_temp_folder); }

		try {

			$fh = fopen($filepath, 'w');

			do {

				$dataModel::csvInit();
				$columns = $dataModel::csvGetColumns($keepColumns, $removeColumns);
				$titles = []; foreach ($columns as $column) { $titles[] = $column->title; }

				// Write TITLES
				fputcsv($fh, $titles);

				// Only write the titles if the dataset is empty...
				if ( ! $dataModel::$itemcount) break;

				$itemspp = $dataModel::$itemspp;
				$pagecount = $dataModel::$pagecount;

				// WRITE ALL FULL PAGES
				for ($pageno = 0; $pageno < $pagecount; $pageno++)
				{
					$limit_offset = $pageno * $itemspp;
					$lines = $dataModel::csvGetPageData($limit_offset . ',' . $itemspp);

					// WRITE CSV PAGE
					foreach ($lines as $lineno => $line_data)
					{
						// WRITE CSV LINE
						fputcsv($fh, $dataModel::csvGetLine($limit_offset + $lineno, $line_data));
					}
				}

				// WRITE FINAL PARTIAL PAGE (if required)
				if ($dataModel::$tailcount)
				{
					$limit_offset = $dataModel::$pagecount * $itemspp;
					$lines = $dataModel::csvGetPageData($limit_offset . ',' . $dataModel::$tailcount);

					// WRITE FINAL LINES
					foreach ($lines as $lineno => $line_data)
					{
						// WRITE CSV LINE
						fputcsv($fh, $dataModel::csvGetLine($limit_offset + $lineno, $line_data));
					}
				}

			} while (0);

			fclose($fh);

			Journal::log(3, 'Csv: ' . $filename);

			return $filepath;
		}

		catch (Exception $e)
		{
			if ($fh) { fclose($fh); }
			throw $e;
		}
	}


	public static function hanteer_csv_aflaai($tipe)
	{
		try {

			switch ($tipe)
			{
				case 'borgverslag': $csv_file = self::makeCsvFile('BorgverslagModel', 'borgverslag_kragdag');   break;
				case 'registrasies': $csv_file = self::makeCsvFile('RegistrasieModel' , 'kragdag_registrasies');   break;
				case 'geskiedenis' : $csv_file = self::makeCsvFile('GeskiedenisModel' , 'gebruiker_geskiedenis');  break;
				case 'verwysings'  : $csv_file = self::makeCsvFile('VerwysingsModel'  , 'registrasie_verwysings'); break;
				default: return; // Geen $tipe waarde... D.w.s nie 'n aflaai versoek nie!
			}

			(new \OneFile\View())->makeCsvDownloadResponse($csv_file);
		}

		catch (Exception $e)
		{
			Journal::log(3, 'CSV aflaai het misluk! ' . $e->getMessage());
			State::addAlert('danger', 'CSV aflaai het misluk!');
		}
	}
}
