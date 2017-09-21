<?php
/**
 * @version     1.0.0
 * @package     com_tjreports
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      TechJoomla <extensions@techjoomla.com> - http://www.techjoomla.com
 */

// no direct access
defined('_JEXEC') or die;

$displayFilters = $this->filters;
$filters = $this->filterValues;

foreach($displayFilters as $searchKey => $filter)
{
	$searchType = $filter['search_type'];
	$searchValue = isset($filters[$searchKey]) ? $filters[$searchKey] : '';
	$filterHtml = '';

	if($searchType == 'text')
	{
		$filterHtml = '<div class="input-append">
							<input type="text" name="filters[' . $searchKey . ']"
									class="input input-mini filter-input" ' .
									'onkeydown="tjrContentUI.report.submitOnEnter(event);"
									onblur="tjrContentUI.report.submitTJRData();"
									value="' . htmlspecialchars($searchValue) . '"
								/>
							<span class="input-group-btn">
								<button class="btn btn-secondary close-icon" type="button" title="Cancel Search">
									<i class="icon-remove"></i>
								</button>
							</span>
						</div>';
	}
	elseif($searchType == 'select' && isset($filter['select_options']))
	{
		$svalue = isset($filter['select_value']) ? $filter['select_value'] : "value";
		$stext  = isset($filter['select_text']) ? $filter['select_text'] : "text";

		$filterHtml = '<div class="input-append">';

		$filterHtml .= JHtml::_('select.genericlist', $filter['select_options'], 'filters[' . $searchKey . ']',
					'class="filter-input input-medium input-2" size="1" onchange="tjrContentUI.report.submitTJRData();"',
					$svalue, $stext, $searchValue);

		$filterHtml .= '<span class="input-group-btn">
								<button class="btn btn-secondary close-icon" type="button" title="Cancel Search">
									<i class="icon-remove"></i>
								</button>
							</span></div>';
	}
	elseif($searchType == 'date.range' || $searchType == 'calendar')
	{
		$j = ($searchType == 'date.range') ? 2 : 1;

		for ($i=1; $i<=$j; $i++)
		{
			if ($searchType == 'date.range')
			{
				$fieldKey =  ($i == 1)?  ($searchKey . '_from') : ($searchKey . '_to');
			}
			else
			{
				$fieldKey =  $searchKey;
			}

			$searchValue = isset($filters[$fieldKey]) ? $filters[$fieldKey] : '';
			$dateFormat  = isset($filters['dateFormat']) ? $filters['dateFormat'] : '%Y-%m-%d';
			$attrib		 = array('class' => 'tjrsmall-input dash-calendar validate-ymd-date');

			if (isset($filter[$fieldKey]['attrib']))
			{
				$fieldAttr = array_merge($filter[$fieldKey]['attrib'], $attrib);
			}
			elseif (isset($filter['attrib']))
			{
				$fieldAttr = array_merge($filter['attrib'], $attrib);
			}
			else
			{
				$fieldAttr = $attrib;
			}

			$filterHtml  .= '<div class="filter-search controls">'
				. JHtml::_('calendar', htmlspecialchars($searchValue), 'filters['. $fieldKey . ']', 'filters_' . $fieldKey , $dateFormat, $fieldAttr)
				. '</div>'
			;
		}
	}
	elseif($searchType == 'html')
	{
		$filterHtml = $filter['html'];
	}
	?>
		<div class="filter-search controls pull-left <?php echo $this->classForShowHide; ?>">
			<?php echo $filterHtml;?>
		</div>
	<?php
}
