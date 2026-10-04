<?php
use function Laravel\Folio\{name, middleware};

name('bulletin.index');
middleware(['auth', 'verified']);

/*
 * Cette page affichait des bulletins et des notes fictifs, avec des liens vers
 * des PDF inexistants. Les notes reelles de l'eleve sont servies par
 * l'espace « Mes notes », qui propose aussi le releve en PDF.
 */
?>
@php(redirect('/dashboard/notes')->send())
