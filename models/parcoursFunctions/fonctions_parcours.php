<?php
/* debut fontions */
/* les permutations d'un tableau */
function permutations($items, $perms = array())
{
    global $tabper;
    if (empty($items)) {
        $tabper[] = $perms;
    } else {
        for ($i = count($items) - 1; $i >= 0; --$i) {
            $newitems = $items;
            $newperms = $perms;
            list($foo) = array_splice($newitems, $i, 1);
            array_unshift($newperms, $foo);

            permutations($newitems, $newperms);
        }
    }
}
/* algorythme de dijkstra pour recherche du parcours le plus court entre $a et $b*/
function calc_chemin($a, $b)
{
    global $_distances;
    $S = array(); //le chemin le plus proche avec son parent et son poids
    $Q = array(); //les nœuds de gauche sans le chemin le plus proche
    foreach (array_keys($_distances) as $val) $Q[$val] = 99999;
    $Q[$a] = 0;

    while (!empty($Q)) {
        $min = array_search(min($Q), $Q);
        if ($min == $b) break;
        foreach ($_distances[$min] as $key => $val) if (!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
            $Q[$key] = $Q[$min] + $val;
            $S[$key] = array($min, $Q[$key]);
        }
        unset($Q[$min]);
    }

    $path = array();
    $pos = $b;

    while ($pos != $a) {
        $path[] = $pos;
        $pos = $S[$pos][0];
    }

    $path[] = $a;
    $path = array_reverse($path);
    return [$S[$b][1], $path];
}
/*  ajout de minutes à une heure        */
function add_time($time, $plusMinutes)
{
    $endTime = strtotime("+{$plusMinutes} minutes", strtotime($time));
    return date('H:i:s', $endTime);
}
