<?php
add_filter( 'the_date', 'greekDates', 10, 1 );
add_filter( 'get_the_time', 'greekDates', 10, 1 );

function greekDates($the_date) {
    $greek_months = array(
        'Ιανουάριος' => 'Ιανουαρίου',
        'Φεβρουάριος' => 'Φεβρουαρίου',
        'Μάρτιος' => 'Μαρτίου',
        'Απρίλιος' => 'Απριλίου',
        'Μάιος' => 'Μαΐου',
        'Ιούνιος' => 'Ιουνίου',
        'Ιούλιος' => 'Ιουλίου',
        'Άυγουστος' => 'Αυγούστου',
        'Σεπτέμβριος' => 'Σεπτεμβρίου',
        'Οκτώβριος' => 'Οκτωβρίου',
        'Νοέμβριος' => 'Νοεμβρίου',
        'Δεκέμβριος' => 'Δεκεμβρίου'
    );

    $new_date = str_replace(array_keys($greek_months), $greek_months, $the_date);
    return $new_date;
}

?>
