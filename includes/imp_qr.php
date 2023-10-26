<?
      $handle = printer_open(EPSON);
		printer_start_doc($handle, 'My Document');
		printer_start_page($handle);

		$largura = 415; //Image Width in Pixels
		$altura = 284;  //Image Height in Pixels

		$fator_x = $largura / 72; //Image Resolution (72 ppi)
		$fator_y = $largura / 50; //Printer Resolution (300 dpi)
		$fator_z = $fator_x / $fator_y;

		$largura_f = $largura * $fator_z;
		$altura_f = $altura * $fator_z;

		printer_set_option($handle, PRINTER_MODE, 'RAW');
		printer_draw_bmp($handle, 's.bmp', 1, 1, $largura_f, $altura_f);

		printer_end_page($handle);
		printer_end_doc($handle);
		printer_close($handle);

?>