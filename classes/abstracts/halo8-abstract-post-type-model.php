<?php
/**
 * Classe astratta model per le pagine di configurazione
 * @since  1.0.0
 */
abstract class Halo8AbstractPostTypeModel extends Halo8AbstractModel {
    /**
     * Carica i valori passati come argomento all'interno dell'istanza corrente del model
     * assumo che i valori in$args siano già validati dal controller
     * @param  array $args valori da caricare nel model
     * @return null
     * @since  1.0.0
     */
    public function import($args){
        if(isset($args) && !empty($args))
            $filtered = $this->arrayMerge( $args, $this->defaults );
        else
            $filtered = $this->defaults;
        foreach ($filtered as $key => $value) {
            switch($key){

            }
        }
    }
}