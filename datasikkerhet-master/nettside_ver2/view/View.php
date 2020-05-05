<?php

/**
 * En abstrakt klasse for views.
 * Et view har ansvar for det som vises til brukeren.
 */
abstract class View {

    abstract public function output(Model $model): void;

}

?>