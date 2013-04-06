<?php
class View_Tasks_View extends ViewModel
{
	public function view()
	{

		/**
		 *	Produces a &gt;span&lt; with the textual description of the pririty and a css class with the same.
		 *  Priority will be one of Highest, High, Normal, Low, and Lowest with 1 being the highest.  Unknown values 
		 *  are mapped to Normal
		 */
		$this->show_priority = function($priority) {
			$output = '<span class="';
			switch($priority) {
			case 1:
				$output .= 'highest">Highest';
				break;
			case 2:
				$output .= 'high">High';
				break;
			case 3:
				$output .= 'normal">Normal';
				break;
			case 4:
				$output .= 'low">Low';
				break;
			case 5:
				$output .= 'lowest">Lowest';
				break;
			default:
				$output .= 'normal">Normal';
			}
			$output .= "</span>";

			return $output;
		};	
	}
}