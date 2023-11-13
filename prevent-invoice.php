<?php

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

use WHMCS\Database\Capsule;

add_hook('InvoiceCreationPreEmail', 1, function($vars) {
    $invoiceId = $vars['invoiceid'];

    // Obtém a data de vencimento da fatura
    $dueDate = Capsule::table('tblinvoices')->where('id', $invoiceId)->value('duedate');

    // Converte a data de vencimento para um objeto DateTime
    $dueDateObj = new DateTime($dueDate);

    // Verifica se o dia da semana é sábado ou domingo
    if ($dueDateObj->format('N') >= 6) {
        logActivity("Fatura #$invoiceId não será gerada nos finais de semana.");
        return false; // Impede a geração da fatura
    }

    return true; // Permite a geração da fatura para outros dias
});
