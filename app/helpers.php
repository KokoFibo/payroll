<?php

function format_tgl($tgl) {
    return date('d-M-Y', strtotime($tgl));
}
