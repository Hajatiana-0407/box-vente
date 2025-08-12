<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;


class ExportationModel
{

	public function exporter($fichier = 'Doc' , $data = [], $entetes = [] , $titre = '' )
	{

		if ( $titre == ''){
			$titre = $fichier  ; 
		}

		// Code d'initialisation...
		$fichier = htmlspecialchars(trim($fichier));

		$ligne = count($data);
		$nombre = 0;
		$enteteValue = [];
		$cellule = "A";
		$listeCellule = [];


		for ($j = 0; $j < count($entetes); $j++) {
			$elem  = $entetes[$j];
			if (strpos($elem, "e#") == true) {
				$enteteValue[] = ucfirst(str_replace("e#", "é", $elem));
			} else {
				$enteteValue[] = ucfirst($elem);
			}

			$listeCellule[] = $cellule;
			$cellule++;
		}

		// Initialisation de l'entête
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();

		// let titre et le date 
		$sheet->setCellValue( $listeCellule[ count( $listeCellule ) - 1  ] . '1', $titre .' le : '. date('d-m-Y')) ; 
		$sheet->getColumnDimension($listeCellule[ count( $listeCellule ) - 1  ])->setAutoSize(true);

		// Configuration de l'entête
		for ($i = 0; $i < count($entetes); $i++) {
			$sheet->setCellValue($listeCellule[$i] . '2', $enteteValue[$i]);
			$sheet->getColumnDimension($listeCellule[$i])->setAutoSize(true);
		}



		// Remplissage des valeurs
		$ligneExcel = 3;
		for ($i = 0; $i < count($data); $i++) {
			$k = 0;
			foreach ($data[$i] as $value) {

				if (is_numeric($value) && $value != 0) {
					$spreadsheet->getActiveSheet()->getCell($listeCellule[$k] . $ligneExcel)->setValue($value);
					$spreadsheet->getActiveSheet()->getStyle($listeCellule[$k] . $ligneExcel)->getNumberFormat()->setFormatCode('### ### ### ### ###');
				} else {
					$sheet->setCellValue($listeCellule[$k] . $ligneExcel, $value);
				}
				$k++;
			}
			$ligneExcel++;
		}

		// var_dump(  $listeCellule[count($entetes) - 1] ) ; die  ; 

		// Appliquer le style et écrire le fichier

		$styleArray = array("borders" => array("allBorders" => array("borderStyle" => Border::BORDER_THIN, "color" => array("argb" => "000000"),),),);

		// style anle entete
		$spreadsheet->getActiveSheet()
			->getStyle($listeCellule[0] . '2:' . $listeCellule[count($entetes) - 1] . '2')
			->getFont()
			->setBold(true);


		// border a partir de l'entete ; 
		for ($i= 2 ; $i < $ligneExcel    ; $i++) { 
			$sheet->getStyle($listeCellule[0] . $i  . ":" . $listeCellule[count($entetes) - 1] . $i )->applyFromArray($styleArray);
		}



		// Écriture du fichier
		$writer = new Xlsx($spreadsheet);
		ob_start();
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . urlencode($fichier . ".xlsx") . '"');
		$writer->save('php://output');
		exit();
	}
}
