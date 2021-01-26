<?php

// The following file has been downloaded from http://fpdf.org/en/script/script3.php and changed accordingly to my needs.
// I claim no authorship, whatsoever. For more information please visit the link.

require('fpdf.php');

class fpdftable extends FPDF
	{
	var $widths;
	var $aligns;
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',9);
    // Page number
    $this->Cell(0,10,'Seite '.$this->PageNo().' von {nb}',0,0,'C');
}
function SetWidths($w)
	{
		//Set the array of column widths
	$this->widths=$w;
	}
function SetAligns($a)
	{
		//Set the array of column alignments
	$this->aligns=$a;
	}
function Row($data, $headers)
	{
		//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
        	$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
		//Issue a page break first if needed
	$this->CheckPageBreak($h, $headers);
		//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
		{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
			//Save the current position
	$x=$this->GetX();
	$y=$this->GetY();
		//Draw the border
	$this->Rect($x,$y,$w,$h);
		//Print the text
	$this->MultiCell($w,5,$data[$i],0,$a);
		//Put the position to the right of the cell
	$this->SetXY($x+$w,$y);
	}
		//Go to the next line
	$this->Ln($h);
	}
function CheckPageBreak($h, $headers)
	{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		{
		$this->AddPage($this->CurOrientation);
		$this->SetFont('times', 'B', 9);
		$this->Row($headers);
		$this->SetFont('times', '', 9);
		}
	}
function NbLines($w,$txt)
	{
		//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
		{
		$c=$s[$i];
		if($c=="\n")
			{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
			}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
			{
			if($sep==-1)
				{
				if($i==$j)
					$i++;
				}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			}
		else
			$i++;
		}
	return $nl;
	}


function TableClient($header, $data, $sizes, $color_bg=FALSE)
	{
	// Header
	$count = 0;
	$this->SetFillColor(255,255,255);
	if ( $header != "" )
		{
		$this->SetFont('times','B',9);
		$color_count = 1;
		foreach($header as $col)
			{
			if ( $color_bg === TRUE )
				{
				$color = Color_Set($color_count);
				$this->SetFillColor($color['r'], $color['g'], $color['b']);
				$color_count++;
				}
			$this->Cell($sizes[$count],8,$col,1,0,'C',true);
			$count++;
			}
		$this->SetFont('times','',9);
		$this->Ln();
		}	
		// Data
	//	$this->SetFont('times','',8);
	foreach($data as $row)
		{
		$count = 0;
		$color_count = 1;
		foreach($row as $col)
			{
			if ( $color_bg === TRUE )
				{
				$color = Color_Set($color_count);
				$this->SetFillColor($color['r'], $color['g'], $color['b']);
				$color_count++;
				}
			$this->Cell($sizes[$count],7,$col,1,0,'C',true);
			$count++;
			}
		$this->SetFillColor(255,255,255);
		$this->Ln();
		}
	}
}
?>
