<?php

/**
 * Interfaccia necessaria per rispettare la specifiche del design pattern Factory
 * ma non necessaria tecnicamente.
 */

interface IHalo8Factory
{
   public function createController($name);
   public function createView($name);
}