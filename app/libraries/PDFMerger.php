<?php
namespace App\libraries;
use App\libraries\pdf\fpdi\FPDI;
use App\libraries\pdf\fpdf\FPDF;

class PDFMerger
{
	private $_files;	//['form.pdf']  ["1,2,4, 5-19"]
	private $_fpdi;
	
	
	
	/**
	 * Add a PDF for inclusion in the merge with a valid file path. Pages should be formatted: 1,3,6, 12-16. 
	 * @param $filepath
	 * @param $pages
	 * @return void
	 */
	public function addPDF($filepath, $pages = 'all')
	{
		if(file_exists($filepath))
		{
			if(strtolower($pages) != 'all')
			{
				$pages = $this->_rewritepages($pages);
			}
			
			$this->_files[] = array($filepath, $pages);
		}
		else
		{
			throw new exception("Could not locate PDF on '$filepath'");
		}
		
		return $this;
	}
	
	/**
	 * Merges your provided PDFs and outputs to specified location.
	 * @param $outputmode
	 * @param $outputname
	 * @return PDF
	 */
	public function merge($outputmode = 'browser', $outputpath = 'newfile.pdf')
	{
		if(!isset($this->_files) || !is_array($this->_files)): throw new exception("No PDFs to merge."); endif;
		
		$fpdi = new FPDI;
		
		//merger operations
		foreach($this->_files as $file)
		{
			$filename  = $file[0];
			$filepages = $file[1];
			
			$count = $fpdi->setSourceFile($filename);
			
			//add the pages
			if($filepages == 'all')
			{
				for($i=1; $i<=$count; $i++)
				{
					$template 	= $fpdi->importPage($i);
					$size 		= $fpdi->getTemplateSize($template);
					
					$fpdi->AddPage('P', array($size['w'], $size['h']));
					$fpdi->useTemplate($template);
				}
			}
			else
			{
				foreach($filepages as $page)
				{
					if(!$template = $fpdi->importPage($page)): throw new exception("Could not load page '$page' in PDF '$filename'. Check that the page exists."); endif;
					$size = $fpdi->getTemplateSize($template);
					
					$fpdi->AddPage('P', array($size['w'], $size['h']));
					$fpdi->useTemplate($template);
				}
			}	
		}
		
		//output operations
		$mode = $this->_switchmode($outputmode);
		
		
		if($mode == 'S')
		{
			return $fpdi->Output($outputpath, 'S');
		}
		else
		{
			if($fpdi->Output($outputpath, $mode))
			{
				return true;
			}
			else
			{
				//throw new exception("Error outputting PDF to '$outputmode'.");
				return false;
			}
		}
		
		
	}
	
	/**
	 * FPDI uses single characters for specifying the output location. Change our more descriptive string into proper format.
	 * @param $mode
	 * @return Character
	 */
	private function _switchmode($mode)
	{
		switch(strtolower($mode))
		{
			case 'download':
				return 'D';
				break;
			case 'browser':
				return 'I';
				break;
			case 'file':
				return 'F';
				break;
			case 'string':
				return 'S';
				break;
			default:
				return 'I';
				break;
		}
	}
	
	/**
	 * Takes our provided pages in the form of 1,3,4,16-50 and creates an array of all pages
	 * @param $pages
	 * @return unknown_type
	 */
	private function _rewritepages($pages)
	{
		$pages = str_replace(' ', '', $pages);
		$part = explode(',', $pages);
		
		//parse hyphens
		foreach($part as $i)
		{
			$ind = explode('-', $i);

			if(count($ind) == 2)
			{
				$x = $ind[0]; //start page
				$y = $ind[1]; //end page
				
				if($x > $y): throw new exception("Starting page, '$x' is greater than ending page '$y'."); return false; endif;	
				
				//add middle pages
				while($x <= $y): $newpages[] = (int) $x; $x++; endwhile;
			}
			else
			{
				$newpages[] = (int) $ind[0];
			}
		}
		
		return $newpages;
	}
	
}