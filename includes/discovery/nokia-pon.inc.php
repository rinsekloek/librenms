<?php
/*
 * LibreNMS module to capture Cisco Class-Based QoS Details
 *
 * Copyright (c) 2015 Aaron Daniels <aaron@daniels.id.au>
 *
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.  Please see LICENSE.txt at the top level of
 * the source code distribution for details.
 */

if ($device['os'] == 'nokia-isam') {
	$isam_port_table = [
		'94380032' => '1/1/1',
		'94445568' => '1/1/2',
		'94511104' => '1/1/3',
		'94576640' => '1/1/4',
		'94642176' => '1/1/5',
		'94707712' => '1/1/6',
		'94773248' => '1/1/7',
		'94838784' => '1/1/8',
		'94904320' => '1/1/9',
		'94969856' => '1/1/10',
		'95035392' => '1/1/11',
		'95100928' => '1/1/12',
		'95166464' => '1/1/13',
		'95232000' => '1/1/14',
		'95297536' => '1/1/15',
		'95363072' => '1/1/16',
		'127934464' => '1/2/1',
		'128000000' => '1/2/2',
		'128065536' => '1/2/3',
		'128131072' => '1/2/4',
		'128196608' => '1/2/5',
		'128262144' => '1/2/6',
		'128327680' => '1/2/7',
		'128393216' => '1/2/8',
		'128458752' => '1/2/9',
		'128524288' => '1/2/10',
		'128589824' => '1/2/11',
		'128655360' => '1/2/12',
		'128720896' => '1/2/13',
		'128786432' => '1/2/14',
		'128851968' => '1/2/15',
		'128917504' => '1/2/16',
		'161488896' => '1/3/1',
		'161554432' => '1/3/2',
		'161619968' => '1/3/3',
		'161685504' => '1/3/4',
		'161751040' => '1/3/5',
		'161816576' => '1/3/6',
		'161882112' => '1/3/7',
		'161947648' => '1/3/8',
		'162013184' => '1/3/9',
		'162078720' => '1/3/10',
		'162144256' => '1/3/11',
		'162209792' => '1/3/12',
		'162275328' => '1/3/13',
		'162340864' => '1/3/14',
		'162406400' => '1/3/15',
		'162471936' => '1/3/16',
		'195043328' => '1/4/1',
		'195108864' => '1/4/2',
		'195174400' => '1/4/3',
		'195239936' => '1/4/4',
		'195305472' => '1/4/5',
		'195371008' => '1/4/6',
		'195436544' => '1/4/7',
		'195502080' => '1/4/8',
		'195567616' => '1/4/9',
		'195633152' => '1/4/10',
		'195698688' => '1/4/11',
		'195764224' => '1/4/12',
		'195829760' => '1/4/13',
		'195895296' => '1/4/14',
		'195960832' => '1/5/15',
		'196026368' => '1/5/16',
		'228597760' => '1/5/1',
		'228663296' => '1/5/2',
		'228728832' => '1/5/3',
		'228794368' => '1/5/4',
		'228859904' => '1/5/5',
		'228925440' => '1/5/6',
		'228990976' => '1/5/7',
		'229056512' => '1/5/8',
		'229122048' => '1/5/9',
		'229187584' => '1/5/10',
		'229253120' => '1/5/11',
		'229318656' => '1/5/12',
		'229384192' => '1/5/13',
		'229449728' => '1/5/14',
		'229515264' => '1/5/15',
		'229580800' => '1/5/16',
		'262152192' => '1/6/1',
		'262217728' => '1/6/2',
		'262283264' => '1/6/3',
		'262348800' => '1/6/4',
		'262414336' => '1/6/5',
		'262479872' => '1/6/6',
		'262545408' => '1/6/7',
		'262610944' => '1/6/8',
		'262676480' => '1/6/9',
		'262742016' => '1/6/10',
		'262807552' => '1/6/11',
		'262873088' => '1/6/12',
		'262938624' => '1/6/13',
		'263004160' => '1/6/14',
		'263069696' => '1/6/15',
		'263135232' => '1/6/16'
	];
    $module = 'Nokia-PON';

    $component = new LibreNMS\Component();
    $components = $component->getComponents($device['device_id'], ['type' => $module]);

    // We only care about our device id.
    $components = $components[$device['device_id']];

    // Begin our master array, all other values will be processed into this array.
    $tblPON = [];


    // TODO
	// The In and OutOctets are not normal counters from ifMIB for the PON ports
	// After you have enabled the pmcollect for pon utilization you can get the traffic value from
	// They need to be walked from .1.3.6.1.4.1.637.61.1.35.21.57.1.$metric.$isam_port_id 
	// $isam_port_id  see array above
	// $metric        see array below
	$metrics = [
		2 => 'OltSideUtilUcastDown',
		4 => 'OltSideUtilMcastDown',
		5 => 'OltSideBcastUtilDown',
		6 => 'OltSideUtilDown',
		7 => 'OltSideUtilUp',
		11 => 'OltSideBcastBytesDown',
		12 => 'OltSideOctetsUp',
		13 => 'OltSideOctetsUp',
		14 => 'OltSideDroppedBytesDown',
		15 => 'OltSideDroppedBytesUp',
		16 => 'OltNumOnts',
		25 => 'OltSidePacketsDown',
		26 => 'OltSidePacketsUp',
		27 => 'OltSidePacketsDropDown',
		28 => 'OltSidePacketsDropUp'
	];


	$pon_port_stats = snmpwalk_group($device, '.1.3.6.1.4.1.637.61.1.35.21.57.1');
	//$pon_port_stats = snmpwalk_cache_oid($device, '.1.3.6.1.4.1.637.61.1.35.21.57.1', [] ) ;
	if ($pon_port_stats) {
		foreach($pon_port_stats as $index => $port_stats) { 
			$metric     = explode('.', $index)[13];
			$port_index = explode('.', $index)[14];
			if (strstr($port_stats,' ')) $val = hexdec(str_replace(' ', '', $port_stats));
			else $val = $port_stats;
		    $name = 'PON' . $isam_port_table[$port_index];
			// Build port indexes
			$port_indexes[] = $port_index;
			
			$component_data = [
                'label' => 'pon_'. $port_index,
                'portIndex' => $port_index,
                'name' => $name
            ];

            /*
             * Find existing component ID if PON is already known
             */
            $component_id = false;
            foreach ($components as $tmp_component_id => $tmp_component) {
                if ($tmp_component['portIndex'] == $port_index) {
                    $component_id = $tmp_component_id;
                }
            }

            /*
             * If $component_id is false PON Component doesn't exist
             * Create new component and add it to $components array
             */
            if (! $component_id) {
                $new_component = $component->createComponent($device['device_id'], $module);
                $component_id = key($new_component);
                $components[$component_id] = array_merge($new_component[$component_id], $component_data);
                echo '+';
            } else {
                $components[$component_id] = array_merge($components[$component_id], $component_data);
                echo '.';
            }
        }
    }
	
	/*
     * Loop trough components, check against SNMP QFP indexes and delete if needed
     */
    foreach ($components as $tmp_component_id => $tmp_component) {
        $found = in_array($tmp_component['portIndex'], $port_indexes);
        if (! $found) {
            $component->deleteComponent($tmp_component_id);
            echo '-';
        }
    }
	
	$component->setComponentPrefs($device['device_id'], $components);

    echo "\n";
}

