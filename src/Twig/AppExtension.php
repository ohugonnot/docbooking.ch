<?php
namespace App\Twig;

use App\Entity\Timing;
use App\Entity\Appointment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Doctrine\ORM\EntityManagerInterface;

class AppExtension extends AbstractExtension
{
	private $em;
	
	public function __construct(EntityManagerInterface $em){
		$this->em = $em;
	}
	
    public function getFilters()
    {
        return [
            new TwigFilter('formatTiming', [$this, 'formatTiming']),
			new TwigFilter('formatHeaderTiming', [$this, 'formatHeaderTiming']),
			new TwigFilter('formatTimingCheckout', [$this, 'formatTimingCheckout']),
			new TwigFilter('formatOpeningNow', [$this, 'formatOpeningNow']),
			new TwigFilter('formatIsOpeningNow', [$this, 'formatIsOpeningNow']),	
			new TwigFilter('formathomeCalendar', [$this, 'formathomeCalendar'])
        ];
    }
	
	public function formatHeaderTiminghome(){
		$date = new \DateTime();
		$next_date = new \DateTime();
		$prev_date = new \DateTime();
		
		$next_date->setISODate($next_date->format('o'), $next_date->format('W') + 1);
		$prev_date->setISODate($prev_date->format('o'), $prev_date->format('W') - 1);
		
		$date->setISODate($date->format('o'), $date->format('W'));
		$periods = new \DatePeriod($date, new \DateInterval('P1D'), 6);
		$response = [];
		foreach($periods as $period){
			$response['time'][] = [
				$period->format('j'),
				$period->format('m'),
				$period->format('y')
			];
		}
		$response['next'] = $next_date;
		$response['prev'] = $prev_date;
		return $response;
	}
	
	public function getMaxElem($arr){
		$count_arr = [];
		foreach($arr as $a){
			$times = json_decode($a->getTimes(), true);
			if(count($times)){
				$index = 0;
				foreach($times as $t){
					if($t['selected'] == 'true'){
						$index++;
					}
				}
				$count_arr[] = $index;
			}
			else{
				$count_arr[] = 0;
			}
		}
		$ret = 0;
		if(!empty($count_arr)){
			$ret = max($count_arr);
		}
		return $ret;
	}
	
	public function isUsed($app_date, $app_time){
		$app_date = date('Y-m-d', strtotime($app_date));
		$appointments = $this->em->getRepository(Appointment::class)->findExistAppointment($app_date, $app_time);
		if($appointments){
			return true;
		}
		return false;
	}
	
	public function isPassedDate($app_date, $app_time){
		$dto = new \DateTime();
		$dto1 = new \DateTime($app_date . ' ' . $app_time);
		$is_show = false;
		if($dto<=$dto1){
			$is_show = true;
		}
		return $is_show;
	}
	
	public function formathomeCalendar($array, $doctor){
		$output = [];
		$date = date('m/d/Y h:i:s a', time());
		$date = new \DateTime($date);
		$week = $date->format("W");
		$weekStartEnd = $this->getStartAndEndDate($week, $date->format("Y"));
		$timing = $this->formatHeaderTiminghome();
		$month_array = [];
		$year_array = [];
		$day_array = [];
		foreach($timing['time'] as $tms){
			$month_array[] = $tms[1];
			$year_array[] = $tms[2];
			$day_array[] = $tms[0];
		}
		$array = $doctor->getIdTiming()->filter(
			function($entry) use ($day_array, $month_array, $year_array) {
				return in_array($entry->getDay(), $day_array) && in_array($entry->getMonth(), $month_array) && in_array($entry->getYear(), $year_array);
			}
		);
		$output = [];
		$output[] = '<!-- Schedule Header -->' . "\n";
		$output[] = '<div class="schedule-header">' . "\n";
		$output[] = '<div class="row">' . "\n";
		$output[] = '<div class="col-md-12">' . "\n";
		$output[] = '<!-- Day Slot -->' . "\n";
		$output[] = '<div class="day-slot dlpro-calendar-render">' . "\n";
		$output[] = '<!-- Day Slot -->' . "\n";
		$output[] = '<div class="day-slot">' . "\n";
		$output[] = '<ul>' . "\n";
		$output[] = '<li class="left-arrow">' . "\n";
		$output[] = '<a class="dlproPrev" href onClick="return dlproPrev(this, '.$timing['prev']->getTimestamp().', '.$doctor->getId().');">' . "\n";
		$output[] = '<i class="fa fa-chevron-left"></i>' . "\n";
		$output[] = '</a>' . "\n";
		$output[] = '</li>' . "\n";
		foreach($timing['time'] as $time){
			$date= $time[0] . '-' .  $time[1] . '-' .  $time[2];
			$dateObj   = \DateTime::createFromFormat('j-m-Y', $date);
			$monthName = $dateObj->format('F');
			$dayName = $dateObj->format('D');
			$output[] = '<li>' . "\n";
			$output[] = '<span>' . $dayName . '</span>' . "\n";
			$output[] = '<span class="slot-date">' . $time[0] . ' ' . $monthName . ' <small class="slot-year">' . $time[2] . '</small></span>' . "\n";
			$output[] = '</li>' . "\n";
		}
		$output[] = '<li class="right-arrow">' . "\n";
		$output[] = '<a class="dlproNext" href onClick="return dlproNext(this, '.$timing['next']->getTimestamp().','.$doctor->getId().');">' . "\n";
		$output[] = '<i class="fa fa-chevron-right"></i>' . "\n";
		$output[] = '</a>' . "\n";
		$output[] = '</li>' . "\n";
		$output[] = '</ul>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Day Slot -->' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Day Slot -->' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Schedule Header -->' . "\n";	
		$output[] = '<!-- Schedule Content -->' . "\n";
		$output[] = '<div class="schedule-cont">' . "\n";
		$output[] = '<div class="row">' . "\n";
		$output[] = '<div class="col-md-12">' . "\n";
		$output[] = '<!-- Time Slot -->' . "\n";
		$output[] = '<div class="time-slot dlpro-calendar-time-render">' . "\n";
		$output[] = '<ul class="clearfix dlproCollapse" id="collapseDlproTime' . $doctor->getId() . '">' . "\n";
		if(!empty($array)){
			$max_el= $this->getMaxElem($array);
			foreach($array as $tm){
				$day = $tm->getDay();
				$year = $tm->getYear();
				$month = $tm->getMonth();
				$times = json_decode($tm->getTimes(), true);
				$first = true;
				$output[] = '<li>' . "\n";
				if(count($times)){
					$count_selected = 0;
					foreach($times as $t){
						$selected = '';
						$app_date = $year . '-' . $month . '-' . $day;
						$app_time = $t['time'];
						if($t['selected'] == 'true' && !$this->isUsed($app_date, $app_time) && $this->isPassedDate($app_date, $app_time)){
							$selected= 'selected';
							$output[] = '<a class="timing dlproTimeElementSelected ' . $selected . '" href data-doctorid="' . $doctor->getId() . '" data-step="" data-time="' . $t['time'] . '" data-id="' . $tm->getId() . '" data-year="' . $year . '" data-month="' . $month . '" data-day="' . $day . '">' . "\n";
							$output[] = '<span>' . $t['time'] . '</span>' . "\n";
							$output[] = '</a>' . "\n";
							$count_selected++;
						}
						if($t['selected'] == 'true' && !$this->isUsed($app_date, $app_time) && !$this->isPassedDate($app_date, $app_time)){
							$selected= 'selected';
							$output[] = '<a class="timing dlproTimeElement" href data-step="" data-time="' . $t['time'] . '" data-id="' . $tm->getId() . '" data-year="' . $year . '" data-month="' . $month . '" data-day="' . $day . '" onClick="return false;">' . "\n";
							$output[] = '<span>' . $t['time'] . '</span>' . "\n";
							$output[] = '</a>' . "\n";
							$count_selected++;
						}
						if($t['selected'] == 'true' && $this->isUsed($app_date, $app_time) && !$this->isPassedDate($app_date, $app_time)){
							$selected= 'selected';
							$output[] = '<a class="timing dlproTimeElement" href data-step="" data-time="' . $t['time'] . '" data-id="' . $tm->getId() . '" data-year="' . $year . '" data-month="' . $month . '" data-day="' . $day . '" onClick="return false;">' . "\n";
							$output[] = '<span>' . $t['time'] . '</span>' . "\n";
							$output[] = '</a>' . "\n";
							$count_selected++;
						}
					}
					if($count_selected){
						for($i=0;$i<($max_el - $count_selected);$i++){
							$output[] = '<a class="timing dlproTimeElement" href="#" data-step="" data-time="" data-id="" data-year="" data-month="" data-day="">' . "\n";
							$output[] = '<span>--:--</span>' . "\n";
							$output[] = '</a>' . "\n";
						}
					}
					else{
						for($i=0;$i<$max_el;$i++){
							$output[] = '<a class="timing dlproTimeElement" href="#" data-step="" data-time="" data-id="" data-year="" data-month="" data-day="">' . "\n";
							$output[] = '<span>--:--</span>' . "\n";
							$output[] = '</a>' . "\n";
						}
					}
					$output[] = '</li>' . "\n";
				}
				else{
					
				}
				
			}
		}
		else{
			$slot = 1;
			foreach($timing['time'] as $time){
				$output[] = '<li>' . "\n";
				for($j=25200;$j<=72000;$j=$j+$slot){
					$h = floor($j / 3600);
					$m = floor($j % 3600 / 60);
					$output[] = '<a class="timing dlproTimeElement" href="#" data-step="' . $h . '" data-time="' . $h . ':' . $m . '" data-id="" data-year="' . $time[2] . '" data-month="' . $time[1] . '" data-day="' . $time[0] . '">' . "\n";
					$output[] = '<span>' . $h . ':' . $m . '</span>' . "\n";
					$output[] = '</a>' . "\n";
				}
				$output[] = '</li>' . "\n";
			}
		}
		$output[] = '</ul>' . "\n";
		$output[] = '<div class="col-md-12 text-center is-dlpro-show-more" style="padding-top: 15px;">' . "\n";
		$output[] = '<a class="btn btn-outline-primary dlproColapseLink" href="#collapseDlproTime' . $doctor->getId() . '">See more schedules</a>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Time Slot -->' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Schedule Content -->' . "\n";
		return join("\n", $output);
	}
	
	public function formatIsOpeningNow($array_value){
		$now = new \DateTime();
		$current_day = $now->format('D');
		$output = '';
		foreach($array_value as $value){
			if($value['day'] == $current_day){
				$currentTime = $now->format('H:i');
				if ((strtotime($value['from']) < strtotime($currentTime)) && (strtotime($currentTime) < strtotime($value['to']))) {
					$output = '<span class="badge bg-success-light">Open Now</span>';
					break;
				}else{
					// Opening Later
					$output = '<span class="badge bg-danger-light">Closed Now</span>';
				}
			}
		}
		return $output;
	}
	
	public function formatOpeningNow($array_value){
		$now = new \DateTime();
		$current_day = $now->format('D');
		$output = '';
		foreach($array_value as $value){
			if($value['day'] == $current_day){
				$output = $value['from'] . ' AM - ' . $value['to'] . ' PM';
			}
		}
		return $output;
	}
	
	public function getStartAndEndDate($week, $year) {
		$dto = new \DateTime();
		$dto->setISODate($year, $week);
		$ret['week_start'] = [
			'day'	=>	 $dto->format('j'),
			'month'	=>	 $dto->format('n'),
			'year'	=>	 $dto->format('Y')
		];
		$dto->modify('+6 days');
		$ret['week_end'] = [
			'day'	=>	 $dto->format('j'),
			'month'	=>	 $dto->format('n'),
			'year'	=>	 $dto->format('Y')
		];
		return $ret;
	}
	
	public function formatHeaderTiming($arr){
		
		$date = date('m/d/Y h:i:s a', time());
		$date = new \DateTime($date);
		$week = $date->format("W");
		$weekStartEnd = $this->getStartAndEndDate($week, $date->format("Y"));
		
		$currentMonth=[
			$weekStartEnd['week_start']['month'],
			$weekStartEnd['week_end']['month']
		];
		$currentDay=[];
		for($i=$weekStartEnd['week_start']['day']; $i<=$weekStartEnd['week_end']['day'];$i++){
			$currentDay[] = $i;
		}
		$currentYear=[
			$weekStartEnd['week_start']['year'],
			$weekStartEnd['week_end']['year']
		];
		
		$currentMonth = array_unique ( $currentMonth );
		$currentDay = array_unique ( $currentDay );
		$currentYear = array_unique ( $currentYear ); 
		
		
		
		$output = [];
		$output[] = '<li class="left-arrow">' . "\n";
		$output[] = '<a href="">' . "\n";
		$output[] = '<i class="fa fa-chevron-left"></i>' . "\n";
		$output[] = '</a>' . "\n";
		$output[] = '</li>' . "\n";
		
		$arrFiltered = $arr->filter(
			function (Timing $entry) use ($currentMonth, $currentDay, $currentYear) {
				return (in_array($entry->getMonth(), $currentMonth) && in_array($entry->getDay(), $currentDay) && in_array($entry->getYear(), $currentYear));
			}
		);	

		foreach($arrFiltered as $time){
			$output[] = '<li>' . "\n";
			$output[] = '<span>' . $time->getDayNameOfMonth() . '</span>' . "\n";
			$output[] = '<span class="slot-date">' . $time->getDay() . ' ' . $time->getNameOfMonth() . ' <small class="slot-year">' . $time->getYear() . '</small></span>' . "\n";
			$output[] = '</li>' . "\n";
		}
		
		$output[] = '<li class="right-arrow">' . "\n";
		$output[] = '<a href="">' . "\n";
		$output[] = '<i class="fa fa-chevron-right"></i>' . "\n";
		$output[] = '</a>' . "\n";
		$output[] = '</li>' . "\n";
		
		return join("\n", $output);
	}
	
	public function formatHeaderTimingCheckout(){
		$date = new \DateTime();
		$next_date = new \DateTime();
		$prev_date = new \DateTime();
		
		$next_date->setISODate($next_date->format('o'), $next_date->format('W') + 1);
		$prev_date->setISODate($prev_date->format('o'), $prev_date->format('W') - 1);
		
		$date->setISODate($date->format('o'), $date->format('W'));
		$periods = new \DatePeriod($date, new \DateInterval('P1D'), 6);
		$response = [];
		foreach($periods as $period){
			$response['time'][] = [
				$period->format('j'),
				$period->format('m'),
				$period->format('y')
			];
		}
		$response['next'] = $next_date;
		$response['prev'] = $prev_date;
		return $response;
	}
	
	public function formatTimingCheckout($arr, $timeSelected, $daySelected, $monthSelected, $yearSelected){
		$dt = $monthSelected . '/' . $daySelected . '/' . $yearSelected . ' ' . $timeSelected;
		$date = date('m/d/Y H:i:s', strtotime($dt));
		$date = new \DateTime($date);
		$week = $date->format("W");
		$weekStartEnd = $this->getStartAndEndDate($week, $date->format("Y"));
		$timing = $this->formatHeaderTimingCheckout();
		$output = [];
		$output[] = '<!-- Schedule Header -->' . "\n";
		$output[] = '<div class="schedule-header">' . "\n";
		$output[] = '<div class="row">' . "\n";
		$output[] = '<div class="col-md-12">' . "\n";		
		$output[] = '<!-- Day Slot -->' . "\n";
		$output[] = '<div class="day-slot">' . "\n";
		$output[] = '<ul>' . "\n";
		
		$dates = array();
		$current = strtotime($weekStartEnd['week_start']['year'] . '-' . $weekStartEnd['week_start']['month'] . '-' . $weekStartEnd['week_start']['day']);
		$date2 = strtotime($weekStartEnd['week_end']['year'] . '-' . $weekStartEnd['week_end']['month'] . '-' . $weekStartEnd['week_end']['day']);
		$stepVal = '+1 day';
		$timings = [];
		while( $current <= $date2 ) {
			$dt = date('d-m-Y', $current);
			$dates[] = $dt;
			$dateObj   = \DateTime::createFromFormat('d-m-Y', $dt);
			$timings[] = [
				$dateObj->format('j'),
				$dateObj->format('m'),
				$dateObj->format('y')
			];
			$current = strtotime($stepVal, $current);
		}
		
		foreach($dates as $dat){
			$dateObj   = \DateTime::createFromFormat('d-m-Y', $dat);
			$monthName = $dateObj->format('F');
			$dayName   =   $dateObj->format('D');
			$day   =   $dateObj->format('d');
			$output[] = '<li>' . "\n";
			$output[] = '<span>' . $dayName . '</span>' . "\n";
			$output[] = '<span class="slot-date">' . $day . ' ' . $monthName . ' <small class="slot-year">' . $dateObj->format('Y') . '</small></span>' . "\n";
			$output[] = '</li>' . "\n";
		}
		$output[] = '</ul>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Day Slot -->' . "\n";	
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Schedule Header -->' . "\n";	
		$output[] = '<!-- Schedule Content -->' . "\n";
		$output[] = '<div class="schedule-cont">' . "\n";
		$output[] = '<div class="row">' . "\n";
		$output[] = '<div class="col-md-12">' . "\n";
		$output[] = '<!-- Time Slot -->' . "\n";
		$output[] = '<div class="time-slot">' . "\n";
		$output[] = '<ul class="clearfix">' . "\n";
		foreach($timings as $time){
			$date= date('j-m-y', strtotime($time[0] . '-' .  $time[1] . '-' .  $time[2]));
			$j = $time[0];
			$m = $time[1];
			$y = $time[2];
			if( $j == $daySelected && $m == $monthSelected && $y == $yearSelected){
				$output[] = '<li>' . "\n";
				$output[] = '<a class="timing dlproTimeElement selected" href="#" data-step="" data-time="" data-id="" data-year="" data-month="" data-day="">' . "\n";
				$output[] = '<span>' . $timeSelected . '</span>' . "\n";
				$output[] = '</a>' . "\n";
				$output[] = '</li>' . "\n";
			}
			else{
				$output[] = '<li>' . "\n";
				$output[] = '<a class="timing dlproTimeElement" href="#" data-step="" data-time="" data-id="" data-year="" data-month="" data-day="">' . "\n";
				$output[] = '<span>--:--</span>' . "\n";
				$output[] = '</a>' . "\n";
				$output[] = '</li>' . "\n";
			}
		}
		$output[] = '</ul>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Time Slot -->' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '</div>' . "\n";
		$output[] = '<!-- /Schedule Content -->	' . "\n";
		return join("\n", $output);
	}
	
	public function render($timeA, $path, $maxIndex){
		$index = 0;
		$output = [];
		$output[] = '<li>' . "\n";
		foreach($timeA as $subTime){
			$selected = '';
			if($subTime->selected == true){
				$selected = 'selected';
				$output[] = '<a class="timing ' . $selected . '" href="' . str_replace('TIME', $subTime->time, $path) . '">' . "\n";;
				$output[] = '<span>' . $subTime->time . '</span>' . "\n";;
				$output[] = '</a>' . "\n";
			}
			/*else{
				$output[] = '<a class="timing" href="#">' . "\n";;
				$output[] = '<span>--:--</span>' . "\n";;
				$output[] = '</a>' . "\n";;
			}*/
			if($index == $maxIndex){
				break;
			}
			else{
				$index ++;
			}
		}
		$output[] = '</li>' . "\n";
		return join("\n", $output);
	}
	
	public function getMaxIndexSelected($arr){
		$maxIndex = 0;
		$index = 0;
		foreach($arr as $time){
			$timeA = $time->getTimesArray();
			foreach($timeA as $subTime){
				if($subTime->selected == true && $index>$maxIndex){
					$maxIndex = $index;
				}
				$index++;
			}
			$index = 0;
		}
		return $maxIndex;
	}

    public function formatTiming($arr, $path)
    {
		$maxIndex = $this->getMaxIndexSelected($arr);
		$output = '';
		foreach($arr as $time){
			$path_format = str_replace('TIMEID', $time->getId(), $path);
			$timeA = $time->getTimesArray();
			$output .= $this->render($timeA, str_replace('%TIMEID%', $time->getId(), $path_format), $maxIndex);
			$index = 0;
			$sub_output = [];
			$sub_output[] = '<li>' . "\n";
			foreach($timeA as $subTime){
				$selected = '';
				if($subTime->selected == true){
					$selected = 'selected';
					$sub_output[] = '<a class="timing ' . $selected . '" href="' . str_replace('TIME', $subTime->time, $path) . '">' . "\n";
					$sub_output[] = '<span>' . $subTime->time . '</span>' . "\n";;
					$sub_output[] = '</a>' . "\n";
				}
				else{
					$sub_output[] = '<a class="timing" href="#">' . "\n";
					$sub_output[] = '<span>--:--</span>' . "\n";;
					$sub_output[] = '</a>' . "\n";;
				}
				if($index == $maxIndex){
					break;
				}
				else{
					$index ++;
				}
			}
			$sub_output[] = '</li>' . "\n";
			$output .= join("\n", $sub_output);
		}
		
		return $output;
       
    }
	public function getName()
    {
        return 'app_extension';
    }
}